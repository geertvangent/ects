<?php
namespace application\discovery\module\course;

use libraries\utilities\Utilities;
use libraries\storage\DataClass;

/**
 * application.discovery.module.courses.discovery
 * 
 * @author Hans De Bisschop
 */
class Course extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Course properties
     */
    const PROPERTY_YEAR = 'year';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_NAME = 'name';
    const PROPERTY_ID = 'id';
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAJECTORY_PART = 'tranjectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_PROGRAMME_TYPE = 'programme_type';
    const PROPERTY_WEIGHT = 'weight';
    const PROPERTY_TIMEFRAME_VISUAL_ID = 'timeframe_visual_id';
    const PROPERTY_TIMEFRAME_ID = 'timeframe_id';
    const PROPERTY_RESULT_SCALE_ID = 'result_scale_id';
    const PROPERTY_DELIBERATION = 'deliberation';
    const PROPERTY_LEVEL = 'level';
    const PROPERTY_KIND = 'kind';
    const PROPERTY_GOALS = 'goals';
    const PROPERTY_CONTENTS = 'contents';
    const PROPERTY_COACHING = 'coaching';
    const PROPERTY_SUCCESSION = 'succession';
    const PROPERTY_JURY = 'jury';
    const PROPERTY_REPLEACABLE = 'repleacable';
    const PROPERTY_TRAINING_UNIT = 'training_unit';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_TRAINING;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_ID;
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_TYPE;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_VISUAL_ID;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_ID;
        $extended_property_names[] = self :: PROPERTY_RESULT_SCALE_ID;
        $extended_property_names[] = self :: PROPERTY_DELIBERATION;
        $extended_property_names[] = self :: PROPERTY_LEVEL;
        $extended_property_names[] = self :: PROPERTY_KIND;
        $extended_property_names[] = self :: PROPERTY_GOALS;
        $extended_property_names[] = self :: PROPERTY_CONTENTS;
        $extended_property_names[] = self :: PROPERTY_COACHING;
        $extended_property_names[] = self :: PROPERTY_SUCCESSION;
        $extended_property_names[] = self :: PROPERTY_JURY;
        $extended_property_names[] = self :: PROPERTY_REPLEACABLE;
        $extended_property_names[] = self :: PROPERTY_TRAINING_UNIT;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    public function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    public function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    public function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    public function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    public function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    public function training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public function get_id()
    {
        return $this->get_default_property(self :: PROPERTY_ID);
    }

    public function set_id($id)
    {
        $this->set_default_property(self :: PROPERTY_ID, $id);
    }

    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    public function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    public function credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    public function get_programme_type()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_TYPE);
    }

    public function set_programme_type($programme_type)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_TYPE, $programme_type);
    }

    public function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    public function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    public function get_timeframe_visual_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID);
    }

    public function set_timeframe_visual_id($timeframe_visual_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID, $timeframe_visual_id);
    }

    public function get_timeframe_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_ID);
    }

    public function set_timeframe_id($timeframe_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    public function get_result_scale_id()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT_SCALE_ID);
    }

    public function set_result_scale_id($result_scale_id)
    {
        $this->set_default_property(self :: PROPERTY_RESULT_SCALE_ID, $result_scale_id);
    }

    public function get_deliberation()
    {
        return $this->get_default_property(self :: PROPERTY_DELIBERATION);
    }

    public function set_deliberation($deliberation)
    {
        $this->set_default_property(self :: PROPERTY_DELIBERATION, $deliberation);
    }

    public function get_level()
    {
        return $this->get_default_property(self :: PROPERTY_LEVEL);
    }

    public function set_level($level)
    {
        $this->set_default_property(self :: PROPERTY_LEVEL, $level);
    }

    public function get_kind()
    {
        return $this->get_default_property(self :: PROPERTY_KIND);
    }

    public function set_kind($kind)
    {
        $this->set_default_property(self :: PROPERTY_KIND, $kind);
    }

    public function get_goals()
    {
        return $this->get_default_property(self :: PROPERTY_GOALS);
    }

    public function set_goals($goals)
    {
        $this->set_default_property(self :: PROPERTY_GOALS, $goals);
    }

    public function get_contents()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENTS);
    }

    public function set_contents($contents)
    {
        $this->set_default_property(self :: PROPERTY_CONTENTS, $contents);
    }

    public function get_coaching()
    {
        return $this->get_default_property(self :: PROPERTY_COACHING);
    }

    public function set_coaching($coaching)
    {
        $this->set_default_property(self :: PROPERTY_COACHING, $coaching);
    }

    public function get_succession()
    {
        return $this->get_default_property(self :: PROPERTY_SUCCESSION);
    }

    public function set_succession($succession)
    {
        $this->set_default_property(self :: PROPERTY_SUCCESSION, $succession);
    }

    public function get_jury()
    {
        return $this->get_default_property(self :: PROPERTY_JURY);
    }

    public function set_jury($jury)
    {
        $this->set_default_property(self :: PROPERTY_JURY, $jury);
    }

    public function get_repleacable()
    {
        return $this->get_default_property(self :: PROPERTY_REPLEACABLE);
    }

    public function set_repleacable($repleacable)
    {
        $this->set_default_property(self :: PROPERTY_REPLEACABLE, $repleacable);
    }

    public function training_unit()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_UNIT);
    }

    public function set_training_unit($training_unit)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_UNIT, $training_unit);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
