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
}
