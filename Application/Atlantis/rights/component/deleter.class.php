<?php
namespace Chamilo\Application\Atlantis\rights\component;

use libraries\architecture\NotAllowedException;
use libraries\utilities\Utilities;
use libraries\platform\translation\Translation;

class DeleterComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        
        $ids = \libraries\platform\Request :: get(self :: PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID);
        $failures = 0;
        
        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }
            
            foreach ($ids as $id)
            {
                $location_entity_right_group = DataManager :: retrieve(
                    RightsLocationEntityRightGroup :: class_name(), 
                    (int) $id);
                
                if (! $location_entity_right_group->delete())
                {
                    $failures ++;
                }
            }
            
            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotDeleted';
                    $parameter = array('OBJECT' => Translation :: get('LocationEntityRightGroup'));
                }
                elseif (count($ids) > $failures)
                {
                    $message = 'SomeObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('LocationEntityRightGroups'));
                }
                else
                {
                    $message = 'ObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('LocationEntityRightGroups'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectDeleted';
                    $parameter = array('OBJECT' => Translation :: get('LocationEntityRightGroup'));
                }
                else
                {
                    $message = 'ObjectsDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('LocationEntityRightGroups'));
                }
            }
            
            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), 
                ($failures ? true : false), 
                array(Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE));
        }
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected', 
                        array('OBJECT' => Translation :: get('LocationEntityRightGroup')), 
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
