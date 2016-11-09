<?php
namespace Ehb\Application\Sync\Data\Processor;

use Chamilo\Application\Portfolio\Storage\DataClass\Publication;
use Chamilo\Core\Repository\ContentObject\Introduction\Storage\DataClass\Introduction;
use Chamilo\Libraries\File\FileLogger;
use Chamilo\Libraries\Storage\Cache\DataClassCache;

/**
 * Upgrades the visit tracker table into the course visit tracker table.
 * This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 * 
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class PortfolioIntroductionProcessor
{

    /**
     * The logger object
     * 
     * @var FileLogger
     */
    private $logger;

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
        $this->logger = new FileLogger(__DIR__ . '/portfolio_introduction.log');
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
        $this->dm = \Chamilo\Application\Portfolio\Storage\DataManager :: getInstance();
        
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
        $query = 'SELECT user_id, introduction FROM old_portfolio_portfolio_information WHERE introduction IS NOT NULL AND introduction != \'\' AND introduction != \'intro\'';
        
        $result = $this->dm->get_connection()->query($query);
        
        while ($portfolio_introduction_row = $result->fetch(\PDO :: FETCH_ASSOC))
        {
            $this->handle_portfolio_introduction($portfolio_introduction_row);
        }
        
        flush();
    }

    /**
     * Handles a single visit tracker
     * 
     * @param Visit $visit_tracker
     *
     * @return bool
     */
    protected function handle_portfolio_introduction($portfolio_introduction)
    {
        $introduction = $portfolio_introduction['introduction'];
        $user_id = $portfolio_introduction['user_id'];
        
        $introduction = new Introduction();
        $introduction->set_owner_id($portfolio_introduction['user_id']);
        $introduction->set_title('Portfolio introductie');
        $introduction->set_description($portfolio_introduction['introduction']);
        $introduction->set_parent_id(0);
        
        if (! $introduction->create())
        {
            return false;
        }
        else
        {
            $publication = new Publication();
            $publication->set_content_object_id($introduction->get_id());
            $publication->set_publisher_id($introduction->get_owner_id());
            $publication->set_published(time());
            $publication->set_modified(time());
            $publication->create();
            
            if (! $publication->create())
            {
                return false;
            }
        }
        
        unset($introduction);
        DataClassCache :: reset();
        
        return true;
    }
}