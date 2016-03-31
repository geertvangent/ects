<?php
namespace Ehb\Application\Discovery\Module\Training\Rendition\View\Html;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Rendition\Format\HtmlRendition;

class HtmlDefaultRendition extends HtmlRendition
{

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(
            new Breadcrumb(
                null,
                Translation :: get('TypeName', null, ClassnameUtilities :: getInstance()->getNamespaceFromObject($this))));
        $html = array();
        if (is_null($this->get_rendition_implementation()->module_parameters()->get_year()))
        {
            $years = $this->get_rendition_implementation()->get_years();
            $current_year = $years[0];
        }
        else
        {
            $current_year = $this->get_rendition_implementation()->module_parameters()->get_year();
        }
        $tabs = new DynamicVisualTabsRenderer(
            'training_list',
            $this->get_rendition_implementation()->get_trainings_table($current_year)->as_html());

        foreach ($this->get_rendition_implementation()->get_years() as $year)
        {

            $parameters = $this->get_rendition_implementation()->module_parameters();
            $parameters->set_year($year);
            $tabs->add_tab(
                new DynamicVisualTab(
                    $year,
                    $year,
                    null,
                    $this->get_rendition_implementation()->get_instance_url(
                        $this->get_rendition_implementation()->get_module_instance()->get_id(),
                        $parameters),
                    $current_year == $year));
        }
        $html[] = $tabs->render();

        return implode(PHP_EOL, $html);
    }
}
