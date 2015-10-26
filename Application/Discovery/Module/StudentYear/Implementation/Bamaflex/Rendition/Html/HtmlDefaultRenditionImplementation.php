<?php
namespace Ehb\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;

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
        $table->setColumnHeader(0, Translation :: get('Year'), false);
        $table->setColumnHeader(1, Translation :: get('Scholarship'), false);
        $table->setColumnHeader(2, Translation :: get('ReducedRegistrationFee'), false);
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
            throw new NotAllowedException(false);
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
        return implode(PHP_EOL, $html);
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
