<?php
namespace Ehb\Application\Avilarts\Course\Component;

use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Ehb\Application\Avilarts\Course\Table\SubscribedCourse\SubscribedCourseTable;

/**
 * This class describes a browser for the subscribed courses
 * 
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class BrowseSubscribedCoursesComponent extends BrowseSubscriptionCoursesComponent implements TableSupport
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the course table for this component
     * 
     * @return CourseTable
     */
    protected function get_course_table()
    {
        return new SubscribedCourseTable($this);
    }
}
