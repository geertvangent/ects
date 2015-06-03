<?php
namespace Ehb\Application\Avilarts\Ajax\Component;

use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Chamilo\Libraries\Platform\Session\Request;

/**
 *
 * @package Ehb\Application\Avilarts\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ChangeCourseModuleVisibilityComponent extends \Ehb\Application\Avilarts\Ajax\Manager
{

    public function run()
    {
        $module_id = Request :: post('tool');
        $visible = Request :: post('visible');
        $course = Request :: post('course');

        \Ehb\Application\Avilarts\Course\Storage\DataManager :: set_tool_visibility_by_tool_id(
            $course,
            $module_id,
            $visible);

        JsonAjaxResult :: success();
    }
}