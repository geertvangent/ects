<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Filter;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeDataFilter;
use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type\PublicationData;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

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
                return Translation :: get('TypeName', null, Utilities :: get_namespace_classname($value));
                break;
        }

        return parent :: format_filter_option($filter, $value);
    }
}
