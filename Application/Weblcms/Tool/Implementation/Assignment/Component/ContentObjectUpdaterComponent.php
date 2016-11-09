<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ContentObjectUpdaterComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager::PARAM_PUBLICATION_ID);
    }
}
