<?php
namespace Application\EhbSync\data\processor;

use libraries\file\FileLogger;
use libraries\storage\DataClassRetrieveParameters;
use libraries\storage\PropertyConditionVariable;
use libraries\storage\Cache\DataClassCache;
use libraries\storage\OrderBy;

/**
 * Upgrades the visit tracker table into the course visit tracker table. This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 *
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class RepositoryExporterProcessor
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
        $this->logger = new FileLogger(__DIR__ . '/repository_exporter.log');
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
        $this->dm = \core\user\integration\core\tracking\DataManager :: get_instance();

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
                    new PropertyConditionVariable(
                        RepositoryExporter :: class_name(),
                        RepositoryExporter :: PROPERTY_ACCESS_DATE))));
        $last_visit = DataManager :: retrieve(RepositoryExporter :: class_name(), $parameters);

        if (! $last_visit instanceof RepositoryExporter)
        {
            $start_time = 0;
        }
        else
        {
            $start_time = $last_visit->get_access_date();
        }

        $end_time = time() - 86400;

        $offset = 0;
        $count = 100000;

        $base_query = 'SELECT * FROM tracking_user_visit WHERE enter_date > ' . $start_time . ' AND enter_date <= ' .
             $end_time .
             ' AND location LIKE "%application=repository%" AND location LIKE "%go=exporter%" AND location LIKE "%export_type=%" LIMIT ';

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
        $location = $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit  :: PROPERTY_LOCATION];
        $user_id = $visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit  :: PROPERTY_USER_ID];

        $query = array();

        $url_parts = parse_url($location);
        parse_str($url_parts['query'], $query);

        $content_object_ids = $query['object'];
        $category_ids = $query['category'];
        $type = $query['export_type'];

        foreach ($content_object_ids as $content_object_id)
        {
            $visit = new RepositoryExporter();
            $visit->set_user_id($user_id);
            $visit->set_content_object_id($content_object_id);
            $visit->set_type($type);
            $visit->set_access_date($visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit  :: PROPERTY_ENTER_DATE]);

            if (! $visit->save())
            {
                return false;
            }
        }

        foreach ($category_ids as $category_id)
        {
            $visit = new RepositoryExporter();
            $visit->set_user_id($user_id);
            $visit->set_category_id($category_id);
            $visit->set_type($type);
            $visit->set_access_date($visit_tracker[\Chamilo\Core\User\Integration\Core\Tracking\Tracker\Visit  :: PROPERTY_ENTER_DATE]);

            if (! $visit->save())
            {
                return false;
            }
        }

        unset($visit);
        DataClassCache :: reset();

        return true;
    }
}