<?php
namespace application\discovery\module\course\implementation\bamaflex;

use common\libraries\DatetimeUtilities;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class TimeframePart extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_TIMEFRAME_ID = 'timeframe_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_DATE = 'date';

    function get_timeframe_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_ID);
    }

    function set_timeframe_id($timeframe_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_ID;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_ID;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_DATE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * @return string
     */
    function __toString()
    {
        return DatetimeUtilities::format_locale_date('%b %d, %Y', strtotime($this->get_date()));
    }
}
?>