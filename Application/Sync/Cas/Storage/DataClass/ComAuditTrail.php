<?php
namespace Chamilo\Application\EhbSync\Cas\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Application\EhbSync\Cas\Storage\DataManager;

/**
 *
 * @author Hans De Bisschop
 */
class ComAuditTrail extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_USER = 'AUD_USER';
    const PROPERTY_CLIENT_IP = 'AUD_CLIENT_IP';
    const PROPERTY_SERVER_IP = 'AUD_SERVER_IP';
    const PROPERTY_RESOURCE = 'AUD_RESOURCE';
    const PROPERTY_ACTION = 'AUD_ACTION';
    const PROPERTY_APPLICATION = 'APPLIC_CD';
    const PROPERTY_DATE = 'AUD_DATE';

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
                self :: PROPERTY_CLIENT_IP,
                self :: PROPERTY_SERVER_IP,
                self :: PROPERTY_RESOURCE,
                self :: PROPERTY_ACTION,
                self :: PROPERTY_APPLICATION,
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
    public function get_client_ip()
    {
        return $this->get_default_property(self :: PROPERTY_CLIENT_IP);
    }

    /**
     *
     * @return string
     */
    public function get_server_ip()
    {
        return $this->get_default_property(self :: PROPERTY_SERVER_IP);
    }

    /**
     *
     * @return string
     */
    public function get_resource()
    {
        return $this->get_default_property(self :: PROPERTY_RESOURCE);
    }

    /**
     *
     * @return string
     */
    public function get_action()
    {
        return $this->get_default_property(self :: PROPERTY_ACTION);
    }

    /**
     *
     * @return string
     */
    public function get_application()
    {
        return $this->get_default_property(self :: PROPERTY_APPLICATION);
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
     * @param string $client_ip
     */
    public function set_client_ip($client_ip)
    {
        $this->set_default_property(self :: PROPERTY_CLIENT_IP, $client_ip);
    }

    /**
     *
     * @param string $server_ip
     */
    public function set_server_ip($server_ip)
    {
        $this->set_default_property(self :: PROPERTY_SERVER_IP, $server_ip);
    }

    /**
     *
     * @param string $resource
     */
    public function set_resource($resource)
    {
        $this->set_default_property(self :: PROPERTY_RESOURCE, $resource);
    }

    /**
     *
     * @param string $action
     */
    public function set_action($action)
    {
        $this->set_default_property(self :: PROPERTY_ACTION, $action);
    }

    /**
     *
     * @param string $application
     */
    public function set_application($application)
    {
        $this->set_default_property(self :: PROPERTY_APPLICATION, $application);
    }

    /**
     *
     * @param string $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_date, $date);
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return strtoupper(parent :: get_table_name());
    }
}
