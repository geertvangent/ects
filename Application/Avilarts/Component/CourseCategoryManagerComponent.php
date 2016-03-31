<?php
namespace Ehb\Application\Avilarts\Component;

use Chamilo\Configuration\Category\Interfaces\CategorySupport;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Avilarts\Form\CategoryForm;
use Ehb\Application\Avilarts\Manager;
use Ehb\Application\Avilarts\Storage\DataClass\CourseCategory;
use Ehb\Application\Avilarts\Storage\DataManager;

/**
 * Weblcms component allows the user to manage course categories
 */
class CourseCategoryManagerComponent extends Manager implements DelegateComponent, CategorySupport
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        $factory = new ApplicationFactory(
            \Chamilo\Configuration\Category\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        if ($this->get_user()->is_platform_admin())
        {
            $redirect = new Redirect(
                array(
                    Application :: PARAM_CONTEXT => \Chamilo\Core\Admin\Manager :: context(),
                    \Chamilo\Core\Admin\Manager :: PARAM_ACTION => \Chamilo\Core\Admin\Manager :: ACTION_ADMIN_BROWSER));
            $breadcrumbtrail->add(
                new Breadcrumb($redirect->getUrl(), Translation :: get('TypeName', null, 'Chamilo\Core\Admin')));

            $redirect = new Redirect(
                array(
                    Application :: PARAM_CONTEXT => \Chamilo\Core\Admin\Manager :: context(),
                    \Chamilo\Core\Admin\Manager :: PARAM_ACTION => \Chamilo\Core\Admin\Manager :: ACTION_ADMIN_BROWSER,
                    DynamicTabsRenderer :: PARAM_SELECTED_TAB => ClassnameUtilities :: getInstance()->getNamespaceId(
                        self :: package())));
            $breadcrumbtrail->add(new Breadcrumb($redirect->getUrl(), Translation :: get('Courses')));
        }
    }

    public function get_category_parameters()
    {
        return array();
    }

    public function get_category()
    {
        return new CourseCategory();
    }

    public function get_category_form()
    {
        return new CategoryForm();
    }

    public function count_categories($condition)
    {
        return DataManager :: count(CourseCategory :: class_name(), new DataClassCountParameters($condition));
    }

    public function retrieve_categories($condition, $offset, $count, $order_property)
    {
        return DataManager :: retrieves(
            CourseCategory :: class_name(),
            new DataClassRetrievesParameters($condition, $count, $offset, $order_property));
    }

    // Runs through dataclass
    public function get_next_category_display_order($parent_id)
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \configuration\category\CategorySupport::allowed_to_delete_category()
     */
    public function allowed_to_delete_category($category_id)
    {
        return true;
    }

    /*
     * (non-PHPdoc) @see \configuration\category\CategorySupport::allowed_to_edit_category()
     */
    public function allowed_to_edit_category($category_id)
    {
        return true;
    }

    /*
     * (non-PHPdoc) @see \configuration\category\CategorySupport::allowed_to_change_category_visibility()
     */
    public function allowed_to_change_category_visibility($category_id)
    {
        return true;
    }
}
