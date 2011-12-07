<?php
namespace application\discovery\module\career\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.career.implementation.bamaflex.discovery
 * @author Hans De Bisschop
 */
class Mark extends \application\discovery\module\career\Mark
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SUB_STATUS = 'sub_status';
    const PROPERTY_PUBLISH_STATUS = 'publish_status';
    
    const STATUS_EXEMPTION = 1;
    const STATUS_CREDIT = 2;
    const STATUS_DELIBERATED = 3;
    const STATUS_TOLERATED = 4;
    const STATUS_RETRY = 5;
    const STATUS_RETAKE = 6;
    const STATUS_POSTPONED = 7;
    const STATUS_NO_CREDIT = 8;
    const STATUS_DETERMINED = 9;

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SUB_STATUS;
        $extended_property_names[] = self :: PROPERTY_PUBLISH_STATUS;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Returns the sub_status of this Mark.
     * @return string The sub_status.
     */
    function get_sub_status()
    {
        return $this->get_default_property(self :: PROPERTY_SUB_STATUS);
    }

    /**
     * Sets the sub_status of this Mark.
     * @param string $sub_status
     */
    function set_sub_status($sub_status)
    {
        $this->set_default_property(self :: PROPERTY_SUB_STATUS, $sub_status);
    }

    function get_publish_status()
    {
        return $this->get_default_property(self :: PROPERTY_PUBLISH_STATUS);
    }

    function set_publish_status($publish_status)
    {
        $this->set_default_property(self :: PROPERTY_PUBLISH_STATUS, $publish_status);
    }

    /**
     * @return string
     */
    function get_status_string()
    {
        return self :: status_string($this->get_status());
    }

    /**
     * @return string
     */
    static function status_string($status)
    {
        $prefix = 'MarkStatus';
        
        switch ($status)
        {
            case self :: STATUS_EXEMPTION :
                return $prefix . 'Exemption';
                break;
            case self :: STATUS_CREDIT :
                return $prefix . 'Credit';
                break;
            case self :: STATUS_DELIBERATED :
                return $prefix . 'Deliberated';
                break;
            case self :: STATUS_TOLERATED :
                return $prefix . 'Tolerated';
                break;
            case self :: STATUS_RETRY :
                return $prefix . 'Retry';
                break;
            case self :: STATUS_RETAKE :
                return $prefix . 'Retake';
                break;
            case self :: STATUS_POSTPONED :
                return $prefix . 'Postponed';
                break;
            case self :: STATUS_NO_CREDIT :
                return $prefix . 'NoCredit';
                break;
            case self :: STATUS_DETERMINED :
                return $prefix . 'Determined';
                break;
            default :
                return parent :: status_string($status);
                break;
        }
    }

    static function factory($moment_id = 0, $result = null, $status = null, $sub_status = null)
    {
        $mark = new self();
        $mark->set_moment($moment_id);
        $mark->set_result($result);
        $mark->set_status($status);
        $mark->set_sub_status($sub_status);
        return $mark;
    }

}
