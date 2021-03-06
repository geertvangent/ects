<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager::PARAM_PUBLICATION_ID);
    }

    /**
     * Adds toolbar items to the toolbar
     * 
     * @return array ToolbarItems
     */
    public function get_tool_actions()
    {
        $publicationIdentifier = $this->getRequest()->query->get(
            \Chamilo\Application\Weblcms\Manager::PARAM_PUBLICATION);
        
        $actions = array();
        
        $actions[] = new ToolbarItem(
            Translation::get('SubmissionSubmit'), 
            Theme::getInstance()->getCommonImagePath('Action/Add'), 
            $this->get_url(
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager::PARAM_ACTION => self::ACTION_ENTRY, 
                    \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager::PARAM_ACTION => \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager::ACTION_SUBMIT, 
                    \Chamilo\Application\Weblcms\Tool\Manager::PARAM_PUBLICATION_ID => $publicationIdentifier, 
                    \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager::PARAM_ENTITY_ID => $this->getUser()->getId(), 
                    \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager::PARAM_ENTITY_TYPE => Entry::ENTITY_TYPE_USER)), 
            ToolbarItem::DISPLAY_ICON_AND_LABEL);
        
        return $actions;
    }
}
