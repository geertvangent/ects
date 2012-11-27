<?php
namespace application\discovery\module\cas\implementation\doctrine;

use common\libraries\DoctrineConditionTranslator;

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

            $query = 'SELECT count(id) AS \'count\', person_id, application_id, action_id, date_format(date, \'%Y-%m\') AS \'date\'
                    FROM cas_data.statistics
                    WHERE person_id = "' . $official_code . '" AND ((application_id IS NOT NULL AND action_id = 4) OR (application_id IS NULL AND action_id IN (1, 6)))
                    GROUP BY person_id , date_format(date, \'%Y-%m\'), application_id , action_id
                    ORDER BY date DESC, action_id, application_id';
            $statement = $this->get_connection()->query($query);

            if (! $statement instanceof \PDOException)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $cas = new CasCount();
                    $cas->set_count($result->count);
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

        $query = 'SELECT count(id) AS statistics_count FROM statistics WHERE person_id = "' . $official_code . '" AND application_id IS NOT NULL AND action_id IN (1, 4, 6)';

        $statement = $this->get_connection()->query($query);

        if (! $statement instanceof \PDOException)
        {
            $record = $statement->fetch(\PDO :: FETCH_NUM);
            return (int) $record[0];
        }

        return 0;
    }

    function count_cas_graph_statistics($condition)
    {
        $query = 'SELECT count(id) AS statistics_count FROM statistics';
        $translator = new DoctrineConditionTranslator($this);
        $query .= $translator->render_query($condition);

        $statement = $this->get_connection()->query($query);

        if (! $statement instanceof \PDOException)
        {
            $record = $statement->fetch(\PDO :: FETCH_NUM);
            return (int) $record[0];
        }

        return 0;
    }

    function retrieve_first_date($user_id, $action, $application)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($user_id);
        $official_code = $user->get_official_code();

        $query = 'SELECT date FROM statistics WHERE person_id = "' . $official_code . '" AND action_id = "' . $action->get_id() . '" AND application_id = "' . $application->get_id() . '" ORDER BY date';
        $statement = $this->get_connection()->query($query);

        if (! $statement instanceof \PDOException)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->date;
        }
    }

    //helper for DoctrineConditionTranslator
    function get_alias($table_name)
    {
        return $table_name;
    }

    function escape_column_name($name, $table_alias = null)
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

    function quote($value, $type = null, $quote = true, $escape_wildcards = false)
    {
        return $this->get_connection()->quote($value, $type, $quote, $escape_wildcards);
    }
}
?>