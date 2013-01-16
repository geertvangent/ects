<?php
namespace application\discovery\module\advice\implementation\bamaflex;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use common\libraries\Display;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;
use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\advice\DataManager;

class Module extends \application\discovery\module\advice\Module
{

    private $cache_advices = array();

    function get_advices_data($enrollment)
    {
        if (! isset($this->cache_advices[$enrollment->get_id()]))
        {
            $advices = array();
            foreach ($this->get_advices() as $advice)
            {
                if ($advice->get_enrollment_id() == $enrollment->get_id())
                {
                    $advices[] = $advice;
                }
            }

            $this->cache_advices[$enrollment->get_id()] = $advices;
        }
        return $this->cache_advices[$enrollment->get_id()];
    }

    function has_advices($enrollment = null)
    {
        if ($enrollment)
        {
            return count($this->get_advices_data($enrollment));
        }
        else
        {
            return count($this->get_advices()) > 0;
        }
    }
}
?>