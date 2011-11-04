<?php
namespace application\discovery\module\profile\implementation\bamaflex;

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
        
        return $properties;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\profile.Module::render()
     */
    function render()
    {
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
            
            $data_source = $this->get_module_instance()->get_setting('data_source');
            $enrollment_module_instance = \application\discovery\Module :: exists('application\discovery\module\enrollment\implementation\bamaflex', array(
                    'data_source' => $data_source));
            
            $career_module_instance = \application\discovery\Module :: exists('application\discovery\module\career\implementation\bamaflex', array(
                    'data_source' => $data_source));
            
            $teaching_assignment_module_instance = \application\discovery\Module :: exists('application\discovery\module\teaching_assignment\implementation\bamaflex', array(
                    'data_source' => $data_source));
            
            $toolbar = new Toolbar();
            
            if ($enrollment_module_instance)
            {
                $parameters = new \application\discovery\module\enrollment\Parameters($this->get_profile_parameters()->get_user_id());
                $url = $this->get_instance_url($enrollment_module_instance->get_id(), $parameters);
                $toolbar->add_item(new ToolbarItem($enrollment_module_instance->get_title(), Theme :: get_image_path('application\discovery\module\enrollment\implementation\bamaflex') . 'logo/16.png', $url));
            }
            
            if ($career_module_instance)
            {
                $parameters = new \application\discovery\module\career\Parameters($this->get_profile_parameters()->get_user_id());
                $url = $this->get_instance_url($career_module_instance->get_id(), $parameters);
                $toolbar->add_item(new ToolbarItem($career_module_instance->get_title(), Theme :: get_image_path('application\discovery\module\career\implementation\bamaflex') . 'logo/16.png', $url));
            }
            
            if ($teaching_assignment_module_instance)
            {
                $parameters = new \application\discovery\module\teaching_assignment\Parameters($this->get_profile_parameters()->get_user_id());
                $url = $this->get_instance_url($teaching_assignment_module_instance->get_id(), $parameters);
                $toolbar->add_item(new ToolbarItem($teaching_assignment_module_instance->get_title(), Theme :: get_image_path('application\discovery\module\teaching_assignment\implementation\bamaflex') . 'logo/16.png', $url));
            }
            
            $html[] = $toolbar->as_html();
            
            return implode("\n", $html);
        }
        else
        {
        	return Display::normal_message(Translation :: get('NoData'), true);
        }
    }
}
?>