<?php
namespace Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\Filter;

use Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeDataFilter;
use Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\Type\ContentObjectData;

class LoginDataFilter extends TypeDataFilter
{
    const CLASS_NAME = __CLASS__;

    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case ContentObjectData :: PROPERTY_PLATFORM :
                return $value == 1 ? 'dokeos' : 'chamilo';
                break;
        }
        return parent :: format_filter_option($filter, $value);
    }
}
