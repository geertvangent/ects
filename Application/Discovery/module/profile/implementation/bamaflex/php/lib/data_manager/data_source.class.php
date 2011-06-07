<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\module\profile\IdentificationCode;
use application\discovery\module\profile\Name;
use application\discovery\module\profile\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{

    function retrieve_profile($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_basic] WHERE p_persoon = ' . $id;

        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();

        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);

            $name = new Name();
            $name->set_first_name($object->voornaam);
            $name->set_other_first_names($object->voornaam_2);
            $name->set_last_name($object->naam);

            $birth = new Birth();
            $birth->set_date(strtotime($object->geboordat));
            $birth->set_place($object->geboorpl);
            $birth->set_country($object->land);

            $national_id = new IdentificationCode();
            $national_id->set_type(IdentificationCode :: TYPE_NATIONAL);
            $national_id->set_code($object->rijksregnr);

            $company_id = new IdentificationCode();
            $company_id->set_type(IdentificationCode :: TYPE_COMPANY);
            $company_id->set_code($object->stamnr);

            $profile = new Profile();
            $profile->set_name($name);
            $profile->add_identification_code($national_id);
            $profile->add_identification_code($company_id);
            //$profile->add_email($email);
            //$profile->add_communication($communication);
            $profile->set_language($object->taal);
            //$profile->set_picture($picture);

            $profile->set_gender($object->geslacht);
            $profile->set_birth($birth);
            //$profile->add_address($address);

            return $profile;
        }
        else
        {
            return false;
        }
    }
}
?>