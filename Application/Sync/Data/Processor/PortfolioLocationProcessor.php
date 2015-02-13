<?php
namespace Ehb\Application\Sync\Data\Processor;

use Chamilo\Libraries\File\FileLogger;

/**
 * Upgrades the visit tracker table into the course visit tracker table.
 * This script has been separated from the normal
 * upgrade procedure because of the amount of data and time it takes to finish this upgrade.
 * 
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class PortfolioLocationProcessor
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
        $this->logger = new FileLogger(__DIR__ . '/portfolio_location.log');
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
        $this->dm = \Chamilo\Application\Portfolio\Storage\DataManager :: get_instance();
        
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
        $publications = \Chamilo\Application\Portfolio\Storage\DataManager :: retrieves(
            \Chamilo\Application\Portfolio\Storage\DataClass\Publication :: class_name());
        
        while ($publication = $publications->next_result())
        {
            // Initialize the root location
            $root = \Chamilo\Application\Portfolio\Rights :: get_instance()->initialize_user_tree(
                $publication->get_publisher_id());
            
            // Create a location for all publications
            $location = \Chamilo\Application\Portfolio\Rights :: get_instance()->create_location_in_users_subtree(
                \Chamilo\Application\Portfolio\Rights :: TYPE_PUBLICATION, 
                $publication->get_id(), 
                $root->get_id(), 
                $publication->get_publisher_id(), 
                false, 
                false);
            
            // Retrieve and convert configured user rights for this location
            $query = 'SELECT user_id, right_id FROM desiderius2013.old_portfolio_portfolio_user_right_location AS oppurl
JOIN old_portfolio_portfolio_location AS oppl ON oppurl.location_id = oppl.id
WHERE type = 1 AND item_id =' . $location->get_id();
            
            $result = $this->dm->get_connection()->query($query);
            while ($user_right = $result->fetch(\PDO :: FETCH_ASSOC))
            {
                $rights_location_entity_right = new \Chamilo\Application\Portfolio\Storage\DataClass\RightsLocationEntityRight();
                $rights_location_entity_right->set_location_id($location->get_id());
                
                switch ($user_right['user_id'])
                {
                    case 0 :
                        $rights_location_entity_right->set_entity_id(0);
                        $rights_location_entity_right->set_entity_type(0);
                        break;
                    case 1 :
                        $rights_location_entity_right->set_entity_id(0);
                        $rights_location_entity_right->set_entity_type(0);
                        break;
                    default :
                        $rights_location_entity_right->set_entity_id($user_right['user_id']);
                        $rights_location_entity_right->set_entity_type(1);
                        break;
                }
                
                switch ($user_right['right_id'])
                {
                    case 1 :
                        $rights_location_entity_right->set_right_id(1);
                        break;
                    case 2 :
                        continue;
                        break;
                    case 3 :
                        $rights_location_entity_right->set_right_id(2);
                        break;
                    case 4 :
                        $rights_location_entity_right->set_right_id(3);
                        break;
                }
                
                $rights_location_entity_right->create();
            }
            
            // Retrieve and convert configured group rights for this location
            $query = 'SELECT group_id, right_id FROM desiderius2013.old_portfolio_portfolio_group_right_location AS oppurl
JOIN old_portfolio_portfolio_location AS oppl ON oppurl.location_id = oppl.id
WHERE type = 1 AND item_id =' . $location->get_id();
            
            $result = $this->dm->get_connection()->query($query);
            while ($group_right = $result->fetch(\PDO :: FETCH_ASSOC))
            {
                $rights_location_entity_right = new \Chamilo\Application\Portfolio\Storage\DataClass\RightsLocationEntityRight();
                $rights_location_entity_right->set_location_id($location->get_id());
                $rights_location_entity_right->set_entity_id($group_right['group_id']);
                $rights_location_entity_right->set_entity_type(2);
                
                switch ($group_right['right_id'])
                {
                    case 1 :
                        $rights_location_entity_right->set_right_id(1);
                        break;
                    case 2 :
                        continue;
                        break;
                    case 3 :
                        $rights_location_entity_right->set_right_id(2);
                        break;
                    case 4 :
                        $rights_location_entity_right->set_right_id(3);
                        break;
                }
                
                $rights_location_entity_right->create();
            }
        }
        
        flush();
    }
}