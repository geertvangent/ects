<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

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
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }

    /**
     * Adds toolbar items to the toolbar
     *
     * @return array ToolbarItems
     */
    public function get_tool_actions()
    {
        $actions = array();
        $publication_id = Request :: get(\Chamilo\Application\Weblcms\Manager :: PARAM_PUBLICATION);
        $actions[] = new ToolbarItem(
            Translation :: get('SubmissionSubmit'),
            Theme :: getInstance()->getCommonImagePath('Action/Add'),
            $this->get_url(
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => self :: ACTION_SUBMIT_SUBMISSION,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication_id,
                    self :: PARAM_TARGET_ID => $this->get_user_id(),
                    self :: PARAM_ENTITY_TYPE => \Chamilo\Application\Weblcms\Integration\Chamilo\Core\Tracking\Storage\DataClass\AssignmentSubmission :: SUBMITTER_TYPE_USER)),
            ToolbarItem :: DISPLAY_ICON_AND_LABEL);
        return $actions;
    }
}
