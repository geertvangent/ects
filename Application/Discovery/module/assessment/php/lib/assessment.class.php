<?php
namespace application\discovery\module\assessment;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.assessment.discovery
 * @author Hans De Bisschop
 */
class Assessment extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Assessment properties
     */
    const PROPERTY_DATE = 'date';
    const PROPERTY_PUBLISHER = 'publisher';
    const PROPERTY_RESULT = 'result';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_PUBLISHER;
        $extended_property_names[] = self :: PROPERTY_RESULT;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the date of this Assessment.
     * @return int The date.
     */
    function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     * Sets the date of this Assessment.
     * @param int $date
     */
    function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }
    /**
     * Returns the publisher of this Assessment.
     * @return string The publisher.
     */
    function get_publisher()
    {
        return $this->get_default_property(self :: PROPERTY_PUBLISHER);
    }

    /**
     * Sets the publisher of this Assessment.
     * @param string $publisher
     */
    function set_publisher($publisher)
    {
        $this->set_default_property(self :: PROPERTY_PUBLISHER, $publisher);
    }
    /**
     * Returns the result of this Assessment.
     * @return string The result.
     */
    function get_result()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT);
    }

    /**
     * Sets the result of this Assessment.
     * @param string $result
     */
    function set_result($result)
    {
        $this->set_default_property(self :: PROPERTY_RESULT, $result);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
