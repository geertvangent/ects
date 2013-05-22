<?php
namespace application\atlantis\role\entity;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use rights\NewPlatformGroupEntity;
use rights\NewUserEntity;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Condition;
use common\libraries\NotCondition;

class CreatorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        if (! $this->get_user()->is_platform_admin())
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
                        foreach ($values['context']['context'] as $context)
                        {
                            $new_start_date = Utilities :: time_from_datepicker($values['start_date']);
                            $new_end_date = Utilities :: time_from_datepicker($values['end_date']);

                            $conditions = array();
                            $conditions[] = new EqualityCondition(
                                \application\atlantis\role\entity\RoleEntity :: PROPERTY_ENTITY_TYPE,
                                $entity_type);
                            $conditions[] = new EqualityCondition(
                                \application\atlantis\role\entity\RoleEntity :: PROPERTY_ENTITY_ID,
                                $entity_id);
                            $conditions[] = new EqualityCondition(
                                \application\atlantis\role\entity\RoleEntity :: PROPERTY_CONTEXT_ID,
                                $context);
                            $conditions[] = new EqualityCondition(
                                \application\atlantis\role\entity\RoleEntity :: PROPERTY_ROLE_ID,
                                $role);
                            $condition = new AndCondition($conditions);

                            $parameters = new DataClassRetrievesParameters($condition);

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
                            }
                        }
                    }
                }
            }

            $count = (count($values['entity'][NewUserEntity :: ENTITY_TYPE]) + count(
                $values['entity'][NewPlatformGroupEntity :: ENTITY_TYPE])) * count($values['role']) * count(
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
//         $merge_parameters = clone $parameters;

        if ($condition instanceof Condition)
        {
            $parameters->set_condition(new AndCondition($parameters->get_condition(), $condition));
        }

        $role_entities = \application\atlantis\role\entity\DataManager :: retrieves(
            \application\atlantis\role\entity\RoleEntity :: class_name(),
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
                        new EqualityCondition(RoleEntity :: PROPERTY_ID, $role_entity->get_id()));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        $role_entity->delete();
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
                        new EqualityCondition(RoleEntity :: PROPERTY_ID, $role_entity->get_id()));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        $role_entity->delete();
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
                        new EqualityCondition(RoleEntity :: PROPERTY_ID, $role_entity->get_id()));

                    $was_merged = $this->merge_entities(
                        $parameters,
                        $condition,
                        $role_entity->get_start_date(),
                        $role_entity->get_end_date());

                    if ($was_merged)
                    {
                        $role_entity->delete();
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
