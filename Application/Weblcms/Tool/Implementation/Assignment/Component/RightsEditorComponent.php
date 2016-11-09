<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class RightsEditorComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @param unknown $location
     * @return string[]
     */
    public function get_available_rights($location)
    {
        return WeblcmsRights::get_available_rights($location);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::get_additional_parameters()
     */
    public function get_additional_parameters()
    {
        array(\Chamilo\Application\Weblcms\Tool\Manager::PARAM_PUBLICATION_ID);
    }
}
