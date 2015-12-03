<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Ehb\Application\Discovery\DiscoveryItem;

class SecondChance extends DiscoveryItem
{
    const PROPERTY_EXAM = 'exam';
    const PROPERTY_ENROLLMENT = 'enrollment';
    const PROPERTY_EXAM_PARTS = 'exam_parts';
    const EXAM_PARTS_ALL = 5;
    const EXAM_PARTS_FAILED = 6;
    const EXAM_PARTS_SPECIFIC = 10;

    public function get_exam()
    {
        return $this->get_default_property(self :: PROPERTY_EXAM);
    }

    public function set_exam($exam)
    {
        $this->set_default_property(self :: PROPERTY_EXAM, $exam);
    }

    public function get_enrollment()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT);
    }

    public function set_enrollment($enrollment)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT, $enrollment);
    }

    public function get_exam_parts()
    {
        return $this->get_default_property(self :: PROPERTY_EXAM_PARTS);
    }

    public function set_exam_parts($exam_parts)
    {
        $this->set_default_property(self :: PROPERTY_EXAM_PARTS, $exam_parts);
    }

    public function get_exam_parts_string()
    {
        return self :: exam_parts_string($this->get_exam_parts());
    }

    /**
     *
     * @return string
     */
    public static function exam_parts_string($exam_parts)
    {
        switch ($exam_parts)
        {
            case self :: EXAM_PARTS_ALL :
                return 'ExamPartsAll';
                break;
            case self :: EXAM_PARTS_FAILED :
                return 'ExamPartsFailed';
                break;
            case self :: EXAM_PARTS_SPECIFIC :
                return 'ExamPartsSpecific';
                break;
        }
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_EXAM;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT;
        $extended_property_names[] = self :: PROPERTY_EXAM_PARTS;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        $string[] = $this->get_exam();
        $string[] = $this->get_enrollment();
        return implode(' | ', $string);
    }
}
