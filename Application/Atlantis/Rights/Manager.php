<?php
namespace Ehb\Application\Atlantis\Rights;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'access_rights_action';
    const PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID = 'location_entity_right_group_id';
    const ACTION_CREATE = 'Creator';
    const ACTION_ACCESS = 'Accessor';
    const ACTION_BROWSE = 'Browser';
    const ACTION_DELETE = 'Deleter';
    const DEFAULT_ACTION = self::ACTION_CREATE;

    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        parent::__construct($applicationConfiguration);
        
        SessionBreadcrumbs::add(new Breadcrumb($this->get_url(), Translation::get('TypeName')));
    }

    public static function launch($application)
    {
        parent::launch(null, $application);
    }

    public function get_tabs($current_tab, $content)
    {
        $tabs = new DynamicVisualTabsRenderer(
            ClassnameUtilities::getInstance()->getClassnameFromNamespace(__NAMESPACE__, true), 
            $content);
        
        $tabs->add_tab(
            new DynamicVisualTab(
                self::ACTION_CREATE, 
                Translation::get('Add'), 
                Theme::getInstance()->getImagesPath() . 'Tab/' . self::ACTION_CREATE . '.png', 
                $this->get_url(array(self::PARAM_ACTION => self::ACTION_CREATE)), 
                ($current_tab == self::ACTION_CREATE ? true : false)));
        $tabs->add_tab(
            new DynamicVisualTab(
                self::ACTION_ACCESS, 
                Translation::get('GeneralAccess'), 
                Theme::getInstance()->getImagesPath() . 'Tab/' . self::ACTION_ACCESS . '.png', 
                $this->get_url(array(self::PARAM_ACTION => self::ACTION_ACCESS)), 
                ($current_tab == self::ACTION_ACCESS ? true : false)));
        $tabs->add_tab(
            new DynamicVisualTab(
                self::ACTION_BROWSE, 
                Translation::get('Targets'), 
                Theme::getInstance()->getImagesPath() . 'Tab/' . self::ACTION_BROWSE . '.png', 
                $this->get_url(array(self::PARAM_ACTION => self::ACTION_BROWSE)), 
                ($current_tab == self::ACTION_BROWSE ? true : false)));
        return $tabs;
    }
}
