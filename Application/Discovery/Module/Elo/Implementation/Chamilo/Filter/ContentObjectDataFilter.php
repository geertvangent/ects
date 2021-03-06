<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Filter;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type\ContentObjectData;
use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeDataFilter;

class ContentObjectDataFilter extends TypeDataFilter
{

    /*
     * (non-PHPdoc) @see \application\discovery\module\elo\implementation\chamilo\TypeDataFilter::format_filter_option()
     */
    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case ContentObjectData::PROPERTY_PLATFORM :
                return $value == 1 ? 'dokeos' : 'chamilo';
                break;
            case ContentObjectData::PROPERTY_OBJECT_TYPE :
                return Translation::get('TypeName', null, Utilities::get_namespace_classname($value));
                break;
        }
        return parent::format_filter_option($filter, $value);
    }

    public function get_filter_condition($module_type, $filter, $value)
    {
        return parent::get_filter_condition($module_type, $filter, $value);
    }
}
