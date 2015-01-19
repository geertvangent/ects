<?php
namespace Ehb\Application\Atlantis\Role\Entity\Component;

use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\Condition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertiesConditionVariable;
use Ehb\Application\Atlantis\Role\Entity\Manager;
use Ehb\Application\Atlantis\Role\Entity\Entities\UserEntity;
use Ehb\Application\Atlantis\Role\Entity\Entities\PlatformGroupEntity;
use Ehb\Application\Atlantis\Role\Entity\Form\EntityForm;
use Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntityTracker;
use Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity;

class CreatorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        if (! \Ehb\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }

        $form = new EntityForm($this, $this->get_url());

        if ($form->validate())
        {
            $values = $form->exportValues();
            $failures = 0;

            foreach ($values['entity'] as $entity_type => $entity_ids)
            {
                foreach ($entity_ids as $entity_id)
                {
                    foreach ($values['role']['role'] as $role)
                    {
                        foreach ($values['context'][PlatformGroupEntity :: ENTITY_TYPE] as $context)
                        {
                            $new_start_date = Utilities :: time_from_datepicker_without_timepicker(
                                $values['start_date']);
                            $new_end_date = Utilities :: time_from_datepicker_without_timepicker($values['end_date']);

                            $conditions = array();
                            $conditions[] = new EqualityCondition(
                                new PropertyConditionVariable(
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: PROPERTY_ENTITY_TYPE),
                                new StaticConditionVariable($entity_type));
                            $conditions[] = new EqualityCondition(
                                new PropertyConditionVariable(
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: PROPERTY_ENTITY_ID),
                                new StaticConditionVariable($entity_id));
                            $conditions[] = new EqualityCondition(
                                new PropertyConditionVariable(
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: PROPERTY_CONTEXT_ID),
                                new StaticConditionVariable($context));
                            $conditions[] = new EqualityCondition(
                                new PropertyConditionVariable(
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                    \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: PROPERTY_ROLE_ID),
                                new StaticConditionVariable($role));
                            $condition = new AndCondition($conditions);

                            $parameters = new DataClassRetrievesParameters(
                                $condition,
                                null,
                                null,
                                array(
                                    new OrderBy(
                                        new PropertiesConditionVariable(
                                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: PROPERTY_START_DATE))));

                            $has_merged = $this->merge_entities($parameters, null, $new_start_date, $new_end_date);

                            if (! $has_merged)
                            {
                                $entity = new RoleEntity();
                                $entity->set_entity_id($entity_id);
                                $entity->set_entity_type($entity_type);
                                $entity->set_role_id($role);
                                $entity->set_context_id($context);
                                $entity->set_start_date($new_start_date);
                                $entity->set_end_date($new_end_date);

                                if (! $entity->create())
                                {
                                    $failures ++;
                                }
                                else
                                {
                                    $entity->track($this->get_user_id(), RoleEntityTracker :: ACTION_TYPE_CREATE);
                                }
                            }
                        }
                    }
                }
            }

            $count = (count($values['entity'][UserEntity :: ENTITY_TYPE]) + count(
                $values['entity'][PlatformGroupEntity :: ENTITY_TYPE])) * count($values['role']) * count(
                $values['context']) * count($values['start_date']) * count($values['end_date']);

            if ($failures)
            {
                if ($count == 1)
                {
                    $message = 'ObjectNotCreated';
                    $parameter = array('OBJECT' => Translation :: get('Entity'));
                }
                elseif ($count > $failures)
                {
                    $message = 'SomeObjectsNotCreated';
                    $parameter = array('OBJECTS' => Translation :: get('Entities'));
                }
                else
                {
                    $message = 'ObjectsNotCreated';
                    $parameter = array('OBJECTS' => Translation :: get('Entities'));
                }
            }
            else
            {
                if ($count == 1)
                {
                    $message = 'ObjectCreated';
                    $parameter = array('OBJECT' => Translation :: get('Entity'));
                }
                else
                {
                    $message = 'ObjectsCreated';
                    $parameter = array('OBJECTS' => Translation :: get('Entities'));
                }
            }

            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES),
                ($failures ? true : false),
                array(Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE));
        }
        else
        {
            $this->display_header();
            echo $form->toHtml();
            $this->display_footer();
        }
    }

    public function merge_entities($parameters, $condition, $start_date, $end_date)
    {
        if ($condition instanceof Condition)
        {
            $parameters->set_condition(new AndCondition($parameters->get_condition(), $condition));
        }

        $role_entities = \Ehb\Application\Atlantis\Role\Entity\Storage\DataManager :: retrieves(
            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
            $parameters);

        while ($role_entity = $role_entities->next_result())
        {
            if ($role_entity->get_start_date() <= $start_date && $role_entity->get_end_date() >= $end_date)
            {
                return true;
            }
            elseif ($role_entity->get_start_date() >= $start_date && $role_entity->get_end_date() <= $end_date)
            {
                $role_entity->set_start_date($start_date);
                $role_entity->set_end_date($end_date);
                if (! $role_entity->update())
                {
                    throw new \Exception('Error');
                }
                else
                {
                    $condition = new NotCondition(
                        new EqualityCondition(
                            new PropertyConditionVariable(
                                \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                RoleEntity :: PROPERTY_ID),
                            new StaticConditionVariable($role_entity->get_id())));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        if ($role_entity->delete())
                        {
                            $role_entity->track($this->get_user_id(), RoleEntityTracker :: ACTION_TYPE_MERGE);
                        }
                    }

                    return true || $was_merged;
                }
            }
            elseif (($role_entity->get_start_date() >= $start_date) && ($role_entity->get_start_date() <= $end_date) &&
                 ($role_entity->get_end_date() >= $end_date))
            {
                $role_entity->set_start_date($start_date);
                if (! $role_entity->update())
                {
                    throw new \Exception('Error');
                }
                else
                {
                    $condition = new NotCondition(
                        new EqualityCondition(
                            new PropertyConditionVariable(
                                \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                RoleEntity :: PROPERTY_ID),
                            new StaticConditionVariable($role_entity->get_id())));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        if ($role_entity->delete())
                        {
                            $role_entity->track($this->get_user_id(), RoleEntityTracker :: ACTION_TYPE_MERGE);
                        }
                    }

                    return true || $was_merged;
                }
            }
            elseif (($role_entity->get_start_date() <= $start_date) && ($role_entity->get_end_date() >= $start_date) &&
                 ($role_entity->get_end_date() <= $end_date))
            {
                $role_entity->set_end_date($end_date);
                if (! $role_entity->update())
                {
                    throw new \Exception('Error');
                }
                else
                {
                    $condition = new NotCondition(
                        new EqualityCondition(
                            new PropertyConditionVariable(
                                \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity :: class_name(),
                                RoleEntity :: PROPERTY_ID),
                            new StaticConditionVariable($role_entity->get_id())));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        if ($role_entity->delete())
                        {
                            $role_entity->track($this->get_user_id(), RoleEntityTracker :: ACTION_TYPE_MERGE);
                        }
                    }

                    return true || $was_merged;
                }
            }
            else
            {
                return false;
            }
        }
    }
}
