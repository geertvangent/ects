<?php
namespace application\discovery\module\course\implementation\bamaflex;


use application\discovery\DiscoveryItem;

class FollowingImpossible extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_CREDIT = 'credit';
    const PROPERTY_EXAM_DEGREE = 'exam_degree';
    const PROPERTY_EXAM_CREDIT = 'exam_credit';

    public function get_credit()
    {
        return $this->get_default_property(self :: PROPERTY_CREDIT);
    }

    public function set_credit($credit)
    {
        $this->set_default_property(self :: PROPERTY_CREDIT, $credit);
    }

    public function get_exam_degree()
    {
        return $this->get_default_property(self :: PROPERTY_EXAM_DEGREE);
    }

    public function set_exam_degree($exam_degree)
    {
        $this->set_default_property(self :: PROPERTY_EXAM_DEGREE, $exam_degree);
    }

    public function get_exam_credit()
    {
        return $this->get_default_property(self :: PROPERTY_EXAM_CREDIT);
    }

    public function set_exam_credit($exam_credit)
    {
        $this->set_default_property(self :: PROPERTY_EXAM_CREDIT, $exam_credit);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_CREDIT;
        $extended_property_names[] = self :: PROPERTY_EXAM_DEGREE;
        $extended_property_names[] = self :: PROPERTY_EXAM_CREDIT;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
//         return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        $string[] = $this->get_type();
        $string[] = $this->get_price();
        return implode(' | ', $string);
    }
}
