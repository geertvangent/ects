<?php
namespace Application\Discovery\module\faculty\rendition\view\html;

use libraries\format\DynamicVisualTab;
use libraries\format\DynamicVisualTabsRenderer;

class HtmlDefaultRendition extends HtmlRendition
{
    /*
     * (non-PHPdoc) @see application\discovery\module\faculty\Module::render()
     */
    public function render()
    {
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
            'faculty_list', 
            $this->get_rendition_implementation()->get_faculties_table($current_year)->as_html());
        
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
        
        return implode("\n", $html);
    }
}
