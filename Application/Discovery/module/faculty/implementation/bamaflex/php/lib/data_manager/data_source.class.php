<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use common\libraries\AndCondition;
use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\EqualityCondition;
use common\libraries\DoctrineConditionTranslator;
use application\discovery\data_source\bamaflex\HistoryReference;
use application\discovery\module\faculty\DataManagerInterface;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
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
            $condition = new EqualityCondition('year', $year);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_faculty_advanced ' . $translator->render_query($condition) . ' ORDER BY year DESC, name';
            
            $statement = $this->query($query);
            
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
                $conditions[] = new EqualityCondition('id', $faculty_id);
                $conditions[] = new EqualityCondition('source', $source);
                $condition = new AndCondition($conditions);
                $translator = DoctrineConditionTranslator :: factory($this);
                
                $query = 'SELECT * FROM v_discovery_faculty_advanced ' . $translator->render_query($condition);
                
                $statement = $this->query($query);
                
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
        $conditions[] = new EqualityCondition('previous_id', $faculty->get_id());
        $conditions[] = new EqualityCondition('source', $faculty->get_source());
        $condition = new AndCondition($conditions);
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT id, source FROM v_discovery_faculty_advanced ' . $translator->render_query($condition);
        $statement = $this->query($query);
        
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
            
            $statement = $this->query($query);
            
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
            $conditions[] = new EqualityCondition('source', $source);
            $conditions[] = new EqualityCondition('faculty_id', $faculty_id);
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_faculty_dean_advanced ' . $translator->render_query($condition) . ' ORDER BY person';
            $statement = $this->query($query);
            
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
