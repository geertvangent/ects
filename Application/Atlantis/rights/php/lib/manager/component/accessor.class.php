<?php
namespace application\atlantis\rights;

use common\libraries\NotAllowedException;
use common\libraries\Translation;

class AccessorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        
        $form = new RightsForm($this, $this->get_url());
        
        if ($form->validate())
        {
            $success = $form->set_rights();
            
            $this->redirect(
                Translation :: get($success ? 'AccessRightsSaved' : 'AccessRightsNotSaved'), 
                ($success ? false : true));
        }
        else
        {
            $this->display_header();
            echo $this->get_tabs(self :: ACTION_ACCESS, $form->toHtml())->render();
            $this->display_footer();
        }
    }
}
