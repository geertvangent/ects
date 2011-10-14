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
        if (count($course->get_materials()) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_MATERIALS, Translation :: get('Materials'), Theme :: get_image_path() . 'tabs/' . self :: TAB_MATERIALS . '.png', $this->get_materials()));
        }
        if (count($course->get_activities()) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_ACTIVITIES, Translation :: get('Activities'), Theme :: get_image_path() . 'tabs/' . self :: TAB_ACTIVITIES . '.png', $this->get_activities()));
        }
        if (count($course->get_competences()) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_COMPETENCES, Translation :: get('Competences'), Theme :: get_image_path() . 'tabs/' . self :: TAB_COMPETENCES . '.png', $this->get_competences()));
        }
        if ($course->has_content())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_CONTENT, Translation :: get('Content'), Theme :: get_image_path() . 'tabs/' . self :: TAB_CONTENT . '.png', $this->get_content()));
        }
        if (count($course->get_evaluations()) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_EVALUATIONS, Translation :: get('Evaluations'), Theme :: get_image_path() . 'tabs/' . self :: TAB_EVALUATIONS . '.png', $this->get_evaluations()));
        }
        
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
        
        if ($course->has_coordinators())
        {
            $properties[Translation :: get('Coordinators')] = $course->get_coordinators_string();
        }
        
        if ($course->has_teachers())
        {
            $properties[Translation :: get('Teachers')] = $course->get_teachers_string();
        }
        
        foreach ($course->get_costs() as $cost)
        {
            $properties[Translation :: get($cost->get_type_string())] = $cost->get_price_string();
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
                $table_row[] = $material->get_description();
                if ($material->get_price())
                {
                    $table_row[] = $material->get_price_string();
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
                
                $table_data[] = $table_row;
            }
        }
        
        $cost = $course->get_costs_by_type(Cost :: TYPE_MATERIAL);
        
        if (count($table_data) > 0 || ($cost && $type == Material :: TYPE_REQUIRED && $cost->get_price()))
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Group'), false);
            $table->set_header(1, Translation :: get('Title'), false);
            $table->set_header(2, Translation :: get('Edition'), false);
            $table->set_header(3, Translation :: get('Author'), false);
            $table->set_header(4, Translation :: get('Editor'), false);
            $table->set_header(5, Translation :: get('Isbn'), false);
            $table->set_header(6, Translation :: get('Medium'), false);
            $table->set_header(7, Translation :: get('Remarks'), false);
            $table->set_header(8, Translation :: get('Price'), false);
            $table->set_header(9, '', false);
            
            $html[] = $table->as_html($cost->get_price_string(), 8);
        }
        
        return implode("\n", $html);
    }

    function get_activities()
    {
        $course = $this->get_course();
        
        $html = array();
        $table_data = array();
        
        foreach ($course->get_activities() as $activity)
        {
            if ($activity instanceof ActivityDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $activity->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            elseif ($activity instanceof ActivityStructured)
            {
                
                $table_row = array();
                $table_row[] = $activity->get_group();
                $table_row[] = $activity->get_name();
                $table_row[] = Translation :: get('ActivityTime', array('TIME' => $activity->get_time()));
                //                $table_row[] = $activity->get_remarks();
                //                $table_row[] = $activity->get_description();
                

                $table_data[] = $table_row;
            }
        }
        $total = $course->get_activities_by_type(ActivityTotal :: CLASS_NAME);
        
        if (count($table_data) > 0 || $total)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Group'), false);
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Time'), false);
            //            $table->set_header(3, Translation :: get('Remarks'), false);
            //            $table->set_header(4, Translation :: get('Description'), false);
            

            $total = $course->get_activities_by_type(ActivityTotal :: CLASS_NAME);
            if ($total)
            {
                $html[] = $table->as_html(Translation :: get('ActivityTime', array('TIME' => $total->get_time())), 2);
            }
            else
            {
                $html[] = $table->as_html();
            }
        }
        
        return implode("\n", $html);
    }

    function get_competences()
    {
        $course = $this->get_course();
        $tabs = new DynamicTabsRenderer('competences');
        if (count($course->get_competences_by_type(Competence :: TYPE_BEGIN)) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(Competence :: TYPE_BEGIN, Translation :: get('BeginCompetence'), Theme :: get_image_path() . 'competence/tabs/' . Competence :: TYPE_BEGIN . '.png', $this->get_competences_by_type(Competence :: TYPE_BEGIN)));
        }
        if (count($course->get_competences_by_type(Competence :: TYPE_END)) > 0)
        {
            $tabs->add_tab(new DynamicContentTab(Competence :: TYPE_END, Translation :: get('EndCompetence'), Theme :: get_image_path() . 'competence/tabs/' . Competence :: TYPE_END . '.png', $this->get_competences_by_type(Competence :: TYPE_END)));
        }
        return $tabs->render();
    }

    function get_competences_by_type($type)
    {
        $course = $this->get_course();
        
        $html = array();
        $table_data = array();
        
        if (count($course->get_competences_by_type($type)) > 0)
        {
            $var = ($type == Competence :: TYPE_BEGIN ? 'BeginCompetence' : 'EndCompetence');
            $html[] = '<h3>' . Translation :: get($var) . '</h3>';
        }
        foreach ($course->get_competences_by_type($type) as $competence)
        {
            if ($competence instanceof CompetenceDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $competence->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $competence->get_code();
                //                $table_row[] = $competence->get_summary();
                $table_row[] = $competence->get_description();
                $table_row[] = $competence->get_level();
                
                $table_data[] = $table_row;
            }
        }
        
        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Code'), false);
            //            $table->set_header(1, Translation :: get('Summary'), false);
            $table->set_header(1, Translation :: get('Description'), false);
            $table->set_header(2, Translation :: get('Level'), false);
            $html[] = $table->as_html();
        }
        
        return implode("\n", $html);
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
        
        $html = array();
        $table_data = array();
        
        foreach ($course->get_evaluations() as $evaluation)
        {
            if ($evaluation instanceof EvaluationDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $evaluation->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $evaluation->get_try();
                $table_row[] = $evaluation->get_moment();
                $table_row[] = $evaluation->get_type();
                $table_row[] = $evaluation->get_percentage();
                $table_row[] = $evaluation->get_description();
                
                if ($evaluation->get_permanent())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'evaluation/permanent.png" alt="' . Translation :: get('IsPermanent') . '" title="' . Translation :: get('IsPermanent') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsPermanent'), Translation :: get('Permanent'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'evaluation/not_permanent.png" alt="' . Translation :: get('IsNotPermanent') . '" title="' . Translation :: get('IsNotPermanent') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotPermanent'), Translation :: get('Permanent'));
                    $table_row[] = $image;
                }
                
                $table_data[] = $table_row;
            }
        }
        
        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Try'), false);
            $table->set_header(1, Translation :: get('Moment'), false);
            $table->set_header(2, Translation :: get('Type'), false);
            $table->set_header(3, Translation :: get('Percentage'), false);
            $table->set_header(4, Translation :: get('Remarks'), false);
            $table->set_header(5, Translation :: get('Permanent'), false);
            $html[] = $table->as_html();
        }
        
        return implode("\n", $html);
    }
}
?>