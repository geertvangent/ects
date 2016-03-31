<?php
namespace Ehb\Application\Atlantis\Rights\Component;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Rights\Form\RightsForm;
use Ehb\Application\Atlantis\Rights\Manager;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

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
                Translation :: get(ClassnameUtilities :: getInstance()->getClassnameFromNamespace(self :: class_name()))));

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
            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->get_tabs(self :: ACTION_ACCESS, $form->toHtml())->render();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }
}
