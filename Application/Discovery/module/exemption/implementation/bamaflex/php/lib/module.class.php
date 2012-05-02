<?php
namespace application\discovery\module\exemption\implementation\bamaflex;

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
use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\exemption\Module
{
    private $cache_exemptions = array();

    function get_exemptions_data($year)
    {
        if (! isset($this->cache_exemptions[$year]))
        {
            $exemptions = array();
            foreach ($this->get_exemptions() as $exemption)
            {
                if ($exemption->get_year() == $year)
                {
                    $exemptions[] = $exemption;
                }
            }

            $this->cache_exemptions[$year] = $exemptions;
        }
        return $this->cache_exemptions[$year];
    }

    function has_exemptions($year)
    {
        return count($this->get_exemptions_data($year)) > 0;
    }

    function get_exemptions_table($year)
    {
        $exemptions = $this->get_exemptions_data($year);

        $data = array();

        foreach ($exemptions as $key => $exemption)
        {
            $row = array();
            $row[] = $exemption->get_credits();
            $row[] = $exemption->get_name();
            $row[] = $exemption->get_type();
            $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $exemption->get_date_requested());
//             if ($exemption->get_date_closed())
//             {
//                 $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $exemption->get_date_closed());
//             }
//             else
//             {
//                 $row[] = ' ';
//             }
            $row[] = $exemption->get_motivation();
            $row[] = $exemption->get_proof();
            $row[] = $exemption->get_remarks();

            $image = '<img src="' . Theme :: get_image_path() . 'state/' . $exemption->get_state() . '.png" alt="' . Translation :: get($exemption->get_state_string()) . '" title="' . Translation :: get($exemption->get_state_string()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($exemption->get_state_string()), Translation :: get('State'));

            $data[] = $row;
        }

        $table = new SortableTable($data);

        $table->set_header(0, Translation :: get('Credits'), false);
        $table->set_header(1, Translation :: get('Name'), false);
        $table->set_header(2, Translation :: get('Type'), false);
        $table->set_header(3, Translation :: get('DateRequested'), false);
//         $table->set_header(4, Translation :: get('DateClosed'), false);
        $table->set_header(4, Translation :: get('Motivation'), false);
        $table->set_header(5, Translation :: get('Proof'), false);
        $table->set_header(6, Translation :: get('Remarks'), false);
        $table->set_header(7, ' ', false);

        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\exemption\Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();

        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_exemption_parameters()))
        {
            Display :: not_allowed();
        }

        $html = array();

        if (count($this->get_exemptions()) > 0)
        {
            $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_exemption_parameters());

            $tabs = new DynamicTabsRenderer('exemption_list');

            foreach ($years as $year)
            {
                $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_exemptions_table($year)->toHTML()));
            }
            $html[] = $tabs->render();

        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>