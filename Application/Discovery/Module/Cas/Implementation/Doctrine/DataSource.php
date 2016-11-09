<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\Action;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\Application;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\CasCount;
use Ehb\Application\Discovery\Module\Cas\Parameters;

class DataSource extends \Ehb\Application\Discovery\DataSource\Doctrine\DataSource
{

    private $actions;

    private $cas_statistics = array();

    private $applications;

    /**
     *
     * @param unknown_type $id
     * @return multitype:int
     */
    public function retrieve_actions($parameters)
    {
        if (! isset($this->actions))
        {
            $query = 'SELECT * FROM action WHERE id IN (1, 4, 6)';
            
            $statement = $this->get_connection()->query($query);
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
                {
                    $action = new Action();
                    $action->set_id($result->id);
                    $action->set_title($result->name);
                    
                    $this->actions[] = $action;
                }
            }
        }
        
        return $this->actions;
    }

    public function retrieve_applications()
    {
        if (! isset($this->applications))
        {
            $query = 'SELECT * FROM application';
            
            $statement = $this->get_connection()->query($query);
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
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
     *
     * @param int $id
     * @return multitype:\application\discovery\module\cas\implementation\doctrine\Cas
     */
    public function retrieve_cas_statistics($parameters)
    {
        $user_id = $parameters->get_user_id();
        $mode = $parameters->get_mode();
        
        if ($mode == Parameters::MODE_GENERAL)
        {
            $user_id = 0;
        }
        
        if (! isset($this->cas_statistics[$user_id]))
        {
            if ($mode == Parameters::MODE_GENERAL)
            {
                $query = 'SELECT count(id) AS \'count\', application_id, action_id, date_format(date, \'%Y-%m\') AS \'date\'
                    FROM cas_data.statistics
                    WHERE ((application_id IS NOT NULL AND action_id = 4) OR (application_id IS NULL AND action_id IN (1, 6)))
                    GROUP BY date_format(date, \'%Y-%m\'), application_id , action_id
                    ORDER BY date DESC, action_id, application_id';
            }
            else
            {
                $user = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
                    \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
                    $user_id);
                $official_code = $user->get_official_code();
                
                $query = 'SELECT count(id) AS \'count\', person_id, application_id, action_id, date_format(date, \'%Y-%m\') AS \'date\'
                    FROM cas_data.statistics
                    WHERE person_id = "' .
                     $official_code . '" AND ((application_id IS NOT NULL AND action_id = 4) OR (application_id IS NULL AND action_id IN (1, 6)))
                    GROUP BY person_id , date_format(date, \'%Y-%m\'), application_id , action_id
                    ORDER BY date DESC, action_id, application_id';
            }
            
            $statement = $this->get_connection()->query($query);
            
            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
                {
                    $cas = new CasCount();
                    $cas->set_count($result->count);
                    
                    if ($mode == Parameters::MODE_GENERAL)
                    {
                        $cas->set_person_id(0);
                    }
                    else
                    {
                        $cas->set_person_id($result->person_id);
                    }
                    
                    $cas->set_action_id($result->action_id);
                    $cas->set_application_id($result->application_id);
                    $cas->set_date($result->date);
                    
                    $this->cas_statistics[$user_id][] = $cas;
                }
            }
        }
        
        return $this->cas_statistics[$user_id];
    }

    public function count_cas_statistics($parameters)
    {
        $user_id = $parameters->get_user_id();
        $mode = $parameters->get_mode();
        
        if ($mode == Parameters::MODE_GENERAL)
        {
            $query = 'SELECT count(id) AS statistics_count FROM statistics WHERE ((application_id IS NOT NULL AND action_id = 4) OR (application_id IS NULL AND action_id IN (1, 6)))';
        }
        else
        {
            $user = \Chamilo\Core\User\Storage\DataManager::getInstance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT count(id) AS statistics_count FROM statistics WHERE person_id = "' . $official_code .
                 '" AND ((application_id IS NOT NULL AND action_id = 4) OR (application_id IS NULL AND action_id IN (1, 6)))';
        }
        
        $statement = $this->get_connection()->query($query);
        
        if (! $statement instanceof \PDOException)
        {
            $record = $statement->fetch(\PDO::FETCH_NUM);
            return (int) $record[0];
        }
        
        return 0;
    }

    public function count_cas_graph_statistics($condition)
    {
        $query = 'SELECT count(id) AS statistics_count FROM statistics';
        $translator = new ConditionTranslator($this);
        $query .= $translator->render_query($condition);
        
        $statement = $this->get_connection()->query($query);
        
        if (! $statement instanceof \PDOException)
        {
            $record = $statement->fetch(\PDO::FETCH_NUM);
            return (int) $record[0];
        }
        
        return 0;
    }

    public function retrieve_first_date($user_id, $action, $application)
    {
        if ($user_id != 0)
        {
            
            $user = \Chamilo\Core\User\Storage\DataManager::getInstance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();
            
            if ($application instanceof Application)
            {
                
                $query = 'SELECT date FROM statistics WHERE person_id = "' . $official_code . '" AND action_id = "' .
                     $action->get_id() . '" AND application_id = "' . $application->get_id() . '" ORDER BY date LIMIT 1';
            }
            else
            {
                $query = 'SELECT date FROM statistics WHERE person_id = "' . $official_code . '" AND action_id = "' .
                     $action->get_id() . '" AND application_id IS NULL ORDER BY date LIMIT 1';
            }
        }
        else
        {
            if ($application instanceof Application)
            {
                
                $query = 'SELECT date FROM statistics WHERE action_id = "' . $action->get_id() .
                     '" AND application_id = "' . $application->get_id() . '" ORDER BY date LIMIT 1';
            }
            else
            {
                $query = 'SELECT date FROM statistics WHERE action_id = "' . $action->get_id() .
                     '" AND application_id IS NULL ORDER BY date LIMIT 1';
            }
        }
        
        $statement = $this->get_connection()->query($query);
        
        if (! $statement instanceof \PDOException)
        {
            $result = $statement->fetch(\PDO::FETCH_OBJ);
            return $result->date;
        }
    }
    
    // helper for ConditionTranslator
    public function get_alias($table_name)
    {
        return $table_name;
    }

    public function escape_column_name($name, $table_alias = null)
    {
        $quoted_name = $this->get_connection()->quoteIdentifier($name);
        if (! is_null($table_alias))
        {
            return $this->get_connection()->quoteIdentifier($table_alias) . '.' . $quoted_name;
        }
        else
        {
            return $quoted_name;
        }
    }

    public function quote($value, $type = null, $quote = true, $escape_wildcards = false)
    {
        return $this->get_connection()->quote($value, $type, $quote, $escape_wildcards);
    }
}