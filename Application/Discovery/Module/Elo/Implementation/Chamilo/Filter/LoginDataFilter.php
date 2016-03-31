<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Filter;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type\ContentObjectData;
use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeDataFilter;

class LoginDataFilter extends TypeDataFilter
{

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
