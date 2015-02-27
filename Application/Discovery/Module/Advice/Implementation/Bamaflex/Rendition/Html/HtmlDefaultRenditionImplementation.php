<?php
namespace Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Advice;
use Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\Module\Enrollment\DataManager;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
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

        if ($this->has_advices())
        {

            $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments(
                $this->get_module_parameters());

            foreach ($enrollments as $enrollment)
            {
                if ($this->has_advices($enrollment))
                {
                    $html[] = '<table class="data_table" id="tablename"><thead><tr><th class="action">';

                    $enrollment_namespace = Utilities :: get_namespace_classname(Enrollment :: CLASS_NAME);

                    if ($enrollment->is_special_result())
                    {
                        $tab_image_path = Theme :: getInstance()->getImagePath($enrollment_namespace) . 'ResultType/' .
                             $enrollment->get_result() . '.png';
                        $tab_image = '<img src="' . $tab_image_path . '" alt="' .
                             Translation :: get($enrollment->get_result_string(), null, $enrollment_namespace) .
                             '" title="' .
                             Translation :: get($enrollment->get_result_string(), null, $enrollment_namespace) . '" />';
                        $html[] = $tab_image;
                        LegendTable :: get_instance()->add_symbol(
                            $tab_image,
                            Translation :: get($enrollment->get_result_string(), null, $enrollment_namespace),
                            Translation :: get('ResultType'),
                            null,
                            $enrollment_namespace);
                    }

                    $html[] = '</th><th class="action">';
                    $tab_image_path = Theme :: getInstance()->getImagePath($enrollment_namespace) . 'ContractType/' .
                         $enrollment->get_contract_type() . '.png';
                    $tab_image = '<img src="' . $tab_image_path . '" alt="' .
                         Translation :: get($enrollment->get_contract_type_string(), null, $enrollment_namespace) .
                         '" title="' .
                         Translation :: get($enrollment->get_contract_type_string(), null, $enrollment_namespace) .
                         '" />';
                    $html[] = $tab_image;
                    LegendTable :: get_instance()->add_symbol(
                        $tab_image,
                        Translation :: get($enrollment->get_contract_type_string(), null, $enrollment_namespace),
                        Translation :: get('ContractType'),
                        null,
                        $enrollment_namespace);

                    $html[] = '</th><th>';

                    $enrollment_name = array();

                    $enrollment_name[] = $enrollment->get_year();
                    $enrollment_name[] = $enrollment->get_training();

                    if ($enrollment->get_unified_option())
                    {
                        $enrollment_name[] = $enrollment->get_unified_option();
                    }

                    if ($enrollment->get_unified_trajectory())
                    {
                        $enrollment_name[] = $enrollment->get_unified_trajectory();
                    }

                    $html[] = implode(' | ', $enrollment_name);
                    $html[] = '</th></tr></thead></table>';
                    $html[] = '<br />';
                    $html[] = $this->get_advices_table($enrollment)->toHTML();
                    $html[] = '<br />';
                }
            }
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode(PHP_EOL, $html);
    }

    public function get_advices_table($enrollment)
    {
        $advices = $this->get_advices_data($enrollment);

        $data = array();

        foreach ($advices as $key => $advice)
        {

            if ($advice->get_motivation())
            {
                $row = array();

                $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' . Advice :: TYPE_MOTIVATION .
                     '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)) . '" title="' .
                     Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)),
                    Translation :: get('Type'));

                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $advice->get_date());
                $row[] = $advice->get_decision_type();

                $row[] = $advice->get_motivation();

                $data[] = $row;
            }

            if ($advice->get_ombudsman())
            {
                $row = array();

                $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' . Advice :: TYPE_OMBUDSMAN .
                     '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)) . '" title="' .
                     Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)),
                    Translation :: get('Type'));

                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_ombudsman();

                $data[] = $row;
            }

            if ($advice->get_vote())
            {
                $row = array();

                $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' . Advice :: TYPE_VOTE .
                     '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)) . '" title="' .
                     Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)),
                    Translation :: get('Type'));

                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_vote();

                $data[] = $row;
            }

            if ($advice->get_measures() /*&& ($advice->get_measures_visible())*/)
            {
                $row = array();
                if ($advice->get_measures_valid())
                {
                    $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' .
                         Advice :: TYPE_MEASURES_VALID . '.png" alt="' .
                         Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)) . '" title="' .
                         Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)) . '"/>';
                    $row[] = $image;
                    LegendTable :: get_instance()->add_symbol(
                        $image,
                        Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)),
                        Translation :: get('Type'));
                }
                else
                {
                    $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' .
                         Advice :: TYPE_MEASURES_INVALID . '.png" alt="' .
                         Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)) . '" title="' .
                         Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)) . '"/>';
                    $row[] = $image;
                    LegendTable :: get_instance()->add_symbol(
                        $image,
                        Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)),
                        Translation :: get('Type'));
                }
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_measures();

                $data[] = $row;
            }

            if ($advice->get_advice()/* && ($advice->get_advice_visible())*/)
            {
                $row = array();

                $image = '<img src="' . Theme :: getInstance()->getImagePath() . 'Type/' . Advice :: TYPE_ADVICE .
                     '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)) . '" title="' .
                     Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)),
                    Translation :: get('Type'));

                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES),
                    $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_advice();

                $data[] = $row;
            }
        }

        $table = new SortableTable($data);

        $table->set_header(0, Translation :: get('Type'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');

        $table->set_header(1, Translation :: get('Try'), false);
        $table->set_header(2, Translation :: get('Date'), false);

        $table->set_header(3, Translation :: get('DecisionType'), false);
        $table->set_header(4, Translation :: get('Advice'), false);

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
