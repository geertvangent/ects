<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\DataManager;

use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Doctrine\DBAL\Driver\PDOStatement;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\File\ImageManipulation\ImageManipulation;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Utilities\String\Text;
use Chamilo\Libraries\File\Path;
use Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex\Course;
use Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex\Training;
use Chamilo\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Faculty;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class DataSource extends \Chamilo\Application\Discovery\DataSource\Bamaflex\DataSource
{

    public function retrieve_photo($id, $web = true)
    {
        $relative_path = 'photo/' . Text :: char_at($id, 0) . '/' . $id . '.jpg';
        $path = Path :: getInstance()->getStoragePath() .
             ClassnameUtilities :: getInstance()->namespaceToPath(__NAMESPACE__) . '/' . $relative_path;

        if (! file_exists($path))
        {
            $condition = new EqualityCondition(new StaticColumnConditionVariable('id'), new StaticConditionVariable($id));

            $query = 'SELECT * FROM v_discovery_profile_photo WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection());

            $statement = $this->get_connection()->query($query);

            if ($statement instanceof PDOStatement)
            {
                $object = $statement->fetch(\PDO :: FETCH_OBJ);

                if (! empty($object->photo))
                {
                    Filesystem :: write_to_file($path, $object->photo);

                    $image_manipulation = ImageManipulation :: factory($path);
                    $image_manipulation->scale(600, 600, ImageManipulation :: SCALE_INSIDE);
                    $image_manipulation->write_to_file($path);
                }
                else
                {
                    Filesystem :: copy_file(Theme :: getInstance()->getCommonImagePath(false) . 'unknown.jpg', $path);
                }
            }
            else
            {
                Filesystem :: copy_file(Theme :: getInstance()->getCommonImagePath(false) . 'unknown.jpg', $path);
            }
        }

        return Path :: getInstance()->getStoragePath(__NAMESPACE__, $web) . $relative_path;
    }

    public function retrieve_faculty($faculty_id)
    {
        $source = 1;
        if ($faculty_id && $source)
        {
            if (! isset($this->faculties[$faculty_id][$source]))
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

                    $this->faculties[$faculty_id][$source] = $faculty;
                }
            }
            return $this->faculties[$faculty_id][$source];
        }
        else
        {
            return false;
        }
    }

    public function retrieve_training($training_id)
    {
        $source = 1;

        if (! isset($this->trainings[$training_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('id'),
                new StaticConditionVariable($training_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'),
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);

            $query = 'SELECT * FROM v_discovery_training_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection());

            $statement = $this->get_connection()->query($query);

            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $training = new Training();
                    $training->set_source($result->source);
                    $training->set_id($result->id);
                    $training->set_name($this->convert_to_utf8($result->name));
                    $training->set_year($this->convert_to_utf8($result->year));
                    $training->set_credits($result->credits);
                    $training->set_domain_id($result->domain_id);
                    $training->set_domain($this->convert_to_utf8($result->domain));
                    $training->set_goals(nl2br($this->convert_to_utf8($result->goals)));
                    $training->set_type_id($result->type_id);
                    $training->set_type($this->convert_to_utf8($result->type));
                    $training->set_bama_type($result->bama_type);
                    $training->set_faculty_id($result->faculty_id);
                    $training->set_faculty($this->convert_to_utf8($result->faculty));
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);

                    $this->trainings[$training_id][$source] = $training;
                }
            }
        }

        return $this->trainings[$training_id][$source];
    }

    public function retrieve_programme($programme_id)
    {
        $source = 1;

        if (! isset($this->course[$programme_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('id'),
                new StaticConditionVariable($programme_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'),
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);

            $query = 'SELECT * FROM v_discovery_course_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection());

            $statement = $this->get_connection()->query($query);

            if ($statement instanceof PDOStatement)
            {
                $object = $result = $statement->fetch(\PDO :: FETCH_OBJ);

                if ($object instanceof \StdClass)
                {
                    $course = new Course();
                    $course->set_id($object->id);
                    $course->set_year($object->year);
                    $course->set_faculty_id($object->faculty_id);
                    $course->set_faculty($this->convert_to_utf8($object->faculty));
                    $course->set_training_id($object->training_id);
                    $course->set_training($this->convert_to_utf8($object->training));
                    $course->set_name($this->convert_to_utf8($object->name));
                    $course->set_source($object->source);
                    $course->set_trajectory_part($object->trajectory_part);
                    $course->set_credits($object->credits);
                    $course->set_programme_type($object->programme_type);
                    $course->set_weight($object->weight);
                    $course->set_timeframe_visual_id($object->timeframe_visual_id);
                    $course->set_timeframe_id($object->timeframe_id);
                    $course->set_result_scale_id($object->result_scale_id);
                    $course->set_deliberation($object->deliberation);
                    $course->set_score_calculation($object->score_calculation);
                    $course->set_level($this->convert_to_utf8($object->level));
                    $course->set_kind($this->convert_to_utf8($object->kind));
                    $course->set_goals($this->convert_to_utf8($object->goals));
                    $course->set_contents($this->convert_to_utf8($object->contents));
                    $course->set_coaching($this->convert_to_utf8($object->coaching));
                    $course->set_succession($this->convert_to_utf8($object->succession));
                    $course->set_jury($object->jury);
                    $course->set_repleacable($object->repleacable);
                    $course->set_training_unit($this->convert_to_utf8($object->training_unit));

                    $this->course[$programme_id][$source] = $course;
                }
            }
        }
        return $this->course[$programme_id][$source];
    }
}
