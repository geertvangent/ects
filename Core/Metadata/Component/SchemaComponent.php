<?php
namespace Ehb\Core\Metadata\Component;

use Ehb\Core\Metadata\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

/**
 *
 * @package Ehb\Core\Metadata\Component
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class SchemaComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Ehb\Core\Metadata\Schema\Manager :: context(),
            $this->get_user(),
            $this);
        return $factory->run();
    }
}
