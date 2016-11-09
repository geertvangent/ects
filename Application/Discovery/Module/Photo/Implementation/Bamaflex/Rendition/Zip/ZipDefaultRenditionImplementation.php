<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition\Zip;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Discovery\Module\Photo\DataManager;
use Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights;

class ZipDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var string
     */
    private $temporary_directory;

    public function render()
    {
        if (! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $this->prepare_file_system();
        
        $parameters = new DataClassRetrievesParameters($this->get_module()->get_condition());
        $users = \Chamilo\Core\User\Storage\DataManager::retrieves(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $parameters);
        
        while ($user = $users->next_result())
        {
            $photo = DataManager::getInstance($this->get_module_instance())->retrieve_photo(
                $user->get_official_code(), 
                false);
            
            $file = $this->temporary_directory . Filesystem::create_safe_name($user->get_fullname()) . '.jpg';
            
            Filesystem::copy_file($photo, $file);
        }
        
        return \Ehb\Application\Discovery\Rendition\View\Zip\ZipDefaultRendition::save(
            $this->temporary_directory, 
            $this->get_file_name());
    }

    public function get_file_name()
    {
        $file_name_parts = array();
        
        $parameters = $this->get_module_parameters();
        $codes = array();
        
        if ($parameters->get_faculty_id())
        {
            $faculty = DataManager::getInstance($this->get_module_instance())->retrieve_faculty(
                $parameters->get_faculty_id());
            $file_name_parts[] = $faculty->get_year();
            $file_name_parts[] = $faculty->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module::TYPE_TEACHER :
                        $file_name_parts[] = Translation::get('Teachers');
                        break;
                    case Module::TYPE_STUDENT :
                        $file_name_parts[] = Translation::get('Students');
                        break;
                    case Module::TYPE_EMPLOYEE :
                        $file_name_parts[] = Translation::get('Employees');
                        break;
                }
            }
        }
        elseif ($parameters->get_training_id())
        {
            $training = DataManager::getInstance($this->get_module_instance())->retrieve_training(
                $parameters->get_training_id());
            $file_name_parts[] = $training->get_year();
            $file_name_parts[] = $training->get_faculty();
            $file_name_parts[] = $training->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module::TYPE_TEACHER :
                        $file_name_parts[] = Translation::get('Teachers');
                        break;
                    case Module::TYPE_STUDENT :
                        $file_name_parts[] = Translation::get('Students');
                        break;
                }
            }
        }
        elseif ($parameters->get_programme_id())
        {
            $programme = DataManager::getInstance($this->get_module_instance())->retrieve_programme(
                $parameters->get_programme_id());
            $file_name_parts[] = $programme->get_year();
            $file_name_parts[] = $programme->get_faculty();
            $file_name_parts[] = $programme->get_training();
            $file_name_parts[] = $programme->get_name();
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module::TYPE_TEACHER :
                        $file_name_parts[] = Translation::get('Teachers');
                        break;
                    case Module::TYPE_STUDENT :
                        $file_name_parts[] = Translation::get('Students');
                        break;
                }
            }
        }
        
        return implode(' ', $file_name_parts) . ' ' . Translation::get('TypeName');
    }

    public function prepare_file_system()
    {
        $user_id = Session::get_user_id();
        
        $this->temporary_directory = Path::getInstance()->getTemporaryPath(__NAMESPACE__) . $user_id . '/export_photos/';
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
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_XLSX;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
