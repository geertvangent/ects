<?php
namespace application\atlantis\role\entity;

use rights\PlatformGroupEntity;
use rights\UserEntity;
use common\libraries\Utilities;
use common\libraries\Translation;

class CreatorComponent extends Manager
{

    function run()
    {
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
                            $entity = new RoleEntity();
                            $entity->set_entity_id($entity_id);
                            $entity->set_entity_type(
                                    $entity_type == 'user' ? UserEntity :: ENTITY_TYPE : PlatformGroupEntity :: ENTITY_TYPE);
                            $entity->set_role_id($role);
                            $entity->set_context_id($context);
                            $entity->set_start_date(Utilities :: time_from_datepicker($values['start_date']));
                            $entity->set_end_date(Utilities :: time_from_datepicker($values['end_date']));
                            
                            if (! $entity->create())
                            {
                                $failures ++;
                            }
                        }
                    }
                }
            }
            
            $count = (count($values['entity'][UserEntity :: ENTITY_TYPE]) + count(
                    $values['entity'][PlatformGroupEntity :: ENTITY_TYPE])) * count($values['role']) * count(
                    $values['context']) * count($values['start_date']) * count($values['end_date']);
            var_dump($count);
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
            
            $this->redirect(Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), 
                    ($failures ? true : false), array(Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE));
        }
        else
        {
            $this->display_header();
            echo $form->toHtml();
            $this->display_footer();
        }
    }
}
?>