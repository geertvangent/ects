<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\ImageManipulation;
use common\libraries\Theme;
use common\libraries\Filesystem;
use common\libraries\Text;
use common\libraries\Path;
use application\discovery\module\course\implementation\bamaflex\Course;
use application\discovery\module\training\implementation\bamaflex\Training;
use application\discovery\module\faculty\implementation\bamaflex\Faculty;
use application\discovery\module\photo\DataManagerInterface;
use application\discovery\module\profile\Photo;
use MDB2_Error;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    function retrieve_photo($id, $web = true)
    {
        $relative_path = 'photo/' . Text :: char_at($id, 0) . '/' . $id . '.jpg';
        $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/' . $relative_path;

        if (! file_exists($path))
        {
            $query = 'SELECT * FROM v_discovery_profile_photo WHERE id = "' . $id . '"';

            $statement = $this->get_connection()->prepare($query);
            $result = $statement->execute();

            if (! $result instanceof MDB2_Error)
            {
                $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);

                if (! empty($object->photo))
                {
                    Filesystem :: write_to_file($path, $object->photo);

                    $image_manipulation = ImageManipulation :: factory($path);
                    $image_manipulation->scale(600, 600, ImageManipulation :: SCALE_INSIDE);
                    $image_manipulation->write_to_file($path);
                }
                else
                {
                    Filesystem :: copy_file(Theme :: get_common_image_system_path() . 'unknown.jpg', $path);
                }
            }
            else
            {
                Filesystem :: copy_file(Theme :: get_common_image_system_path() . 'unknown.jpg', $path);
            }
        }

        return Path :: get($web ? WEB_FILE_PATH : SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/' . $relative_path;
    }

    function retrieve_faculty($faculty_id)
    {
        $source = 1;
        if ($faculty_id && $source)
        {
            if (! isset($this->faculties[$faculty_id][$source]))
            {
                $query = 'SELECT * FROM v_discovery_faculty_advanced WHERE id = "' . $faculty_id . '" AND source = "' . $source . '"';

                $statement = $this->get_connection()->prepare($query);
                $results = $statement->execute();

                if (! $results instanceof MDB2_Error)
                {
                    $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);

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

    function retrieve_training($training_id)
    {
        $source = 1;

        if (! isset($this->trainings[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_advanced WHERE id = "' . $training_id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
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

    function retrieve_programme($programme_id)
    {
        $source = 1;

        if (! isset($this->course[$programme_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_course_advanced ';
            $query .= 'WHERE id = "' . $programme_id . '" AND source = ' . $source . ' ';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                $object = $results->fetchRow(MDB2_FETCHMODE_OBJECT);

                if ($object instanceof \stdClass)
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
?>