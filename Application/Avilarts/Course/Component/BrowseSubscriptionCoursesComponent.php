<?php
namespace Ehb\Application\Avilarts\Course\Component;

/**
 * Abstract class that extends the browse component and is used for the subcribed / unsubscribed browser
 * 
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
abstract class BrowseSubscriptionCoursesComponent extends BrowseComponent
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Checkes whether or not the current user can view this component
     * 
     * @return boolean
     */
    protected function can_view_component()
    {
        return true;
    }

    protected function getButtonToolbarRenderer()
    {
        return $this->build_basic_action_bar();
    }
}
