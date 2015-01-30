<?php
namespace Ehb\Application\Atlantis\Role\Entitlement\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Role\Entitlement\Form\EntitlementForm;
use Ehb\Application\Atlantis\Role\Entitlement\Manager;
use Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;
use Ehb\Application\Atlantis\Role\Entitlement\Storage\DataManager;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class ListerComponent extends Manager implements DelegateComponent
{

    private $application_id;

    private $role_id;

    public function run()
    {
        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $this->role_id = Request :: get(\Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID);
        $this->application_id = Request :: get(\Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID);
        $role = \Ehb\Application\Atlantis\Role\DataManager :: retrieve_by_id(
            \Ehb\Application\Atlantis\Role\DataClass\Role :: class_name(),
            (int) $this->role_id);

        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(
                    ClassnameUtilities :: getInstance()->getClassnameFromNamespace(self :: class_name()),
                    array('ROLE' => $role->get_name()))));

        // for each application, a list of rights
        $applications = \Ehb\Application\Atlantis\Application\Storage\DataManager :: retrieves(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name());
        if (! $this->application_id)
        {
            $this->application_id = $applications->next_result()->get_id();
            $applications->reset();
        }

        $form = new EntitlementForm(
            $this,
            $this->get_url(
                array(\Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id)));
        if ($form->validate())
        {
            $parameters = new DataClassRetrievesParameters(
                new EqualityCondition(
                    new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_ROLE_ID),
                    new StaticConditionVariable($this->role_id)));
            $entitlements = DataManager :: retrieves(Entitlement :: class_name(), $parameters);
            $stored_rights = array();
            while ($entitlement = $entitlements->next_result())
            {
                if ($entitlement->get_right()->get_application_id() == $this->application_id)
                {
                    $stored_rights[$entitlement->get_id()] = $entitlement->get_right_id();
                }
            }

            $stored_entitlements = array_flip($stored_rights);

            $export_values = $form->exportValues();

            $selected_rights = array_keys($export_values['right']);

            $to_delete = array_diff($stored_rights, $selected_rights);
            $to_add = array_diff($selected_rights, $stored_rights);
            $failures = 0;

            foreach ($to_delete as $right_id)
            {
                if (! DataManager :: delete(
                    DataManager :: retrieve_by_id(Entitlement :: class_name(), (int) $stored_entitlements[$right_id])))
                {
                    $failures ++;
                }
            }

            foreach ($to_add as $right_id)
            {
                $entitlement = new Entitlement();
                $entitlement->set_role_id($this->role_id);
                $entitlement->set_right_id($right_id);
                if (! $entitlement->create())
                {
                    $failures ++;
                }
            }

            if ($failures)
            {
                if ((count($to_add) + count($to_delete)) == 1)
                {
                    $message = 'ObjectNotUpdated';
                    $parameter = array('OBJECT' => Translation :: get('Entitlement'));
                }
                elseif ((count($to_add) + count($to_delete)) > $failures)
                {
                    $message = 'SomeObjectsNotUpdated';
                    $parameter = array('OBJECTS' => Translation :: get('Entitlements'));
                }
                else
                {
                    $message = 'ObjectsNotUpdated';
                    $parameter = array('OBJECTS' => Translation :: get('Entitlements'));
                }
            }
            else
            {
                if ((count($to_add) + count($to_delete)) == 1)
                {
                    $message = 'ObjectUpdated';
                    $parameter = array('OBJECT' => Translation :: get('Entitlement'));
                }
                else
                {
                    $message = 'ObjectsUpdated';
                    $parameter = array('OBJECTS' => Translation :: get('Entitlements'));
                }
            }

            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES),
                ($failures ? true : false),
                array(
                    Manager :: PARAM_ACTION => Manager :: ACTION_LIST,
                    \Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id));
        }
        else
        {
            $tabs = new DynamicVisualTabsRenderer($renderer_name, $form->toHtml());

            while ($application = $applications->next_result())
            {
                $link = $this->get_url(
                    array(
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID => $this->role_id,
                        \Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $application->get_id()));
                if ($application->get_id() == $this->application_id)
                {
                    $selected = true;
                }
                else
                {
                    $selected = false;
                }

                $tabs->add_tab(
                    new DynamicVisualTab($application->get_id, $application->get_name(), '', $link, $selected));
            }
            $this->add_breadcrumb();
            $this->display_header();
            echo $tabs->render();
            $this->display_footer();
        }
    }

    public function get_application_id()
    {
        return $this->application_id;
    }

    public function get_role_id()
    {
        return $this->role_id;
    }

    public function add_breadcrumb()
    {
        $role = \Ehb\Application\Atlantis\Role\DataManager :: retrieve_by_id(
            \Ehb\Application\Atlantis\Role\DataClass\Role :: class_name(),
            (int) $this->role_id);

        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
    }
}
