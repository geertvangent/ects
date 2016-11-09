<?php
namespace Ehb\Application\Sync\Data\Processor;

use Chamilo\Application\Weblcms\Storage\DataClass\CourseTool;
use Chamilo\Libraries\File\FileLogger;
use Chamilo\Libraries\Storage\Cache\DataClassCache;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Application\Sync\Data\Storage\DataClass\WeblcmsDocumentZip;
use Ehb\Application\Sync\Data\Storage\DataManager\DataManager;

/**
 * Upgrades the visit tracker table into the course visit tracker table.
 * This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 *
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class WeblcmsDocumentZipProcessor
{

    /**
     * The logger object
     *
     * @var FileLogger
     */
    private $logger;

    /**
     * The tool id's by tool name
     *
     * @var int[string]
     */
    private $tool_ids_by_name;

    /**
     * A chamilo DM to execute the queries.
     * We use this DM because the credentials are stored in Chamilo and we can
     * reuse them
     *
     * @var mixed
     */
    private $dm;

    public function __construct()
    {
        $this->logger = new FileLogger(__DIR__ . '/weblcms_document_zip.log');
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
        $this->dm = \Chamilo\Core\User\Integration\Chamilo\Core\Tracking\Storage\DataManager :: getInstance();
        $this->intialize_tool_ids_by_name();

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
     * Initializes the tool_ids by name
     */
    protected function intialize_tool_ids_by_name()
    {
        $query = 'SELECT id, name FROM weblcms_course_tool';
        $result = $this->dm->get_connection()->query($query);
        while ($course_tool = $result->fetch(\PDO :: FETCH_ASSOC))
        {
            $this->tool_ids_by_name[$course_tool[CourseTool :: PROPERTY_NAME]] = $course_tool[CourseTool :: PROPERTY_ID];
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
                        WeblcmsDocumentZip :: class_name(),
                        WeblcmsDocumentZip :: PROPERTY_ACCESS_DATE))));
        $last_visit = DataManager :: retrieve(WeblcmsDocumentZip :: class_name(), $parameters);

        if (! $last_visit instanceof WeblcmsDocumentZip)
        {
            $start_time = 0;
        }
        else
        {
            $start_time = $last_visit->get_access_date();
        }

        $end_time = time() - 86400;

        $pattern = '%tool_action=zip_and_download%';
        $offset = 0;
        $count = 100000;

        $base_query = 'SELECT * FROM tracking_user_visit WHERE enter_date > ' . $start_time . ' AND enter_date <= ' .
             $end_time . ' AND location LIKE "' . $pattern . '" LIMIT ';

        do
        {
            $query = $base_query . $offset . ', ' . $count;

            $row_counter = 0;

            $result = $this->dm->get_connection()->query($query);
            $resultSize = $result->rowCount();
            while ($visit_tracker_row = $result->fetch(\PDO :: FETCH_ASSOC))
            {
                $this->handle_visit_tracker($visit_tracker_row);
                $row_counter ++;
            }

            $offset += $count;
            $this->log('Upgraded ' . $resultSize . ' records');
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
        $location = $visit_tracker[\Chamilo\Core\User\Integration\Chamilo\Core\Tracking\Storage\DataClass\Visit :: PROPERTY_LOCATION];
        $user_id = $visit_tracker[\Chamilo\Core\User\Integration\Chamilo\Core\Tracking\Storage\DataClass\Visit :: PROPERTY_USER_ID];

        $query = array();

        $url_parts = parse_url($location);
        parse_str($url_parts['query'], $query);

        $course_id = $query['course'];
        $tool_name = $query['tool'];
        $category_id = $query['publication_category'];

        /**
         * When multiple publications are used, it's mostly due to table actions and the publication is not really
         * visited
         */
        if (is_array($category_id))
        {
            return false;
        }

        if (! $tool_name)
        {
            $tool_name = 'home';
        }

        $course_tool_id = $this->tool_ids_by_name[$tool_name];

        if (! $course_tool_id)
        {
            return false;
        }

        $visit = new WeblcmsDocumentZip();
        $visit->set_user_id($user_id);
        $visit->set_course_id($course_id);
        $visit->set_tool_id($course_tool_id);
        $visit->set_category_id($category_id);
        $visit->set_access_date(
            $visit_tracker[\Chamilo\Core\User\Integration\Chamilo\Core\Tracking\Storage\DataClass\Visit :: PROPERTY_ENTER_DATE]);

        if (! $visit->save())
        {
            return false;
        }

        unset($visit);
        DataClassCache :: reset();

        return true;
    }
}