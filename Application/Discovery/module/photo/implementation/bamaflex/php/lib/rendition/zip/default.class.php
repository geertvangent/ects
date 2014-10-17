<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\platform\Translation;
use libraries\file\Filesystem;
use application\discovery\module\photo\DataManager;
use libraries\storage\DataClassRetrievesParameters;
use libraries\file\Path;
use libraries\platform\Session;
use libraries\format\Display;

class ZipDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var string
     */
    private $temporary_directory;

    public function render()
    {
        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        $this->prepare_file_system();
        
        $parameters = new DataClassRetrievesParameters($this->get_module()->get_condition());
        $users = \core\user\DataManager :: retrieves(\core\user\User :: class_name(), $parameters);
        
        while ($user = $users->next_result())
        {
            $photo = DataManager :: get_instance($this->get_module_instance())->retrieve_photo(
                $user->get_official_code(), 
                false);
            
            $file = $this->temporary_directory . Filesystem :: create_safe_name($user->get_fullname()) . '.jpg';
            
            Filesystem :: copy_file($photo, $file);
        }
        
        return \application\discovery\ZipDefaultRendition :: save($this->temporary_directory, $this->get_file_name());
    }

    public function get_file_name()
    {
        $file_name_parts = array();
        
        $parameters = $this->get_module_parameters();
        $codes = array();
        
        if ($parameters->get_faculty_id())
        {
            $faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty(
                $parameters->get_faculty_id());
            $file_name_parts[] = $faculty->get_year();
            $file_name_parts[] = $faculty->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        $file_name_parts[] = Translation :: get('Teachers');
                        break;
                    case Module :: TYPE_STUDENT :
                        $file_name_parts[] = Translation :: get('Students');
                        break;
                    case Module :: TYPE_EMPLOYEE :
                        $file_name_parts[] = Translation :: get('Employees');
                        break;
                }
            }
        }
        elseif ($parameters->get_training_id())
        {
            $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                $parameters->get_training_id());
            $file_name_parts[] = $training->get_year();
            $file_name_parts[] = $training->get_faculty();
            $file_name_parts[] = $training->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        $file_name_parts[] = Translation :: get('Teachers');
                        break;
                    case Module :: TYPE_STUDENT :
                        $file_name_parts[] = Translation :: get('Students');
                        break;
                }
            }
        }
        elseif ($parameters->get_programme_id())
        {
            $programme = DataManager :: get_instance($this->get_module_instance())->retrieve_programme(
                $parameters->get_programme_id());
            $file_name_parts[] = $programme->get_year();
            $file_name_parts[] = $programme->get_faculty();
            $file_name_parts[] = $programme->get_training();
            $file_name_parts[] = $programme->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        $file_name_parts[] = Translation :: get('Teachers');
                        break;
                    case Module :: TYPE_STUDENT :
                        $file_name_parts[] = Translation :: get('Students');
                        break;
                }
            }
        }
        
        return implode(' ', $file_name_parts) . ' ' . Translation :: get('TypeName');
    }

    public function prepare_file_system()
    {
        $user_id = Session :: get_user_id();
        
        $this->temporary_directory = Path :: get_temp_path(__NAMESPACE__) . $user_id . '/export_photos/';
        if (! is_dir($this->temporary_directory))
        {
            mkdir($this->temporary_directory, 0777, true);
        }
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_XLSX;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
