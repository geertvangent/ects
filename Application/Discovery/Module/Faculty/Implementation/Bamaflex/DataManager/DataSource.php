<?php
namespace Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\DataManager;

use Ehb\Application\Discovery\DataSource\Bamaflex\HistoryReference;
use Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Dean;
use Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Faculty;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;

class DataSource extends \Ehb\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $faculties;

    private $faculty;

    private $deans;

    private $years;

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\faculty\implementation\bamaflex\TeachingAssignment
     */
    public function retrieve_faculties($year)
    {
        if (! isset($this->faculties[$year]))
        {
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('year'), 
                new StaticConditionVariable($year));
            
            $query = 'SELECT * FROM v_discovery_faculty_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $faculty = new Faculty();
                    $faculty->set_source($result->source);
                    $faculty->set_id($result->id);
                    $faculty->set_name($this->convert_to_utf8($result->name));
                    $faculty->set_year($this->convert_to_utf8($result->year));
                    $faculty->set_deans($this->retrieve_deans($faculty->get_source(), $faculty->get_id()));
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $faculty->add_previous_reference($reference);
                    
                    $next = $this->retrieve_faculty_next_id($faculty);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $faculty->add_next_reference($reference);
                    
                    $this->faculties[$year][] = $faculty;
                }
            }
        }
        
        return $this->faculties[$year];
    }

    public function retrieve_faculty($faculty_parameters)
    {
        $faculty_id = $faculty_parameters->get_parameter(Faculty :: PROPERTY_ID);
        $source = $faculty_parameters->get_parameter(Faculty :: PROPERTY_SOURCE);
        
        if ($faculty_id && $source)
        {
            if (! isset($this->faculty[$faculty_id][$source]))
            {
                $conditions = array();
                $conditions[] = new EqualityCondition(
                    new StaticColumnConditionVariable('id'), 
                    new StaticConditionVariable($faculty_id));
                $conditions[] = new EqualityCondition(
                    new StaticColumnConditionVariable('source'), 
                    new StaticConditionVariable($source));
                $condition = new AndCondition($conditions);
                
                $query = 'SELECT * FROM v_discovery_faculty_advanced WHERE ' .
                     ConditionTranslator :: render($condition, null, $this->get_connection());
                
                $statement = $this->get_connection()->query($query);
                
                if ($statement instanceof PDOStatement)
                {
                    $result = $statement->fetch(\PDO :: FETCH_OBJ);
                    
                    $faculty = new Faculty();
                    $faculty->set_source($result->source);
                    $faculty->set_id($result->id);
                    $faculty->set_name($this->convert_to_utf8($result->name));
                    $faculty->set_year($this->convert_to_utf8($result->year));
                    $faculty->set_deans($this->retrieve_deans($faculty->get_source(), $faculty->get_id()));
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $faculty->add_previous_reference($reference);
                    
                    $next = $this->retrieve_faculty_next_id($faculty);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $faculty->add_next_reference($reference);
                    
                    $this->faculty[$faculty_id][$source] = $faculty;
                }
            }
            return $this->faculty[$faculty_id][$source];
        }
        else
        {
            return false;
        }
    }

    public function retrieve_faculty_next_id($faculty)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('previous_id'), 
            new StaticConditionVariable($faculty->get_id()));
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('source'), 
            new StaticConditionVariable($faculty->get_source()));
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT id, source FROM v_discovery_faculty_advanced WHERE ' .
             ConditionTranslator :: render($condition, null, $this->get_connection());
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            return $result = $statement->fetch(\PDO :: FETCH_OBJ);
        }
        else
        {
            return false;
        }
    }

    public function retrieve_years()
    {
        if (! isset($this->years))
        {
            $query = 'SELECT DISTINCT year FROM v_discovery_faculty_advanced ORDER BY year DESC';
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->years[] = $result->year;
                }
            }
        }
        
        return $this->years;
    }

    public function retrieve_deans($source, $faculty_id)
    {
        if (! isset($this->deans[$source][$faculty_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('faculty_id'), 
                new StaticConditionVariable($faculty_id));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_faculty_dean_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY person';
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $dean = new Dean();
                    $dean->set_source($result->source);
                    $dean->set_id($result->id);
                    $dean->set_faculty_id($result->faculty_id);
                    $dean->set_function_id($result->function_id);
                    $dean->set_person($this->convert_to_utf8($result->person));
                    $dean->set_function($this->convert_to_utf8($result->function));
                    
                    $this->deans[$source][$faculty_id][] = $dean;
                }
            }
        }
        return $this->deans[$source][$faculty_id];
    }
}
