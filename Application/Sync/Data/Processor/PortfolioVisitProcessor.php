<?php
namespace Ehb\Application\Sync\Data\Processor;

use Chamilo\Libraries\File\FileLogger;
use Chamilo\Libraries\Storage\Cache\DataClassCache;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Application\Sync\Data\Storage\DataClass\PortfolioVisit;
use Ehb\Application\Sync\Data\Storage\DataManager\DataManager;

/**
 * Upgrades the visit tracker table into the course visit tracker table. This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 *
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class PortfolioVisitProcessor
{

    /**
     * The logger object
     *
     * @var FileLogger
     */
    private $logger;

    /**
     * A chamilo DM to execute the queries. We use this DM because the credentials are stored in Chamilo and we can
     * reuse them
     *
     * @var mixed
     */
    private $dm;

    public function __construct()
    {
        $this->logger = new FileLogger(__DIR__ . '/portfolio_visit.log');
    }

    public function log($message)
    {
        $this->logger->log_message($message);
        echo $this->logger->get_timestamp() . $message . "\n";
        flush();
    }

    /**
     * Initializes the upgrader and executes the upgrade
     */
    public function run()
    {
        $this->dm = \Chamilo\Core\User\Integration\Core\Tracking\Storage\DataManager :: get_instance();

        try
        {
            $this->process_visit_tracker();
        }
        catch (\Exception $ex)
        {
            var_dump($ex->getMessage());
        }
    }

    /**
     * Upgrades the course visit tracker
     *
     * @return bool
     */
    protected function process_visit_tracker()
    {
        $parameters = new DataClassRetrieveParameters(
            null,
            array(
                new OrderBy(
                    new PropertyConditionVariable(PortfolioVisit :: class_name(), PortfolioVisit :: PROPERTY_ACCESS_DATE))));
        $last_visit = DataManager :: retrieve(PortfolioVisit :: class_name(), $parameters);

        if (! $last_visit instanceof PortfolioVisit)
        {
            $start_time = 0;
        }
        else
        {
            $start_time = $last_visit->get_access_date();
        }

        $end_time = time() - 86400;

        $pattern = '%application=portfolio%';
        $offset = 0;
        $count = 100000;

        $base_query = 'SELECT * FROM tracking_user_visit WHERE enter_date > ' . $start_time . ' AND enter_date <= ' .
             $end_time . ' AND location LIKE "' . $pattern . '" LIMIT ';

        do
        {
            $query = $base_query . $offset . ', ' . $count;

            $row_counter = 0;

            $result = $this->dm->get_connection()->query($query);
            while ($visit_tracker_row = $result->fetch(\PDO :: FETCH_ASSOC))
            {
                $this->handle_visit_tracker($visit_tracker_row);
                $row_counter ++;
            }

            $offset += $count;
            $this->log('Upgraded ' . ($offset + $row_counter) . ' records');
            flush();
        }
        while ($row_counter == $count);
    }

    /**
     * Handles a single visit tracker
     *
     * @param Visit $visit_tracker
     *
     * @return bool
     */
    protected function handle_visit_tracker($visit_tracker)
    {
        $location = $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit :: PROPERTY_LOCATION];
        $user_id = $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit :: PROPERTY_USER_ID];

        $query = array();

        $url_parts = parse_url($location);
        parse_str($url_parts['query'], $query);

        $portfolio_id = $query['poid'];
        $publication_id = $query['pid'];
        $item_id = $query['cid'];

        /**
         * When multiple publications are used, it's mostly due to table actions and the publication is not really
         * visited
         */
        if (is_array($publication_id))
        {
            return false;
        }

        $visit = new PortfolioVisit();
        $visit->set_user_id($user_id);
        $visit->set_portfolio_id($portfolio_id);
        $visit->set_publication_id($publication_id);
        $visit->set_item_id($item_id);
        $visit->set_access_date(
            $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit :: PROPERTY_ENTER_DATE]);
        $visit->set_time(
            $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit :: PROPERTY_LEAVE_DATE] -
                 $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit :: PROPERTY_ENTER_DATE]);

        if (! $visit->save())
        {
            return false;
        }

        unset($visit);
        DataClassCache :: reset();

        return true;
    }
}