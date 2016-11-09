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
class CategoryManagerComponent extends Manager implements DelegateComponent
{

    /**
     * #Override Returns additional parameters as an array.
     * 
     * @return array The additional parameters
     */
    public function get_additional_parameters()
    {
        return array(\Chamilo\Configuration\Category\Manager::PARAM_CATEGORY_ID);
    }
}
