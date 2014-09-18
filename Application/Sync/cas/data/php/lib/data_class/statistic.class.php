<?php
namespace application\ehb_sync\cas\data;

use libraries\DataClass;

/**
 *
 * @author Hans De Bisschop
 */
class Statistic extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_USER = 'user';
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_APPLICATION_ID = 'application_id';
    const PROPERTY_ACTION_ID = 'action_id';
    const PROPERTY_DATE = 'date';
    const ACTION_AUTHENTICATION_SUCCESS = 1;
    const ACTION_TICKET_GRANTING_TICKET_CREATED = 2;
    const ACTION_TICKET_GRANTING_TICKET_DESTROYED = 3;
    const ACTION_SERVICE_TICKET_CREATED = 4;
    const ACTION_SERVICE_TICKET_VALIDATED = 5;
    const ACTION_AUTHENTICATION_FAILED = 6;
    const ACTION_TICKET_GRANTING_TICKET_NOT_CREATED = 7;
    const ACTION_SERVICE_TICKET_NOT_CREATED = 8;
    const ACTION_SERVICE_TICKET_VALIDATE_FAILED = 9;
    CONST APPLICATION_DOKEOS = 1;
    CONST APPLICATION_PORTAAL = 2;
    CONST APPLICATION_STUDENTENMAIL = 3;
    CONST APPLICATION_PERSONEEL = 4;
    CONST APPLICATION_BIBLIOTHEEK = 5;
    CONST APPLICATION_ENQUETE = 6;
    CONST APPLICATION_CURSUSDIENST = 7;
    CONST APPLICATION_HELPDESK = 8;
    CONST APPLICATION_IBAMAFLEX = 9;
    CONST APPLICATION_DATA = 10;
    CONST APPLICATION_DISCOVERY = 11;
    CONST APPLICATION_DESIDERIUS = 12;
    CONST APPLICATION_EHBRIEF = 13;
    CONST APPLICATION_IDIOM_TEACHER = 14;
    CONST APPLICATION_IWT_INTRANET = 15;
    CONST APPLICATION_IWT_CISCO = 16;
    CONST APPLICATION_KIES = 17;
    CONST APPLICATION_KWALITEIT = 18;
    CONST APPLICATION_MOBILITY_ONLINE = 19;
    CONST APPLICATION_IWT_FABLAB_XL = 20;
    CONST APPLICATION_RITS_BOTTELARIJ_INTRANET = 21;
    CONST APPLICATION_TEST = 22;
    CONST APPLICATION_CAS = 23;
    CONST APPLICATION_IWT_MOBILE_APPS = 24;
    CONST APPLICATION_STAGEPLANNER = 25;
    CONST APPLICATION_OEFENING_SVEN_VANHOECKE = 26;
    CONST APPLICATION_WEBMAIL = 27;

    /**
     * Get the default properties
     * 
     * @return array The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_USER, 
                self :: PROPERTY_PERSON_ID, 
                self :: PROPERTY_APPLICATION_ID, 
                self :: PROPERTY_ACTION_ID, 
                self :: PROPERTY_DATE));
    }

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function get_user()
    {
        return $this->get_default_property(self :: PROPERTY_USER);
    }

    /**
     *
     * @return string
     */
    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     *
     * @return string
     */
    public function get_application_id()
    {
        return $this->get_default_property(self :: PROPERTY_APPLICATION_ID);
    }

    /**
     *
     * @return string
     */
    public function get_action_id()
    {
        return $this->get_default_property(self :: PROPERTY_ACTION_ID);
    }

    /**
     *
     * @return string
     */
    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     *
     * @param string $user
     */
    public function set_user($user)
    {
        $this->set_default_property(self :: PROPERTY_USER, $user);
    }

    /**
     *
     * @param string $person_id
     */
    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     *
     * @param string $application_id
     */
    public function set_application_id($application_id)
    {
        $this->set_default_property(self :: PROPERTY_APPLICATION_ID, $application_id);
    }

    /**
     *
     * @param string $action_id
     */
    public function set_action_id($action_id)
    {
        $this->set_default_property(self :: PROPERTY_ACTION_ID, $action_id);
    }

    /**
     *
     * @param string $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    public static function application_string_to_id($application)
    {
        switch ($application)
        {
            case strpos($application, 'www.google.com') !== false :
                return self :: APPLICATION_STUDENTENMAIL;
                break;
            case strpos($application, 'dokeos.ehb.be') !== false :
                return self :: APPLICATION_DOKEOS;
                break;
            case strpos($application, 'portaal.ehb.be') !== false :
                return self :: APPLICATION_PORTAAL;
                break;
            case strpos($application, 'personeel.ehb.be') !== false :
                return self :: APPLICATION_PERSONEEL;
                break;
            case strpos($application, 'bibliotheek.ehb.be') !== false :
                return self :: APPLICATION_BIBLIOTHEEK;
                break;
            case strpos($application, 'enquete.ehb.be') !== false :
                return self :: APPLICATION_ENQUETE;
                break;
            case strpos($application, 'cursusdienst.ehb.be') !== false :
                return self :: APPLICATION_CURSUSDIENST;
                break;
            case strpos($application, 'helpdesk.ehb.be') !== false :
                return self :: APPLICATION_HELPDESK;
                break;
            case strpos($application, 'ibamaflex.ehb.be') !== false :
                return self :: APPLICATION_IBAMAFLEX;
                break;
            case strpos($application, 'desiderius.ehb.be') !== false :
                return self :: APPLICATION_DESIDERIUS;
                break;
            case strpos($application, 'discovery.ehb.be') !== false :
                return self :: APPLICATION_DISCOVERY;
                break;
            case strpos($application, 'data.ehb.be') !== false :
                return self :: APPLICATION_DATA;
                break;
            case strpos($application, 'ehbrief.ehb.be') !== false :
                return self :: APPLICATION_EHBRIEF;
                break;
            case strpos($application, 'idiomteacher.ehb.be') !== false :
                return self :: APPLICATION_IDIOM_TEACHER;
                break;
            case strpos($application, 'iwt3.ehb.be/intranet') !== false :
                return self :: APPLICATION_IWT_INTRANET;
                break;
            case strpos($application, 'iwtcisco.ehb.be') !== false :
                return self :: APPLICATION_IWT_CISCO;
                break;
            case strpos($application, 'kies.ehb.be') !== false :
                return self :: APPLICATION_KIES;
                break;
            case strpos($application, 'kwaliteit.ehb.be') !== false :
                return self :: APPLICATION_KWALITEIT;
                break;
            case strpos($application, 'wien.sop.co.at/europe') !== false :
                return self :: APPLICATION_MOBILITY_ONLINE;
                break;
            case strpos($application, 'fablabxl.be') !== false :
                return self :: APPLICATION_IWT_FABLAB_XL;
                break;
            case strpos($application, 'bottelarij.rits.be/intranet') !== false :
                return self :: APPLICATION_RITS_BOTTELARIJ_INTRANET;
                break;
            case strpos($application, 'cas.ehb.be/services') !== false :
                return self :: APPLICATION_CAS;
                break;
            case strpos($application, 'iwt3.ehb.be/mobapp') !== false :
                return self :: APPLICATION_IWT_MOBILE_APPS;
                break;
            case strpos($application, 'stage.ehb.be') !== false :
                return self :: APPLICATION_STAGEPLANNER;
                break;
            case strpos($application, 'svennas.be') !== false :
                return self :: APPLICATION_OEFENING_SVEN_VANHOECKE;
                break;
            case strpos($application, 'webmail.ehb.be') !== false :
                return self :: APPLICATION_WEBMAIL;
                break;
            case strpos($application, 'localhost') !== false :
                return self :: APPLICATION_TEST;
                break;
            default :
                return null;
        }
    }

    public static function get_table_name()
    {
        return 'statistics';
    }
}
