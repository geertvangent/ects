<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublicationCategory;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataManager;

/**
 * $Id: course_group_deleter.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.course_group.component
 */
class DeleterComponent extends Manager
{

    public function run()
    {
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: DELETE_RIGHT))
        {
            throw new NotAllowedException();
        }
        
        if (Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))
        {
            $publication_ids = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        }
        else
        {
            $publication_ids = $_POST[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID];
        }
        
        if (! is_array($publication_ids))
        {
            $publication_ids = array($publication_ids);
        }
        
        foreach ($publication_ids as $pid)
        {
            if ($publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                ContentObjectPublication :: class_name(), 
                $pid))
            {
                $publication->delete();
            }
        }
        
        $ids = Request :: get(self :: PARAM_COURSE_GROUP);
        if ($ids)
        {
            if (! is_array($ids))
                $ids = array($ids);
                
                // Make the course group deletable
            foreach ($ids as $group_id)
            {
                $cg = DataManager :: retrieve_by_id(CourseGroup :: class_name(), $group_id);
                
                if ($cg->get_document_category_id())
                {
                    $cat = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                        ContentObjectPublicationCategory :: class_name(), 
                        $cg->get_document_category_id());
                    
                    if ($cat)
                    {
                        $cat->set_allow_change(1);
                        $cat->delete();
                    }
                }
                if ($cg->get_forum_category_id())
                {
                    
                    $cat = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                        ContentObjectPublicationCategory :: class_name(), 
                        $cg->get_forum_category_id());
                    
                    if ($cat)
                    {
                        $cat->set_allow_change(1);
                        $cat->delete();
                    }
                }
                $cg->delete();
            }
            
            $message = Translation :: get(
                'ObjectDeleted', 
                array('OBJECT' => Translation :: get('CourseGroup')), 
                Utilities :: COMMON_LIBRARIES);
            $this->redirect($message, '', array('course_group' => null, self :: PARAM_ACTION => null));
        }
        // else
        // {
        // Display :: error_message('NoObjectSelected');
        // }
        $this->redirect($message, '', array('course_group' => null, self :: PARAM_ACTION => null));
    }
}
