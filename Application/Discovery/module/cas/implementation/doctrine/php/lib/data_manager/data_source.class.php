<?php
namespace application\discovery\module\cas\implementation\doctrine;

use application\discovery\module\cas\DataManagerInterface;

use user\UserDataManager;

class DataSource extends \application\discovery\data_source\doctrine\DataSource implements DataManagerInterface
{
    private $actions = array();
    private $cas_statistics = array();
    private $applications;

    /**
     * @param unknown_type $id
     * @return multitype:int
     */
    function retrieve_actions($parameters)
    {
        $user_id = $parameters->get_user_id();
        if (! isset($this->actions[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT * FROM action WHERE id IN (SELECT DISTINCT action_id FROM statistics WHERE person_id = "' . $official_code . '" ) AND id IN (1, 4, 6)';
            
            $statement = $this->get_connection()->query($query);
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $action = new Action();
                    $action->set_id($result->id);
                    $action->set_title($result->name);
                    
                    $this->actions[$user_id][] = $action;
                }
            }
        }
        
        return $this->actions[$user_id];
    }

    function retrieve_applications()
    {
        
        if (! isset($this->applications))
        {
            $query = 'SELECT * FROM application';
            
            $statement = $this->get_connection()->query($query);
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $application = new Application();
                    $application->set_id($result->id);
                    $application->set_title($result->name);
                    $this->applications[$application->get_id()] = $application;
                }
            }
        }
        
        return $this->applications;
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\cas\implementation\doctrine\Cas
     */
    function retrieve_cas_statistics($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->cas_statistics[$id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT * FROM statistics WHERE person_id = "' . $official_code . '" ORDER BY date DESC, action_id, application_id';
            $statement = $this->get_connection()->query($query);
            
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $cas = new Cas();
                    $cas->set_id($result->id);
                    $cas->set_person_id($result->person_id);
                    $cas->set_action_id($result->action_id);
                    $cas->set_application_id($result->application_id);
                    $cas->set_date($result->date);
                    $this->cas_statistics[$id][] = $cas;
                }
            }
        }
        
        return $this->cas_statistics[$id];
    }

    function count_cas_statistics($parameters)
    {
        $id = $parameters->get_user_id();
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        
        $query = 'SELECT count(id) AS statistics_count FROM statistics WHERE person_id = "' . $official_code . '"';
        
        $statement = $this->get_connection()->query($query);
        
        if (! $statement instanceof \PDOException)
        {
            $record = $statement->fetch(\PDO :: FETCH_NUM);
            return (int) $record[0];
        }
        
        return 0;
    }
}
?>