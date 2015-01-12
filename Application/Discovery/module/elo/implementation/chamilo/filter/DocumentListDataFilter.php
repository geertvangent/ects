<?php
namespace Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\Filter;

use Chamilo\Libraries\Platform\Translation\Translation;

class DocumentListDataFilter extends TypeDataFilter
{
    const CLASS_NAME = __CLASS__;
    const SIZE_ANY = 0;
    const SIZE_1_MB = 1;
    const SIZE_10_MB = 2;
    const SIZE_100_MB = 3;
    const SIZE_1000_MB = 4;

    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case DocumentListData :: PROPERTY_SIZE :
                return;
                break;
        }
        return parent :: format_filter_option($filter, $value);
    }

    public function get_filter_condition($module_type, $filter, $value)
    {
        return parent :: get_filter_condition($module_type, $filter, $value);
    }

    public function get_options($filter)
    {
        switch ($filter)
        {
            case DocumentListData :: PROPERTY_SIZE :
                return $this->get_size_options();
                break;
        }
        
        return parent :: get_options($filter);
    }

    public function get_size_options()
    {
        return array(
            self :: SIZE_ANY => Translation :: get('AnySize'), 
            self :: SIZE_1_MB => Translation :: get('Size1Mb'), 
            self :: SIZE_10_MB => Translation :: get('Size10Mb'), 
            self :: SIZE_100_MB => Translation :: get('Size100Mb'), 
            self :: SIZE_1000_MB => Translation :: get('Size1000Mb'));
    }
}
