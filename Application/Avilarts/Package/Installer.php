<?php
namespace Ehb\Application\Avilarts\Package;

use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Rights\CourseManagementRights;

use Ehb\Application\Avilarts\Storage\DataClass\CourseCategory;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

/**
 *
 * @package application.lib.weblcms.install
 */

/**
 * This installer can be used to create the storage structure for the weblcms application.
 */
class Installer extends \Chamilo\Configuration\Package\Action\Installer
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Runs the install-script.
     */
    public function extra()
    {
        if (! $this->create_courses_subtree())
        {
            return false;
        }
        else
        {
            $this->add_message(
                self :: TYPE_NORMAL,
                Translation :: get(
                    'ObjectCreated',
                    array('OBJECT' => Translation :: get('CoursesTree')),
                    Utilities :: COMMON_LIBRARIES));
        }
        if (! $this->create_default_categories_in_weblcms())
        {
            return false;
        }

        if (! CourseSettingsController :: install_course_settings($this))
        {
            return false;
        }

        if (! \Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->create_request_root())
        {
            return false;
        }
        else
        {
            $this->add_message(self :: TYPE_NORMAL, Translation :: get('QuotaLocationCreated'));
        }

        return true;
    }

    /**
     * **************************************************************************************************************
     * Helper Functionality *
     * **************************************************************************************************************
     */

    /**
     * Installs the root location of the courses subtree and sets the default rights for everyone on root location so
     * the rights are no issue when using no course type
     *
     * @return boolean
     */
    private function create_courses_subtree()
    {
        $rights_utilities = CourseManagementRights :: get_instance();

        $location = $rights_utilities->create_subtree_root_location(0, \Ehb\Application\Avilarts\Rights\Rights :: TREE_TYPE_COURSE, true);

        if (! $location)
        {
            return false;
        }

        $specific_rights = $rights_utilities->get_specific_course_management_rights();

        foreach ($specific_rights as $right_id)
        {

            if (! $rights_utilities->invert_location_entity_right($right_id, 0, 0, $location->get_id()))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Installs example course categories
     *
     * @return boolean
     */
    private function create_default_categories_in_weblcms()
    {
        // Creating Language Skills
        $cat = new CourseCategory();
        $cat->set_name('Language skills');
        $cat->set_parent('0');
        $cat->set_display_order(1);

        if (! $cat->create())
        {
            return false;
        }

        // creating PC Skills
        $cat = new CourseCategory();
        $cat->set_name('PC skills');
        $cat->set_parent('0');
        $cat->set_display_order(1);
        if (! $cat->create())
        {
            return false;
        }

        // creating Projects
        $cat = new CourseCategory();
        $cat->set_name('Projects');
        $cat->set_parent('0');
        $cat->set_display_order(1);
        if (! $cat->create())
        {
            return false;
        }

        return true;
    }
}
