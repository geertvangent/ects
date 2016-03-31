<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Form\ContentObjectPublicationForm;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;

/**
 * Shows the publication update form
 *
 * @author Sven Vanpoucke
 * @package application.lib.weblcms.tool.component
 */
class PublicationUpdaterComponent extends Manager
{

    public function run()
    {
        $pid = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID) ? Request :: get(
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID) : $_POST[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID];

        $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(),
            $pid);

        if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT, $publication))
        {

            $content_object = $publication->get_content_object();

            BreadcrumbTrail :: get_instance()->add(
                new Breadcrumb(
                    $this->get_url(),
                    Translation :: get(
                        'ToolPublicationUpdaterComponent',
                        array('TITLE' => $content_object->get_title()))));

            $course = $this->get_course();
            $is_course_admin = $course->is_course_admin($this->get_user());

            $publication_form = new ContentObjectPublicationForm(
                ContentObjectPublicationForm :: TYPE_UPDATE,
                array($publication),
                $course,
                $this->get_url(),
                $is_course_admin);

            if ($publication_form->validate() || $content_object->get_type() == 'introduction')
            {
                $succes = $publication_form->handle_form_submit();

                $message = htmlentities(
                    Translation :: get(
                        ($succes ? 'ObjectUpdated' : 'ObjectNotUpdated'),
                        array('OBJECT' => Translation :: get('Publication')),
                        Utilities :: COMMON_LIBRARIES),
                    ENT_COMPAT | ENT_HTML401,
                    'UTF-8');

                $show_details = Request :: get('details');
                $tool = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_TOOL);

                $params = array();
                if ($show_details == 1)
                {
                    $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = $pid;
                    $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW;
                }

                // TODO: What does this code do? Is this still valid?
                if ($tool == 'learning_path')
                {
                    $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = null;
                    $params['display_action'] = 'view';
                    $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = Request :: get(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
                }

                if (! isset($show_details) && $tool != 'learning_path')
                {
                    $filter = array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION,
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
                }

                $this->redirect($message, ! $succes, $params, $filter);
            }
            else
            {
                $html = array();

                $html[] = $this->render_header();
                $html[] = $publication_form->toHtml();
                $html[] = $this->render_footer();

                return implode(PHP_EOL, $html);
            }
        }
        else
        {
            $this->redirect(
                Translation :: get("NotAllowed"),
                true,
                array(),
                array(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION,
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID));
        }
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
    }
}
