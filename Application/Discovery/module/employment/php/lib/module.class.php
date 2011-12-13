<?php
namespace application\discovery\module\employment;

use common\libraries\Path;

use common\libraries\Filesystem;

use common\libraries\Request;

use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    /**
     * @var \application\discovery\module\profile\Profile
     */
    private $profile;
    
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_profile_parameters()
    {
        $parameter = self :: get_module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    static function get_module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     * @return \application\discovery\module\profile\Profile
     */
    function get_profile()
    {
        if (! isset($this->profile))
        {
            $this->profile = DataManager :: get_instance($this->get_module_instance())->retrieve_profile($this->get_profile_parameters());
        }
        
        return $this->profile;
    }

    /**
     * @return multitype:string
     */
    function get_general_properties()
    {
        $properties = array();
        $properties[Translation :: get('FirstName')] = $this->profile->get_name()->get_first_names();
        $properties[Translation :: get('LastName')] = $this->profile->get_name()->get_last_name();
        $properties[Translation :: get('Language')] = $this->profile->get_language();
        
        foreach ($this->profile->get_identification_code() as $identification_code)
        {
            $properties[Translation :: get($identification_code->get_type_string())] = $identification_code->get_code();
        }
        
        if (count($this->profile->get_communication()) == 1)
        {
            $communication = $this->profile->get_communication();
            $communication = $communication[0];
            $properties[Translation :: get('CommunicationNumber')] = $communication->get_number() . ' (' . $communication->get_device_string() . ')';
        }
        
        if (count($this->profile->get_email()) == 1)
        {
            $email = $this->profile->get_email();
            $email = $email[0];
            $properties[Translation :: get('Email')] = $email->get_address() . ' (' . $email->get_type_string() . ')';
        }
        
        return $properties;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        if ($this->profile instanceof Profile)
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/general.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('General');
            $html[] = '</div>';
            
            $html[] = '<div class="description">';
            
            $html[] = '<table style="width: 100%">';
            $html[] = '<tr>';
            
            $html[] = '<td style="padding-right: 10px;">';
            $table = new PropertiesTable($this->get_general_properties());
            //$table->setAttribute('style', 'margin-top: 1em; margin-bottom: 0; margin-right: 300px;');
            $html[] = $table->toHtml();
            $html[] = '</td>';
            
            if ($this->profile->has_photo())
            {
                $html[] = '<td style="text-align: right; vertical-align: top; width: 150px;">';
                $html[] = '<img src="' . $this->profile->get_photo()->get_source() . '" style="width: 150px; border: 1px solid grey;"/>';
                $html[] = '</td>';
            }
            
            $html[] = '</tr>';
            $html[] = '</table>';
            
            $html[] = '</div>';
            $html[] = '</div>';
            
            if (count($this->profile->get_communication()) > 1)
            {
                $data = array();
                
                foreach ($this->profile->get_communication() as $communication)
                {
                    $row = array();
                    $row[] = Translation :: get($communication->get_type_string());
                    $row[] = Translation :: get($communication->get_device_string());
                    $row[] = $communication->get_number();
                    $data[] = $row;
                }
                
                $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/communication_number.png);">';
                $html[] = '<div class="title">';
                $html[] = Translation :: get('CommunicationNumbers');
                $html[] = '</div>';
                
                $html[] = '<div class="description">';
                
                $table = new SortableTableFromArray($data);
                $table->set_header(0, Translation :: get('Type'));
                $table->set_header(1, Translation :: get('Device'));
                $table->set_header(2, Translation :: get('Number'));
                $html[] = $table->toHTML();
                
                $html[] = '</div>';
                $html[] = '</div>';
            }
            
            if (count($this->profile->get_email()) > 1)
            {
                $data = array();
                
                foreach ($this->profile->get_email() as $email)
                {
                    $row = array();
                    $row[] = Translation :: get($email->get_type_string());
                    $row[] = $email->get_address();
                    $data[] = $row;
                }
                
                $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/email_address.png);">';
                $html[] = '<div class="title">';
                $html[] = Translation :: get('EmailAddresses');
                $html[] = '</div>';
                
                $html[] = '<div class="description">';
                
                $table = new SortableTableFromArray($data);
                $table->set_header(0, Translation :: get('Type'));
                $table->set_header(1, Translation :: get('Address'));
                $html[] = $table->toHTML();
                
                $html[] = '</div>';
                $html[] = '</div>';
            }
        
        }
        else
        {
            $html[] = Display :: normal_message('NoData', true);
        }
        
        return implode("\n", $html);
    }

    static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_USER;
    }
}
?>