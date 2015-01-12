<?php
namespace Chamilo\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Application\Discovery\SortableTable;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Breadcrumb;
use Chamilo\Libraries\Format\BreadcrumbTrail;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function get_student_years_table()
    {
        $student_years = $this->get_student_years();
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        foreach ($student_years as $key => $student_year)
        {
            $row = array();
            $row[] = $student_year->get_year();
            $row[] = Translation :: get($student_year->get_scholarship_string());
            $row[] = Translation :: get($student_year->get_reduced_registration_fee_string());
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Scholarship'), false);
        $table->set_header(2, Translation :: get('ReducedRegistrationFee'), false);
        return $table;
    }
    
    /*
     * (non-PHPdoc) @see application\discovery\module\enrollment.Module::render()
     */
    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));
        
        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        $html = array();
        if (count($this->get_student_years()) > 0)
        {
            $html[] = $this->get_student_years_table()->toHTML();
        }
        else
        
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition :: VIEW_DEFAULT;
    }
}
