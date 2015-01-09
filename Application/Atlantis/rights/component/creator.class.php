<?php
namespace application\atlantis\rights;

use libraries\architecture\NotAllowedException;
use libraries\platform\translation\Translation;
use application\atlantis\SessionBreadcrumbs;
use libraries\format\Breadcrumb;
use libraries\utilities\Utilities;

class CreatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(), 
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));
        
        $form = new RightsGroupForm($this, $this->get_url());
        
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
            echo $this->get_tabs(self :: ACTION_CREATE, $form->toHtml())->render();
            $this->display_footer();
        }
    }
}
