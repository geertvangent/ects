<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;


use Chamilo\Core\Repository\Form\ContentObjectForm;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;

/**
 * $Id: edit.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.component
 * @deprecated Use the content_object_updater and publication_updater
 */
class UpdaterComponent extends Manager
{

    public function run()
    {
        $pid = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID) ? Request :: get(
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID) : $_POST[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID];
        if (is_null($pid))
        {
            $this->redirect(
                Translation :: get('NoObjectSelected', null, Utilities :: COMMON_LIBRARIES),
                '',
                array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => null, 'tool_action' => null));
        }

        $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(),
            $pid);

        if (is_null($publication))
        {
            $this->redirect(
                Translation :: get('NoObjectSelected', null, Utilities :: COMMON_LIBRARIES),
                '',
                array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => null, 'tool_action' => null));
        }

        if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT, $publication))
        {
            $content_object = $publication->get_content_object();

            $form = ContentObjectForm :: factory(
                ContentObjectForm :: TYPE_EDIT,
                $content_object,
                'edit',
                'post',
                $this->get_url(array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => $pid)));

            if ($form->validate() || Request :: get('validated'))
            {
                if (! Request :: get('validated'))
                {
                    $form->update_content_object();
                    if ($form->is_version())
                    {
                        $publication->set_content_object_id($content_object->get_latest_version()->get_id());
                        $publication->update();
                        $message = htmlentities(
                            Translation :: get(
                                'ObjectUpdated',
                                array('OBJECT' => Translation :: get('Publication')),
                                Utilities :: COMMON_LIBRARIES));
                        $this->redirect($message, false);
                    }
                }

                $this->redirect($message, false);
            }
            else
            {
                $html = array();

                $html[] = $this->render_header();
                $html[] = $form->toHtml();
                $html[] = $this->render_footer();

                return implode(PHP_EOL, $html);
            }
        }
        else
        {
            $this->redirect(
                Translation :: get("NotAllowed"),
                '',
                array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => null, 'tool_action' => null));
        }
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('courses general');
    }
}
