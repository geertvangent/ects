<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;

class IntroductionPublisherComponent extends Manager implements DelegateComponent
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('weblcms_course_group_introduction_publisher');
    }
}
