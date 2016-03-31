<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Component;

use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataManager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GeneratorComponent extends Manager
{

    public function run()
    {
        $result = DataManager :: generate_passwords($this->get_course_id());

        if ($result[DataManager :: GENERATION_FAILURES] > 0)
        {
            if ($result[DataManager :: GENERATION_ATTEMPTS] == 1)
            {
                $message = 'PasswordNotGenerated';
            }
            elseif ($result[DataManager :: GENERATION_ATTEMPTS] > $result[DataManager :: GENERATION_FAILURES])
            {
                $message = 'SomePasswordsNotGenerated';
            }
            else
            {
                $message = 'PasswordsNotGenerated';
            }
        }
        else
        {
            if ($result[DataManager :: GENERATION_ATTEMPTS] == 1)
            {
                $message = 'PasswordGenerated';
            }
            else
            {
                $message = 'PasswordsGenerated';
            }
        }

        $this->redirect(
            Translation :: get($message),
            (($result[DataManager :: GENERATION_FAILURES] > 0) ? true : false),
            array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
    }
}
