<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\Utilities;
use common\libraries\Translation;

class ContentObjectDataFilter extends TypeDataFilter
{
    const CLASS_NAME = __CLASS__;
    /*
     * (non-PHPdoc) @see \application\discovery\module\elo\implementation\chamilo\TypeDataFilter::format_filter_option()
     */
    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case ContentObjectData :: PROPERTY_PLATFORM :
                return $value;
                break;
            case ContentObjectData :: PROPERTY_OBJECT_TYPE :
                return Translation :: get('TypeName', null, Utilities :: get_namespace_from_classname($value));
                break;

        }
        return parent :: format_filter_option($filter, $value);
    }

    public function get_filter_condition($module_type, $filter, $value)
    {
        return parent :: get_filter_condition($module_type, $filter, $value);
    }
}
