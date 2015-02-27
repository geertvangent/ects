<?php
namespace Ehb\Application\Discovery\Module\Employment\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Employment\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Employment\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\Utilities;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

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
        if (count($this->get_employments()) > 0)
        {
            $html[] = $this->get_table()->toHTML();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode(PHP_EOL, $html);
    }

    public function get_table()
    {
        $data = array();
        $has_interruption = false;

        foreach ($this->get_employments() as $employment)
        {

            $parts = $this->get_employment_parts($employment->get_id());
            $unique_faculty = $this->get_unique_faculty($parts);
            $unique_department = $this->get_unique_department($parts);

            $row = array();
            $row[] = $employment->get_year();
            if (count($parts) == 1)
            {
                $row[] = $parts[0]->get_faculty();
            }
            elseif (! is_array($unique_faculty))
            {
                $row[] = $unique_faculty;
            }
            else
            {
                $row[] = implode(', ', $unique_faculty);
            }

            if (count($parts) == 1)

            {
                $row[] = $parts[0]->get_department();
            }
            elseif (! is_array($unique_department))

            {
                $row[] = $unique_department;
            }
            else

            {
                $row[] = implode(', ', $unique_department);
            }

            $row[] = $employment->get_assignment() . '%';

            if ($employment->get_end_date())
            {
                $end_date = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $employment->get_end_date());
            }
            else
            {
                $end_date = '';
            }
            $start_date = DatetimeUtilities :: format_locale_date(
                Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                $employment->get_start_date());

            $row[] = $start_date;
            $row[] = $end_date;
            $row[] = $employment->get_office_category();
            $row[] = $employment->get_state();

            if ($employment->get_fund_id())
            {
                $image = '<img src="' . Theme :: getInstance()->getImagesPath(
                    'Ehb/Application/Discovery/Module/Employment/Implementation/Bamaflex') . 'Fund/' .
                     $employment->get_fund_id() . '.png" alt="' . Translation :: get($employment->get_fund_string()) .
                     '" title="' . Translation :: get($employment->get_fund_string()) . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get($employment->get_fund_string()),
                    Translation :: get('Fund'));
            }
            else
            {
                $image = '<img src="' . Theme :: getInstance()->getImagesPath() . 'Fund/0.png" alt="' .
                     Translation :: get('UnknownFund') . '" title="' . Translation :: get('UnknownFund') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get('UnknownFund'),
                    Translation :: get('Fund'));
            }
            $row[] = $employment->get_pay_scale();

            $image = '<img src="' . Theme :: getInstance()->getImagesPath(
                'Ehb/Application/Discovery/Module/Employment/Implementation/Bamaflex') . 'Active/' .
                 $employment->get_active() . '.png" alt="' . Translation :: get($employment->get_active_string()) .
                 '" title="' . Translation :: get($employment->get_active_string()) . '" />';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image,
                Translation :: get($employment->get_active_string()),
                Translation :: get('Active'));

            if ($employment->get_interruption())
            {
                $has_interruption = true;
                $row[] = $employment->get_interruption();
            }

            $data[] = $row;
            if (count($parts) > 1)
            {
                foreach ($parts as $part)
                {
                    $row = array();

                    $row[] = ' ';
                    if (is_array($unique_faculty))
                    {
                        $row[] = '<span class="employment_part">' . $part->get_faculty() . '</span>';
                    }
                    else
                    {
                        $row[] = ' ';
                    }

                    if (is_array($unique_department))
                    {
                        $row[] = '<span class="employment_part">' . $part->get_department() . '</span>';
                    }
                    else
                    {
                        $row[] = ' ';
                    }

                    $row[] = '<span class="employment_part">' . $part->get_volume() . '%' . '</span>';
                    $row[] = '<span class="employment_part">' . DatetimeUtilities :: format_locale_date(
                        Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                        $part->get_start_date()) . '</span>';

                    if ($part->get_end_date())
                    {
                        $row[] = '<span class="employment_part">' . DatetimeUtilities :: format_locale_date(
                            Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                            $part->get_end_date()) . '</span>';
                    }
                    else
                    {
                        $row[] = '';
                    }
                    $data[] = $row;
                }
            }
        }

        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Department'), false);
        $table->set_header(3, Translation :: get('Assignment'), false);
        $table->set_header(4, Translation :: get('StartDate'), false);
        $table->set_header(5, Translation :: get('EndDate'), false);
        $table->set_header(6, Translation :: get('Category'), false);
        $table->set_header(7, Translation :: get('State'), false);
        $table->set_header(8, Translation :: get('Fund'), false);
        $table->set_header(9, Translation :: get('PayScale'), false);
        $table->set_header(10, '', false);

        if ($has_interruption)
        {
            $table->set_header(11, Translation :: get('Interruption'), false);
        }

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
