<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

use common\libraries\ArrayResultSet;
use user\UserDataManager;

use application\discovery\module\course_results\MarkMoment;
use application\discovery\module\course_results\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $mark_moments = array();
    private $mark = array();
    private $course_results = array();

    /**
     * @param int $programme_id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\CourseResult
     */
    function retrieve_course_results($course_results_parameters)
    {
        $programme_id = $course_results_parameters->get_programme_id();
        $source = $course_results_parameters->get_source();

        if (! isset($this->course_results[$programme_id][$source]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_course_results_advanced] ';
            $query .= 'WHERE programme_id = "' . $programme_id . '" AND source = ' . $source . ' ';
            $query .= 'ORDER BY person_last_name, person_first_name';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->course_results[$programme_id][$source][] = $this->result_to_course_result($course_results_parameters, $result);
                }
            }
        }
        
        return $this->course_results[$programme_id][$source];
    }

    function result_to_course_result($course_results_parameters, $result)
    {
        $course_result = new CourseResult();
        $course_result->set_id($result->id);
        $course_result->set_type($result->type);
        $course_result->set_person_last_name($this->convert_to_utf8($result->person_last_name));
        $course_result->set_person_first_name($this->convert_to_utf8($result->person_first_name));
        $course_result->set_trajectory_type($result->trajectory_type);
        
        $marks = $this->retrieve_marks($course_results_parameters->get_programme_id(), $course_results_parameters->get_source());
        
        foreach ($this->retrieve_mark_moments($course_results_parameters) as $moment)
        {
            if (isset($marks[$result->source][$result->id][$moment->get_id()]))
            {
                $mark = $marks[$result->source][$result->id][$moment->get_id()];
                $course_result->add_mark($mark);
            }
            else
            {
                $mark = Mark :: factory($moment->get_id());
            }
            
            $course_result->add_mark($mark);
        }
        
        return $course_result;
    }

    /**
     * @param string $user$programme_id
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    function retrieve_mark_moments($course_results_parameters)
    {
        $programme_id = $course_results_parameters->get_programme_id();
        $source = $course_results_parameters->get_source();
        
        if (! isset($this->mark_moments[$programme_id][$source]))
        {
            $query = 'SELECT DISTINCT [try_id], [try_name], [try_order] FROM [dbo].[v_discovery_mark_advanced] ';
            $query .= 'WHERE [programme_id] = "' . $programme_id . '" AND source = ' . $source . ' ';
            $query .= 'ORDER BY [try_order]';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $mark_moment = new MarkMoment();
                    $mark_moment->set_id($result->try_id);
                    $mark_moment->set_name($result->try_name);
                    
                    $this->mark_moments[$programme_id][$source][$result->try_id] = $mark_moment;
                }
            }
        }
        
        return $this->mark_moments[$programme_id][$source];
    }

    /**
     * @return multitype:multitype:multitype:stdClass
     */
    function retrieve_marks($programme_id, $source)
    {
        if (! isset($this->marks[$programme_id][$source]))
        {
            $query = 'SELECT [source], [enrollment_programme_id], [result], [status], [sub_status], [try_id] FROM [dbo].[v_discovery_mark_advanced] ';
            $query .= 'WHERE [programme_id] = "' . $programme_id . '"' . ' AND source = ' . $source . ' ';
            
            $statement = $this->get_connection()->prepare($query);
            $result = $statement->execute();
            
            if (! $result instanceof MDB2_Error)
            {
                while ($mark_result = $result->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $mark = new Mark();
                    $mark->set_moment($mark_result->try_id);
                    $mark->set_result($mark_result->result);
                    $mark->set_status($mark_result->status);
                    $mark->set_sub_status($mark_result->sub_status);
                    
                    $this->marks[$programme_id][$source][$mark_result->source][$mark_result->enrollment_programme_id][$mark_result->try_id] = $mark;
                }
            }
        }
        
        return $this->marks[$programme_id][$source];
    }
}
?>