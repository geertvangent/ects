<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;

/**
 * Tool component which can be used to share content objects
 */
class ShareContentObjectsComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $publication_ids = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);

        if (! empty($publication_ids))
        {
            if (! is_array($publication_ids))
            {
                $publication_ids = array($publication_ids);
            }

            $condition = new InCondition(
                new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(),
                    ContentObjectPublication :: PROPERTY_ID),
                $publication_ids);
            $publications = \Ehb\Application\Avilarts\Storage\DataManager :: retrieves(
                ContentObjectPublication :: class_name(),
                new DataClassRetrievesParameters($condition));

            $content_objects = array();

            while ($publication = $publications->next_result())
            {
                $content_objects[] = $publication->get_content_object();
            }

            if (count($content_objects) == 1)
            {
                BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb(
                        $this->get_url(),
                        Translation :: get('ShareContentObject', array('TITLE' => $content_objects[0]->get_title()))));
            }
            else
            {
                BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb($this->get_url(), Translation :: get('ShareContentObjects')));
            }

            $factory = new ApplicationFactory(
                \Chamilo\Core\Repository\Share\Manager :: context(),
                new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
            $component = $factory->getComponent();
            $component->set_content_objects($content_objects);
            return $component->run();
        }
        else
        {
            return $this->display_warning_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected',
                        array('OBJECT' => Translation :: get('ContentObject')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('tool_share_content_objects');
    }
}
