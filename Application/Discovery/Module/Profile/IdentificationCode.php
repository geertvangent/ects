<?php
namespace Ehb\Application\Discovery\Module\Profile;

use Chamilo\Libraries\Storage\DataClass\DataClass;

class IdentificationCode extends DataClass
{
    const PROPERTY_TYPE = 'type';
    const PROPERTY_CODE = 'code';
    const TYPE_NATIONAL = 1;
    const TYPE_COMPANY = 2;

    /**
     *
     * @return int
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_type_string()
    {
        switch ($this->get_type())
        {
            case self :: TYPE_NATIONAL :
                return 'NationalId';
                break;
            case self :: TYPE_COMPANY :
                return 'CompanyId';
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function get_code()
    {
        return $this->get_default_property(self :: PROPERTY_CODE);
    }

    /**
     *
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @param string $code
     */
    public function set_code($code)
    {
        $this->set_default_property(self :: PROPERTY_CODE, $code);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_CODE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }
}
