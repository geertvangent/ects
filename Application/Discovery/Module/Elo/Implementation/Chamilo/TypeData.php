<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo;

use Chamilo\Libraries\Storage\DataClass\DataClass;

class TypeData extends DataClass
{
    const PROPERTY_DATE = 'date';

    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    public static function get_filters($filters = array())
    {
        $filters[] = self :: PROPERTY_DATE;
        
        return $filters;
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DATE;
        
        return parent :: get_default_property_names($extended_property_names);
    }
}