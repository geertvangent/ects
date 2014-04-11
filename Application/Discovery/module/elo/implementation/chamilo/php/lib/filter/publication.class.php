<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\Translation;
use common\libraries\Utilities;

class PublicationDataFilter extends TypeDataFilter
{
    const CLASS_NAME = __CLASS__;

    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case PublicationData :: PROPERTY_PLATFORM :
                return $value == 1 ? 'dokeos' : 'chamilo';
                break;
            case PublicationData :: PROPERTY_OBJECT_TYPE :
                return Translation :: get('TypeName', null, Utilities :: get_namespace_from_classname($value));
                break;
        }
        return parent :: format_filter_option($filter, $value);
    }
}
