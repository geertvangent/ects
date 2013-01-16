<?php
namespace application\discovery\module\cas\implementation\doctrine;

use application\discovery\module\cas\Parameters;
use application\discovery\SortableTable;
use common\libraries\Filesystem;
use common\libraries\Path;
use common\libraries\Theme;
use common\libraries\Display;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Translation;

// use application\discovery\SortableTable;
use application\discovery\module\cas\DataManager;

class Module extends \application\discovery\module\cas\Module
{

    private $action_statistics;

    function get_action_statistics($action)
    {
        if (! isset($this->action_statistics))
        {
            $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/cas_action_statistics/' . md5(
                    serialize($this->get_module_parameters()));

            if (! file_exists($path))
            {
                $this->action_statistics = array();

                foreach ($this->get_cas_statistics() as $cas_statistic)
                {
                    if ($cas_statistic->get_application_id())
                    {
                        $this->action_statistics[$cas_statistic->get_action_id()][$cas_statistic->get_application_id()][] = $cas_statistic;
                    }
                    else
                    {
                        $this->action_statistics[$cas_statistic->get_action_id()][0][] = $cas_statistic;
                    }
                }
                Filesystem :: write_to_file($path, serialize($this->action_statistics));
            }
            else
            {
                $this->action_statistics = unserialize(file_get_contents($path));
            }
        }

        return $this->action_statistics[$action->get_id()];
    }
}
?>