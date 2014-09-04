<?php
namespace application\ehb_sync\data;

use libraries\FileLogger;
use libraries\DataClassRetrieveParameters;
use libraries\ObjectTableOrder;
use libraries\DataClassCache;

/**
 * Upgrades the visit tracker table into the course visit tracker table. This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 *
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class RepositoryDocumentDownloadProcessor
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
        $this->logger = new FileLogger(__DIR__ . '/repository_document_download.log');
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
            array(new ObjectTableOrder(RepositoryDocumentDownload :: PROPERTY_ACCESS_DATE, SORT_DESC)));
        $last_visit = DataManager :: retrieve(RepositoryDocumentDownload :: class_name(), $parameters);

        if (! $last_visit instanceof RepositoryDocumentDownload)
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
             ' AND location LIKE "%application=repository%" AND location LIKE "%go=document_downloader%" LIMIT ';

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
        $location = $visit_tracker[\core\user\integration\core\tracking\Visit :: PROPERTY_LOCATION];
        $user_id = $visit_tracker[\core\user\integration\core\tracking\Visit :: PROPERTY_USER_ID];

        $query = array();

        $url_parts = parse_url($location);
        parse_str($url_parts['query'], $query);

        $document_id = $query['object'];

        /**
         * When multiple publications are used, it's mostly due to table actions and the publication is not really
         * visited
         */
        if (is_array($document_id))
        {
            return false;
        }

        $visit = new RepositoryDocumentDownload();
        $visit->set_user_id($user_id);
        $visit->set_document_id($document_id);
        $visit->set_access_date($visit_tracker[\core\user\integration\core\tracking\Visit :: PROPERTY_ENTER_DATE]);

        if (! $visit->save())
        {
            return false;
        }

        unset($visit);
        DataClassCache :: reset();

        return true;
    }
}