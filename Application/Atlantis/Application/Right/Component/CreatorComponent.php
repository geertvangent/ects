<?php
namespace Ehb\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Right\Form\RightForm;
use Ehb\Application\Atlantis\Application\Right\Manager;
use Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

class CreatorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs::add(
            new Breadcrumb(
                $this->get_url(), 
                Translation::get(ClassnameUtilities::getInstance()->getClassnameFromNamespace(self::class_name()))));
        
        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self::PARAM_ACTION => self::ACTION_BROWSE));
        }
        
        $right = new Right();
        $right->set_application_id(
            $this->get_parameter(\Ehb\Application\Atlantis\Application\Manager::PARAM_APPLICATION_ID));
        
        $form = new RightForm($right, $this->get_url(array(self::PARAM_ACTION => self::ACTION_CREATE)));
        
        if ($form->validate())
        {
            $values = $form->exportValues();
            
            $right->set_name($values[Right::PROPERTY_NAME]);
            $right->set_description($values[Right::PROPERTY_DESCRIPTION]);
            $right->set_code($values[Right::PROPERTY_CODE]);
            
            $success = $right->create();
            
            $parameters = array();
            $parameters[self::PARAM_ACTION] = self::ACTION_BROWSE;
            
            $this->redirect(
                Translation::get(
                    $success ? 'ObjectCreated' : 'ObjectNotCreated', 
                    array('OBJECT' => Translation::get('Right')), 
                    Utilities::COMMON_LIBRARIES), 
                ($success ? false : true), 
                $parameters);
        }
        else
        {
            $html = array();
            
            $html[] = $this->render_header();
            $html[] = $form->toHtml();
            $html[] = $this->render_footer();
            
            return implode(PHP_EOL, $html);
        }
    }
}
