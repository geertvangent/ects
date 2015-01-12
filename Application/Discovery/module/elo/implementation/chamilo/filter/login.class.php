<?php
namespace Application\Discovery\module\elo\implementation\chamilo\filter;

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
