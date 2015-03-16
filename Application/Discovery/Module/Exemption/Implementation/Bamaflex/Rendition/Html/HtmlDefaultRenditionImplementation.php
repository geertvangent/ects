<?php
namespace Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Enrollment\DataManager;
use Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    /*
     * (non-PHPdoc) @see application\discovery\module\exemption\Module::render()
     */
    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));

        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT,
            $this->get_module_instance()->get_id(),
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }

        $html = array();

        if (count($this->get_exemptions()) > 0)
        {
            $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years(
                $this->get_module_parameters());

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
        return implode(PHP_EOL, $html);
    }

    public function get_exemptions_table($year)
    {
        $exemptions = $this->get_exemptions_data($year);

        $data = array();

        foreach ($exemptions as $key => $exemption)
        {
            $row = array();
            $row[] = $exemption->get_credits();
            $row[] = $exemption->get_name();
            $row[] = $exemption->get_type();
            $row[] = DatetimeUtilities :: format_locale_date(
                Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                $exemption->get_date_requested());
            $row[] = $exemption->get_motivation();
            $row[] = $exemption->get_proof();
            $row[] = $exemption->get_remarks();

            $image = '<img src="' . Theme :: getInstance()->getImagesPath() . 'State/' . $exemption->get_state() .
                 '.png" alt="' . Translation :: get($exemption->get_state_string()) . '" title="' .
                 Translation :: get($exemption->get_state_string()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image,
                Translation :: get($exemption->get_state_string()),
                Translation :: get('State'));

            $data[] = $row;
        }

        $table = new SortableTable($data);

        $table->set_header(0, Translation :: get('Credits'), false);
        $table->set_header(1, Translation :: get('Name'), false);
        $table->set_header(2, Translation :: get('Type'), false);
        $table->set_header(3, Translation :: get('DateRequested'), false);
        $table->set_header(4, Translation :: get('Motivation'), false);
        $table->set_header(5, Translation :: get('Proof'), false);
        $table->set_header(6, Translation :: get('Remarks'), false);
        $table->set_header(7, ' ', false);

        return $table;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
