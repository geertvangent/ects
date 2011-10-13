<?php
namespace application\discovery\module\course\implementation\bamaflex;

use common\libraries\PropertiesTable;

use common\libraries\StringUtilities;

use common\libraries\Request;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\course\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\course\Module
{
    const PARAM_SOURCE = 'source';
    
    const TAB_GENERAL = 1;
    const TAB_MATERIALS = 2;
    const TAB_ACTIVITIES = 3;
    const TAB_COMPETENCES = 4;
    const TAB_CONTENT = 5;
    const TAB_EVALUATIONS = 6;

    function get_course_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_PROGRAMME_ID), Request :: get(self :: PARAM_SOURCE));
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\course.Module::render()
     */
    function render()
    {
        $html = array();
        $course = $this->get_course();
        
        $html[] = '<h3>' . $course->get_name() . '</h3>';
        
        $tabs = new DynamicTabsRenderer('course');
        $tabs->add_tab(new DynamicContentTab(self :: TAB_GENERAL, Translation :: get('General'), Theme :: get_image_path() . 'tabs/' . self :: TAB_GENERAL . '.png', $this->get_general()));
        $tabs->add_tab(new DynamicContentTab(self :: TAB_MATERIALS, Translation :: get('Materials'), Theme :: get_image_path() . 'tabs/' . self :: TAB_MATERIALS . '.png', $this->get_materials()));
        $tabs->add_tab(new DynamicContentTab(self :: TAB_ACTIVITIES, Translation :: get('Activities'), Theme :: get_image_path() . 'tabs/' . self :: TAB_ACTIVITIES . '.png', $this->get_activities()));
        $tabs->add_tab(new DynamicContentTab(self :: TAB_COMPETENCES, Translation :: get('Competences'), Theme :: get_image_path() . 'tabs/' . self :: TAB_COMPETENCES . '.png', $this->get_competences()));
        $tabs->add_tab(new DynamicContentTab(self :: TAB_CONTENT, Translation :: get('Content'), Theme :: get_image_path() . 'tabs/' . self :: TAB_CONTENT . '.png', $this->get_content()));
        $tabs->add_tab(new DynamicContentTab(self :: TAB_EVALUATIONS, Translation :: get('Evaluations'), Theme :: get_image_path() . 'tabs/' . self :: TAB_EVALUATIONS . '.png', $this->get_evaluations()));
        
        $html[] = $tabs->render();
        return implode("\n", $html);
    }

    function get_general()
    {
        $course = $this->get_course();
        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $course->get_year();
        $properties[Translation :: get('Training')] = $course->get_training();
        $properties[Translation :: get('Faculty')] = $course->get_faculty();
        $properties[Translation :: get('Credits')] = $course->get_credits();
        $properties[Translation :: get('Weight')] = $course->get_weight();
        $properties[Translation :: get('TrajectoryPart')] = $course->get_trajectory_part();
        $properties[Translation :: get('ProgrammeType')] = Translation :: get($course->get_programme_type_string());
        
        $image = '<img src="' . Theme :: get_image_path() . 'general/timeframe/' . $course->get_timeframe_visual_id() . '.png" alt="' . Translation :: get($course->get_timeframe()) . '" title="' . Translation :: get($course->get_timeframe()) . '"/>';
        $properties[Translation :: get('Timeframe')] = $image;
        LegendTable :: get_instance()->add_symbol($image, Translation :: get($course->get_timeframe()), Translation :: get('Timeframe'));
        
        $properties[Translation :: get('TimeframeParts')] = $course->get_timeframe_parts_string();
        
        $properties[Translation :: get('Level')] = $course->get_level();
        $properties[Translation :: get('Kind')] = $course->get_kind();
        
        $properties[Translation :: get('Languages')] = $course->get_languages_string();
        $properties[Translation :: get('Teachers')] = $course->get_teachers_string();
        
        foreach ($course->get_costs() as $cost)
        {
            $properties[Translation :: get($cost->get_type_string())] = $cost->get_price();
        }
        
        $images = array();
        $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/degree.png" alt="' . Translation :: get('DegreePossible') . '" title="' . Translation :: get('DegreePossible') . '"/>';
        LegendTable :: get_instance()->add_symbol($image, Translation :: get('DegreePossible'), Translation :: get('FollowingPossible'));
        $images[] = $image;
        
        if ($course->get_following_impossible()->get_credit())
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/credit_impossible.png" alt="' . Translation :: get('CreditImpossible') . '" title="' . Translation :: get('CreditImpossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('CreditImpossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        else
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/credit.png" alt="' . Translation :: get('CreditPossible') . '" title="' . Translation :: get('CreditPossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('CreditPossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        
        if ($course->get_following_impossible()->get_exam_degree())
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_degree_impossible.png" alt="' . Translation :: get('ExamDegreeImpossible') . '" title="' . Translation :: get('ExamDegreeImpossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamDegreeImpossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        else
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_degree.png" alt="' . Translation :: get('ExamDegreePossible') . '" title="' . Translation :: get('ExamDegreePossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamDegreePossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        
        if ($course->get_following_impossible()->get_exam_credit())
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_credit_impossible.png" alt="' . Translation :: get('ExamCreditImpossible') . '" title="' . Translation :: get('ExamCreditImpossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamCreditImpossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        else
        {
            $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_credit.png" alt="' . Translation :: get('ExamCreditPossible') . '" title="' . Translation :: get('ExamCreditPossible') . '"/>';
            LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamCreditPossible'), Translation :: get('FollowingPossible'));
            $images[] = $image;
        }
        
        $properties[Translation :: get('FollowingPossible')] = implode(' ', $images);
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode("\n", $html);
    }

    function get_materials()
    {
        $html = array();
        $course = $this->get_course();
        
        $html[] = $this->get_materials_by_type(Material :: TYPE_REQUIRED);
        if (count($course->get_materials_by_type(Material :: TYPE_REQUIRED)) > 0 && count($course->get_materials_by_type(Material :: TYPE_OPTIONAL)) > 0)
        {
            $html[] = '<br/>';
        }
        $html[] = $this->get_materials_by_type(Material :: TYPE_OPTIONAL);
        return implode("\n", $html);
    }

    function get_materials_by_type($type)
    {
        $course = $this->get_course();
        
        $html = array();
        $table_data = array();
        
        if (count($course->get_materials_by_type($type)) > 0)
        {
            $var = ($type == Material :: TYPE_REQUIRED ? 'Required' : 'Optional');
            $html[] = '<h3>' . Translation :: get($var) . '</h3>';
        }
        foreach ($course->get_materials_by_type($type) as $material)
        {
            if ($material instanceof MaterialDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $material->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $material->get_group();
                $table_row[] = $material->get_title();
                $table_row[] = $material->get_edition();
                $table_row[] = $material->get_author();
                $table_row[] = $material->get_editor();
                $table_row[] = $material->get_isbn();
                $table_row[] = $material->get_medium();
                if ($material->get_price())
                {
                    $table_row[] = $material->get_price();
                }
                else
                {
                    $table_row[] = '';
                }
                if ($material->get_for_sale())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/for_sale.png" alt="' . Translation :: get('IsForSale') . '" title="' . Translation :: get('IsForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsForSale'), Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/not_for_sale.png" alt="' . Translation :: get('IsNotForSale') . '" title="' . Translation :: get('IsNotForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotForSale'), Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                
                $table_row[] = $material->get_description();
                $table_data[] = $table_row;
            }
        }
        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Group'), false);
            $table->set_header(1, Translation :: get('Title'), false);
            $table->set_header(2, Translation :: get('Edition'), false);
            $table->set_header(3, Translation :: get('Author'), false);
            $table->set_header(4, Translation :: get('Editor'), false);
            $table->set_header(5, Translation :: get('Isbn'), false);
            $table->set_header(6, Translation :: get('Medium'), false);
            $table->set_header(7, Translation :: get('Price'), false);
            $table->set_header(8, '', false);
            $table->set_header(9, Translation :: get('Remarks'), false);
            $html[] = $table->as_html();
        }
        
        return implode("\n", $html);
    }

    function get_activities()
    {
        $course = $this->get_course();
    }

    function get_competences()
    {
        $course = $this->get_course();
    }

    function get_content()
    {
        $course = $this->get_course();
        $html = array();
        
        if (! StringUtilities :: is_null_or_empty($course->get_goals(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'content/goals.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Goals');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_goals();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        
        if (! StringUtilities :: is_null_or_empty($course->get_contents(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'content/contents.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Contents');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_contents();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities :: is_null_or_empty($course->get_coaching(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'content/coaching.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Coaching');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_coaching();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities :: is_null_or_empty($course->get_succession(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'content/succession.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Succession');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_succession();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        return implode("\n", $html);
    }

    function get_evaluations()
    {
        $course = $this->get_course();
    }
}
?>