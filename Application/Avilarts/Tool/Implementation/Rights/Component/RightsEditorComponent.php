<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Rights\Component;


use Ehb\Application\Avilarts\Tool\Implementation\Rights\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class RightsEditorComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        if (! $this->get_course()->is_course_admin($this->get_user()))
        {
            throw new NotAllowedException();
        }

        $factory = new ApplicationFactory(
            \Ehb\Application\Avilarts\Tool\Action\Manager :: context(),
           new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }

    public function get_available_rights($location)
    {
        return \Ehb\Application\Avilarts\Rights\Rights :: get_available_rights($location);
    }

    public function get_additional_parameters()
    {
        array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
