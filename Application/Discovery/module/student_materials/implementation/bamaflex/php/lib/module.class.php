<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

use common\libraries\Display;
use application\discovery\module\course\implementation\bamaflex\Material;
use application\discovery\module\course\implementation\bamaflex\Course;
use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\career\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\student_materials\Module
{

    function get_enrollment_materials_by_type($enrollment_id, $type)
    {
        $table_data = array();
        $total_price = 0;
        
        $courses = DataManager :: get_instance($this->get_module_instance())->retrieve_courses($enrollment_id);
        
        foreach ($courses as $course)
        {
            $materials = DataManager :: get_instance($this->get_module_instance())->retrieve_materials(
                    $course->get_programme_id(), $type);
            
            foreach ($materials as $material)
            {
                $table_row = array();
                
                $data_source = $this->get_module_instance()->get_setting('data_source');
                $course_module_instance = \application\discovery\Module :: exists(
                        'application\discovery\module\course\implementation\bamaflex', 
                        array('data_source' => $data_source));
                
                if ($course_module_instance)
                {
                    $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                            $course->get_programme_id(), $course->get_source());
                    $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                    $table_row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
                }
                else
                {
                    $table_row[] = $course->get_name();
                }
                
                $table_row[] = $material->get_group();
                $table_row[] = $material->get_title();
                $table_row[] = $material->get_edition();
                $table_row[] = $material->get_author();
                $table_row[] = $material->get_editor();
                $table_row[] = $material->get_isbn();
                $table_row[] = $material->get_medium();
                // $table_row[] = $material->get_description();
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
                    $image = '<img src="' . Theme :: get_image_path() . 'material/for_sale.png" alt="' . Translation :: get(
                            'IsForSale') . '" title="' . Translation :: get('IsForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsForSale'), 
                            Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/not_for_sale.png" alt="' . Translation :: get(
                            'IsNotForSale') . '" title="' . Translation :: get('IsNotForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotForSale'), 
                            Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                
                $total_price += $material->get_price();
                
                $table_data[] = $table_row;
            }
            
            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $materials = DataManager :: get_instance($this->get_module_instance())->retrieve_materials(
                            $child->get_programme_id(), $type);
                    
                    foreach ($materials as $material)
                    {
                        $table_row = array();
                        
                        $data_source = $this->get_module_instance()->get_setting('data_source');
                        $course_module_instance = \application\discovery\Module :: exists(
                                'application\discovery\module\course\implementation\bamaflex', 
                                array('data_source' => $data_source));
                        
                        if ($course_module_instance)
                        {
                            $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                                    $course->get_programme_id(), 1);
                            $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                            $table_row[] = '<span class="course_child"><a href="' . $url . '">' . $child->get_name() . '</a></span>';
                        }
                        else
                        {
                            $table_row[] = '<span class="course_child">' . $child->get_name() . '</span>';
                        }
                        
                        $table_row[] = $material->get_group();
                        $table_row[] = $material->get_title();
                        $table_row[] = $material->get_edition();
                        $table_row[] = $material->get_author();
                        $table_row[] = $material->get_editor();
                        $table_row[] = $material->get_isbn();
                        $table_row[] = $material->get_medium();
                        // $table_row[] = $material->get_description();
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
                            $image = '<img src="' . Theme :: get_image_path() . 'material/for_sale.png" alt="' . Translation :: get(
                                    'IsForSale') . '" title="' . Translation :: get('IsForSale') . '"/>';
                            LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsForSale'), 
                                    Translation :: get('ForSale'));
                            $table_row[] = $image;
                        }
                        else
                        {
                            $image = '<img src="' . Theme :: get_image_path() . 'material/not_for_sale.png" alt="' . Translation :: get(
                                    'IsNotForSale') . '" title="' . Translation :: get('IsNotForSale') . '"/>';
                            LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotForSale'), 
                                    Translation :: get('ForSale'));
                            $table_row[] = $image;
                        }
                        
                        $total_price += $material->get_price();
                        
                        $table_data[] = $table_row;
                    }
                }
            }
        }
        
        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Course'), false);
            $table->set_header(1, Translation :: get('Group'), false);
            $table->set_header(2, Translation :: get('Title'), false);
            $table->set_header(3, Translation :: get('Edition'), false);
            $table->set_header(4, Translation :: get('Author'), false);
            $table->set_header(5, Translation :: get('Editor'), false);
            $table->set_header(6, Translation :: get('Isbn'), false);
            $table->set_header(7, Translation :: get('Medium'), false);
            // $table->set_header(8, Translation :: get('Remarks'), false);
            $table->set_header(8, Translation :: get('Price'), false);
            $table->set_header(9, '', false);
            
            if ($total_price)
            {
                $total_price .= ' &euro;';
            }
            
            $html[] = $table->as_html($total_price, 9);
        }
        
        return implode("\n", $html);
    }

    function get_enrollment_materials($enrollment_id)
    {
        $tabs = new DynamicTabsRenderer('study_materials_' . $enrollment_id);
        
        if ($this->has_enrollment_materials_by_type(null, $enrollment_id, Material :: TYPE_REQUIRED))
        {
            $tabs->add_tab(
                    new DynamicContentTab(Material :: TYPE_REQUIRED, Translation :: get('Required'), null, 
                            $this->get_enrollment_materials_by_type($enrollment_id, Material :: TYPE_REQUIRED)));
        }
        
        if ($this->has_enrollment_materials_by_type(null, $enrollment_id, Material :: TYPE_OPTIONAL))
        {
            $tabs->add_tab(
                    new DynamicContentTab(Material :: TYPE_OPTIONAL, Translation :: get('Optional'), null, 
                            $this->get_enrollment_materials_by_type($enrollment_id, Material :: TYPE_OPTIONAL)));
        }
        return $tabs->render();
    }

    function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_data_manager()->count_materials($this->get_student_materials_parameters(), $year, 
                $enrollment_id, $type);
    }

    function get_enrollments($year)
    {
        $year_enrollments = array();
        
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments(
                $this->get_student_materials_parameters());
        
        foreach ($enrollments as $enrollment)
        {
            if ($enrollment->get_year() == $year)
            {
                $year_enrollments[] = $enrollment;
            }
        }
        
        $tabs = new DynamicTabsRenderer('year_enrollment_' . $year);
        
        foreach ($year_enrollments as $year_enrollment)
        {
            if ($this->has_enrollment_materials_by_type(null, $year_enrollment->get_id()))
            {
                $tab_name = array();
                
                if ($year_enrollment->is_special_result())
                {
                    $tab_image_path = Theme :: get_image_path(
                            Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'result_type/' . $year_enrollment->get_result() . '.png';
                    $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get(
                            $year_enrollment->get_result_string(), null, 
                            'application\discovery\module\enrollment\implementation\bamaflex') . '" title="' . Translation :: get(
                            $year_enrollment->get_result_string(), null, 
                            'application\discovery\module\enrollment\implementation\bamaflex') . '" />';
                    LegendTable :: get_instance()->add_symbol($tab_image, 
                            Translation :: get($year_enrollment->get_result_string(), null, 
                                    'application\discovery\module\enrollment\implementation\bamaflex'), 
                            Translation :: get('ResultType', null, 
                                    'application\discovery\module\enrollment\implementation\bamaflex'));
                }
                else
                {
                    $tab_image_path = null;
                }
                
                $tab_name[] = $year_enrollment->get_training();
                if ($year_enrollment->get_unified_option())
                {
                    $tab_name[] = $year_enrollment->get_unified_option();
                }
                $tab_name = implode(' | ', $tab_name);
                
                $tabs->add_tab(
                        new DynamicContentTab($year_enrollment->get_id(), $tab_name, $tab_image_path, 
                                $this->get_enrollment_materials($year_enrollment->get_id())));
            }
        }
        return $tabs->render();
    }
    
    /*
     * (non-PHPdoc) @see application\discovery\module\career.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, 
                $this->get_module_instance()->get_id(), $this->get_student_materials_parameters()))
        {
            Display :: not_allowed();
        }
        $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years(
                $this->get_student_materials_parameters());
        if (count($years) > 0)
        {
            $tabs = new DynamicTabsRenderer('year_list');
            
            foreach ($years as $year)
            {
                if ($this->has_enrollment_materials_by_type($year))
                {
                    $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_enrollments($year)));
                }
            }
            return $tabs->render();
        }
        else
        {
            return Display :: normal_message(Translation :: get('NoData'), true);
        }
    }
}
?>