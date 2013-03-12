<?php
namespace application\discovery\module\training;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;

class HtmlDefaultRendition extends HtmlRendition
{

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(
                new Breadcrumb(null, Translation :: get('TypeName', null, Utilities :: get_namespace_from_object($this))));
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
        $tabs = new DynamicVisualTabsRenderer('training_list', 
                $this->get_rendition_implementation()->get_trainings_table($current_year)->as_html());
        
        foreach ($this->get_rendition_implementation()->get_years() as $year)
        {
            
            $parameters = $this->get_rendition_implementation()->module_parameters();
            $parameters->set_year($year);
            $tabs->add_tab(
                    new DynamicVisualTab($year, $year, null, 
                            $this->get_rendition_implementation()->get_instance_url(
                                    $this->get_rendition_implementation()->get_module_instance()->get_id(), $parameters), 
                            $current_year == $year));
        }
        $html[] = $tabs->render();
        
        return implode("\n", $html);
    }
}
