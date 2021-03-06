<?php
namespace Ehb\Application\Discovery\Module\CourseResults;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.course_results.discovery
 * 
 * @author Hans De Bisschop
 */
class CourseResult extends DataClass
{
    
    /**
     * Course properties
     */
    const PROPERTY_MARKS = 'marks';
    const PROPERTY_PERSON_FIRST_NAME = 'person_first_name';
    const PROPERTY_PERSON_LAST_NAME = 'person_last_name';
    const PROPERTY_PERSON_ID = 'person_id';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_MARKS;
        $extended_property_names[] = self::PROPERTY_PERSON_FIRST_NAME;
        $extended_property_names[] = self::PROPERTY_PERSON_LAST_NAME;
        $extended_property_names[] = self::PROPERTY_PERSON_ID;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     * Returns the marks of this Course.
     * 
     * @return multitype:Mark The marks.
     */
    public function get_marks()
    {
        return $this->get_default_property(self::PROPERTY_MARKS);
    }

    /**
     * Returns the person_first_name of this CourseResults.
     * 
     * @return string
     */
    public function get_person_first_name()
    {
        return $this->get_default_property(self::PROPERTY_PERSON_FIRST_NAME);
    }

    /**
     * Returns the person_last_name of this CourseResults.
     * 
     * @return string
     */
    public function get_person_last_name()
    {
        return $this->get_default_property(self::PROPERTY_PERSON_LAST_NAME);
    }

    public function get_person_id()
    {
        return $this->get_default_property(self::PROPERTY_PERSON_ID);
    }

    public function get_mark_by_moment_id($moment_id)
    {
        foreach ($this->get_marks() as $mark)
        {
            if ($mark->get_moment() == $moment_id)
            {
                return $mark;
            }
        }
        
        return self::factory($moment_id);
    }

    /**
     * Sets the marks of this Course.
     * 
     * @param multitype:Mark $marks
     */
    public function set_marks($marks)
    {
        $this->set_default_property(self::PROPERTY_MARKS, $marks);
    }

    /**
     * Sets the person_last_name of this CourseResults.
     * 
     * @param string
     */
    public function set_person_last_name($person_last_name)
    {
        $this->set_default_property(self::PROPERTY_PERSON_LAST_NAME, $person_last_name);
    }

    /**
     * Sets the person_first_name of this CourseResults.
     * 
     * @param string
     */
    public function set_person_first_name($person_first_name)
    {
        $this->set_default_property(self::PROPERTY_PERSON_FIRST_NAME, $person_first_name);
    }

    public function set_person_id($person_id)
    {
        $this->set_default_property(self::PROPERTY_PERSON_ID, $person_id);
    }

    /**
     *
     * @param Mark $mark
     */
    public function add_mark(Mark $mark)
    {
        $marks = $this->get_marks();
        $marks[] = $mark;
        $this->set_marks($marks);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities::get_classname_from_namespace(self::class_name(), true);
    }
}
