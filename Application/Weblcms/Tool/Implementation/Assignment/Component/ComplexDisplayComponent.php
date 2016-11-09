<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service\AssignmentDataProvider;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service\AssignmentService;

class ComplexDisplayComponent extends Manager implements DelegateComponent
{

    private $publication;

    public function run()
    {
        $publication_id = Request :: get(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
        $this->set_parameter(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID, $publication_id);

        $this->publication = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(),
            $publication_id);

        if (! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT, $this->publication))
        {
            $this->redirect(
                Translation :: get("NotAllowed", null, Utilities :: COMMON_LIBRARIES),
                true,
                array(),
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID));
        }

        BreadcrumbTrail :: getInstance()->add(new Breadcrumb(null, $this->get_root_content_object()->get_title()));

        $configuration = new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this);
        $configuration->set(
            \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager :: CONFIGURATION_DATA_PROVIDER,
            new AssignmentDataProvider(new AssignmentService(new AssignmentRepository()), $this->publication, $this));

        $factory = new ApplicationFactory(
            \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager :: context(),
            $configuration);

        return $factory->run();
    }

    public function get_root_content_object()
    {
        $this->set_parameter(
            \Chamilo\Core\Repository\ContentObject\LearningPath\Display\Manager :: PARAM_LEARNING_PATH_ITEM_ID,
            Request :: get(
                \Chamilo\Core\Repository\ContentObject\LearningPath\Display\Manager :: PARAM_LEARNING_PATH_ITEM_ID));
        $this->set_parameter(
            \Chamilo\Core\Repository\Display\Manager :: PARAM_COMPLEX_CONTENT_OBJECT_ITEM_ID,
            Request :: get(\Chamilo\Core\Repository\Display\Manager :: PARAM_COMPLEX_CONTENT_OBJECT_ITEM_ID));
        return $this->publication->get_content_object();
    }

    public function get_publication()
    {
        return $this->publication;
    }

    public function get_additional_parameters()
    {
        return array(
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID,
            \Chamilo\Core\Repository\ContentObject\LearningPath\Display\Manager :: PARAM_STEP);
    }
}
