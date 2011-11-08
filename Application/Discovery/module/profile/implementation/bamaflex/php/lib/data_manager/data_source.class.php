<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\profile\Photo;
use application\discovery\module\profile\Communication;
use application\discovery\module\profile\Email;
use application\discovery\module\profile\IdentificationCode;
use application\discovery\module\profile\Name;
use application\discovery\module\profile\DataManagerInterface;

use MDB2_Error;
use stdClass;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{

    /**
     * @param int $id
     * @return \application\discovery\module\profile\implementation\bamaflex\Profile|boolean
     */
    function retrieve_profile($parameters)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($parameters->get_user_id());
        
        $official_code = $user->get_official_code();
        
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_basic] WHERE id = ' . $official_code;
        
        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();
        
        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
            
            $name = new Name();
            $name->set_first_name($this->convert_to_utf8($object->first_name));
            $name->set_other_first_names($this->convert_to_utf8($object->other_first_names));
            $name->set_last_name($this->convert_to_utf8($object->last_name));
            
            $birth = new Birth();
            $birth->set_date(strtotime($object->birth_date));
            $birth->set_place($this->convert_to_utf8($object->birth_place));
            $birth->set_country($this->convert_to_utf8($object->birth_country));
            
            $national_id = new IdentificationCode();
            $national_id->set_type(IdentificationCode :: TYPE_NATIONAL);
            $national_id->set_code($this->convert_to_utf8($object->national_id));
            
            $company_id = new IdentificationCode();
            $company_id->set_type(IdentificationCode :: TYPE_COMPANY);
            $company_id->set_code($this->convert_to_utf8($object->company_id));
            
            $profile = new Profile();
            $profile->set_title($name->get_full_name());
            $profile->set_name($name);
            $profile->add_identification_code($national_id);
            $profile->add_identification_code($company_id);
            $profile->set_email($this->retrieve_emails($official_code));
            $profile->set_communication($this->retrieve_communications($official_code));
            $profile->set_language($this->convert_to_utf8($object->language));
            $profile->set_photo($this->retrieve_photo($official_code));
            $profile->set_first_university($object->first_university);
            $profile->set_first_university_college($object->first_university_college);
            
            $profile->set_gender($this->convert_to_utf8($object->gender));
            $profile->set_birth($birth);
            $profile->set_address($this->retrieve_addresses($official_code));
            $profile->set_nationality($this->retrieve_nationalities($official_code));
            $profile->set_previous_college($this->retrieve_previous_college($official_code));
            $profile->set_previous_university($this->retrieve_previous_university($official_code));
            
            return $profile;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\profile\Email
     */
    private function retrieve_emails($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_email] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        $emails = array();
        
        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $email = new Email();
                $email->set_address($this->convert_to_utf8($result->address));
                $email->set_type($result->type);
                $emails[] = $email;
            }
        }
        
        return $emails;
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\profile\Communication
     */
    private function retrieve_communications($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_communication] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        $communications = array();
        
        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $communication = new Communication();
                $communication->set_number($this->convert_to_utf8($result->number));
                $communication->set_type($result->type);
                $communication->set_device($result->device);
                $communications[] = $communication;
            }
        }
        
        return $communications;
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\profile\implementation\bamaflex\Address
     */
    private function retrieve_addresses($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_address] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        $addresses = array();
        
        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $address = new Address();
                $address->set_type($result->type);
                $address->set_country($this->convert_to_utf8($result->country));
                $address->set_street($this->convert_to_utf8($result->street));
                $address->set_number($this->convert_to_utf8($result->number));
                $address->set_box($this->convert_to_utf8($result->box));
                $address->set_room($this->convert_to_utf8($result->room));
                $address->set_city($this->convert_to_utf8($result->city));
                $address->set_city_zip_code($this->convert_to_utf8($result->city_zip_code));
                $address->set_subcity($this->convert_to_utf8($result->subcity));
                $address->set_subcity_zip_code($this->convert_to_utf8($result->subcity_zip_code));
                $address->set_region($this->convert_to_utf8($result->region));
                $addresses[] = $address;
            }
        }
        
        return $addresses;
    }

    /**
     * @param int $id
     * @return \application\discovery\module\profile\Photo|boolean
     */
    private function retrieve_photo($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_photo] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();
        
        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
            
            $photo = new Photo();
            $photo->set_mime_type('image/jpeg');
            $photo->set_data(base64_encode($object->photo));
            
            return $photo;
        }
        else
        {
            return false;
        }
    }

    private function retrieve_previous_college($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_previous_college] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();
        
        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
            if ($object instanceof stdClass)
            {
                $previous_college = new PreviousCollege();
                $previous_college->set_date($object->date);
                $previous_college->set_degree_id($object->degree_id);
                $previous_college->set_degree_name($this->convert_to_utf8($object->degree_name));
                $previous_college->set_degree_type($this->convert_to_utf8($object->degree_type));
                $previous_college->set_school_id($object->school_id);
                $previous_college->set_school_name($this->convert_to_utf8($object->school_name));
                $previous_college->set_school_city($this->convert_to_utf8($object->school_city));
                $previous_college->set_training_id($object->training_id);
                $previous_college->set_training_name($this->convert_to_utf8($object->training_name));
                $previous_college->set_country_id($object->country_id);
                $previous_college->set_country_name($this->convert_to_utf8($object->country_name));
                $previous_college->set_info($this->convert_to_utf8($object->info));
                
                return $previous_college;
            }
        }
        return false;
    }

    private function retrieve_previous_university($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_previous_university] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();
        
        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
            
            if ($object instanceof stdClass)
            {
                $previous_university = new PreviousUniversity();
                $previous_university->set_date($object->date);
                $previous_university->set_type($this->convert_to_utf8($object->type));
                $previous_university->set_school_id($object->school_id);
                $previous_university->set_school_name($this->convert_to_utf8($object->school_name));
                $previous_university->set_school_city($this->convert_to_utf8($object->school_city));
                $previous_university->set_training_id($object->training_id);
                $previous_university->set_training_name($this->convert_to_utf8($object->training_name));
                $previous_university->set_country_id($object->country_id);
                $previous_university->set_country_name($this->convert_to_utf8($object->country_name));
                $previous_university->set_info($this->convert_to_utf8($object->info));
                
                return $previous_university;
            }
        }
        
        return false;
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\profile\implementation\bamaflex\Nationalities
     */
    private function retrieve_nationalities($id)
    {
        $query = 'SELECT * FROM [dbo].[v_discovery_profile_nationality] WHERE id = ' . $id;
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        $nationalities = array();
        
        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $nationality = new Nationality();
                $nationality->set_type($result->type);
                $nationality->set_nationality($this->convert_to_utf8($result->nationality));
                $nationalities[] = $nationality;
            }
        }
        
        return $nationalities;
    }

}
?>