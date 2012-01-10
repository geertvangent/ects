<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\SortableTable;

use application\discovery\ModuleInstance;

use common\libraries\PropertiesTable;

use common\libraries\StringUtilities;

use common\libraries\Display;

use common\libraries\Path;

use common\libraries\ToolbarItem;

use common\libraries\Toolbar;

use common\libraries\Theme;

use common\libraries\SortableTableFromArray;

use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\profile\Module
{

    /* (non-PHPdoc)
     * @see application\discovery\module\profile.Module::get_general_properties()
     */
    function get_general_properties()
    {
        $properties = parent :: get_general_properties();
        
        if ($this->get_profile()->get_birth()->has_date())
        {
            $properties[Translation :: get('BirthDate')] = $this->get_profile()->get_birth();
        }
        
        $properties[Translation :: get('Nationality')] = $this->get_profile()->get_nationality_string();
        $properties[Translation :: get('Gender')] = $this->get_profile()->get_gender_string();
        
        if (count($this->get_profile()->get_address()) == 1)
        {
            $address = $this->get_profile()->get_address();
            $address = $address[0];
            
            $properties[Translation :: get('Address')] = $address;
        }
        
        if (count($this->get_profile()->get_learning_credit()) == 1)
        {
            $learning_credit = array_pop($this->get_profile()->get_learning_credit());
            
            $properties[Translation :: get('LearningCredit')] = $learning_credit->get_html() . ' (' . $learning_credit->get_date() . ')';
        }
        
        if ($this->get_profile()->get_first_university_college())
        {
            $properties[Translation :: get('FirstUniversityCollege')] = $this->get_profile()->get_first_university_college();
        }
        
        if ($this->get_profile()->get_first_university())
        {
            $properties[Translation :: get('FirstUniversity')] = $this->get_profile()->get_first_university();
        }
        
        return $properties;
    }

    function get_previous()
    {
        $html = array();
        $previous_college = $this->get_profile()->get_previous_college();
        if ($previous_college)
        {
            $properties = array();
            $properties[Translation :: get('Date')] = $previous_college->get_date();
            $properties[Translation :: get('Degree')] = '(' . $previous_college->get_degree_type() . ') ' . $previous_college->get_degree_name();
            
            $school = array();
            $school[] = $previous_college->get_school_name();
            if ($previous_college->get_school_city())
            {
                $school[] = $previous_college->get_school_city();
            }
            if ($previous_college->get_country_name())
            {
                $school[] = $previous_college->get_country_name();
            }
            $properties[Translation :: get('School')] = implode('<br>', $school);
            if ($previous_college->get_training_name())
            {
                $properties[Translation :: get('TrainingName')] = $previous_college->get_training_name();
            }
            
            if (! StringUtilities :: is_null_or_empty($previous_college->get_info(), true))
            {
                $properties[Translation :: get('Info')] = $previous_college->get_info();
            }
            
            $table = new PropertiesTable($properties);
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/previous_college.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('PreviousCollege');
            $html[] = '</div>';
            
            $html[] = '<div class="description">';
            
            $html[] = $table->toHtml();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        
        $previous_university = $this->get_profile()->get_previous_university();
        
        if ($previous_university)
        {
            $properties = array();
            $properties[Translation :: get('Date')] = $previous_university->get_date();
            $properties[Translation :: get('Type')] = $previous_university->get_type_string();
            
            $school = array();
            $school[] = $previous_university->get_school_name();
            if ($previous_university->get_school_city())
            {
                $school[] = $previous_university->get_school_city();
            }
            if ($previous_university->get_country_name())
            {
                $school[] = $previous_university->get_country_name();
            }
            $properties[Translation :: get('School')] = implode('<br>', $school);
            
            if ($previous_university->get_training_name())
            {
                $properties[Translation :: get('TrainingName')] = $previous_university->get_training_name();
            }
            
            if (! StringUtilities :: is_null_or_empty($previous_university->get_info(), true))
            {
                $properties[Translation :: get('Info')] = $previous_university->get_info();
            }
            
            $table = new PropertiesTable($properties);
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/previous_university.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('PreviousUniversity');
            $html[] = '</div>';
            
            $html[] = '<div class="description">';
            
            $html[] = $table->toHtml();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        
        return implode("\n", $html);
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\profile.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_profile_parameters()))
        {
            Display :: not_allowed();
        }
        
        if ($this->get_profile())
        {
            $html = array();
            $html[] = parent :: render();
            
            if (count($this->get_profile()->get_address()) > 1)
            {
                $data = array();
                
                foreach ($this->get_profile()->get_address() as $address)
                {
                    $row = array();
                    $row[] = Translation :: get($address->get_type_string());
                    $row[] = $address->get_street();
                    $row[] = $address->get_number();
                    $row[] = $address->get_box();
                    $row[] = $address->get_room();
                    $row[] = $address->get_unified_city();
                    $row[] = $address->get_unified_city_zip_code();
                    $row[] = $address->get_region();
                    $row[] = $address->get_country();
                    $data[] = $row;
                }
                
                $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/address.png);">';
                $html[] = '<div class="title">';
                $html[] = Translation :: get('Addresses');
                $html[] = '</div>';
                
                $html[] = '<div class="description">';
                
                $table = new SortableTableFromArray($data);
                $table->set_header(0, Translation :: get('Type'));
                $table->set_header(1, Translation :: get('Street'));
                $table->set_header(2, Translation :: get('Number'));
                $table->set_header(3, Translation :: get('Box'));
                $table->set_header(4, Translation :: get('Room'));
                $table->set_header(5, Translation :: get('City'));
                $table->set_header(6, Translation :: get('ZipCode'));
                $table->set_header(7, Translation :: get('Region'));
                $table->set_header(8, Translation :: get('Country'));
                $html[] = $table->toHTML();
                
                $html[] = '</div>';
                $html[] = '</div>';
            }
            
            $count_learning_credit = count($this->get_profile()->get_learning_credit());
            if ($count_learning_credit > 1)
            {
                $data = array();
                
                foreach ($this->get_profile()->get_learning_credit() as $learning_credit)
                {
                    $row = array();
                    $row[] = $learning_credit->get_date();
                    $row[] = $learning_credit->get_html();
                    $data[] = $row;
                }
                
                $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/learning_credit.png);">';
                $html[] = '<div class="title">';
                $html[] = Translation :: get('LearningCredits');
                $html[] = '</div>';
                
                $html[] = '<div class="description">';
                
                $table = new SortableTable($data);
                $table->set_header(0, Translation :: get('Date'), false);
                $table->set_header(1, Translation :: get('LearningCredit'), false);
                $html[] = $table->toHTML();
                
                $html[] = '</div>';
                $html[] = '</div>';
            }
            
            $html[] = $this->get_previous();
            
            if ($this->get_application()->get_user()->is_platform_admin())
            {
                $toolbar = new Toolbar();
                
                $url = $this->get_rights_url($this->get_module_instance()->get_id(), $this->get_profile_parameters());
                $toolbar->add_item(new ToolbarItem(Translation :: get('Rights'), Theme :: get_common_image_path() . 'action_rights.png', $url));
                
                $html[] = $toolbar->as_html();
            }
            
            return implode("\n", $html);
        }
        else
        {
            return Display :: normal_message(Translation :: get('NoData'), true);
        }
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_profile_parameters();
        return DataManager :: get_instance($this->get_module_instance())->has_profile($parameters);
    }
}
?>