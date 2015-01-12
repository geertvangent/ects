<?php
namespace Chamilo\Application\Atlantis\Rights\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Application\Atlantis\Rights\Manager;
use Chamilo\Application\Atlantis\Rights\Form\RightsForm;

class AccessorComponent extends Manager
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
