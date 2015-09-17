<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Component;

use Ehb\Application\Weblcms\Tool\Implementation\Perception\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\DataManager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Password;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ExporterComponent extends Manager
{

    public function run()
    {
        // Just in case new users were added ...
        $this->generate_passwords();

        $users_ids = DataManager :: get_course_user_ids($this->get_course_id());
        $passwords = DataManager :: retrieves(
            Password :: class_name(),
            new DataClassRetrievesParameters(
                new InCondition(
                    new PropertyConditionVariable(Password :: class_name(), Password :: PROPERTY_USER_ID),
                    $users_ids)));

        $csv_file = Path :: get(SYS_ARCHIVE_PATH) . $this->get_course()->get_visual_code() . '_perception_accounts.csv';
        Filesystem :: create_dir(dirname($csv_file));

        $csv_handle = fopen($csv_file, 'w+');

        while ($password = $passwords->next_result())
        {
            $row = array();

            $row[] = $password->get_user()->get_email();
            $row[] = $password->get_user()->get_official_code();
            $row[] = $password->get_password();
            $row[] = $password->get_user()->get_firstname();
            $row[] = $password->get_user()->get_lastname();
            $row[] = $password->get_user()->get_email();
            $row[] = $this->get_course()->get_visual_code();

            fputcsv($csv_handle, $row, ',', '"');
        }

        fclose($csv_handle);

        Filesystem :: file_send_for_download($csv_file, true);
    }

    public function generate_passwords()
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

            $this->redirect(
                Translation :: get($message),
                (($result[DataManager :: GENERATION_FAILURES] > 0) ? true : false),
                array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
            exit();
        }
    }
}
