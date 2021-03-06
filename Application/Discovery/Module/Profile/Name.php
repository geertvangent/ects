<?php
namespace Ehb\Application\Discovery\Module\Profile;

use Chamilo\Libraries\Storage\DataClass\DataClass;

class Name extends DataClass
{
    const PROPERTY_FIRST_NAME = 'first_name';
    const PROPERTY_OTHER_FIRST_NAMES = 'other_first_names';
    const PROPERTY_LAST_NAME = 'last_name';

    /**
     *
     * @return string
     */
    public function get_first_name()
    {
        return $this->get_default_property(self::PROPERTY_FIRST_NAME);
    }

    /**
     *
     * @return string
     */
    public function get_other_first_names()
    {
        return $this->get_default_property(self::PROPERTY_OTHER_FIRST_NAMES);
    }

    /**
     *
     * @return string
     */
    public function get_first_names()
    {
        $names = array();
        
        if ($this->get_first_name())
        {
            $names[] = $this->get_first_name();
        }
        
        if ($this->get_other_first_names())
        {
            $names[] = $this->get_other_first_names();
        }
        
        return implode(' ', $names);
    }

    /**
     *
     * @return string
     */
    public function get_last_name()
    {
        return $this->get_default_property(self::PROPERTY_LAST_NAME);
    }

    /**
     *
     * @param string $first_name
     */
    public function set_first_name($first_name)
    {
        $this->set_default_property(self::PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     *
     * @param string $other_first_names
     */
    public function set_other_first_names($other_first_names)
    {
        $this->set_default_property(self::PROPERTY_OTHER_FIRST_NAMES, $other_first_names);
    }

    /**
     *
     * @param string $last_name
     */
    public function set_last_name($last_name)
    {
        $this->set_default_property(self::PROPERTY_LAST_NAME, $last_name);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_FIRST_NAME;
        $extended_property_names[] = self::PROPERTY_OTHER_FIRST_NAMES;
        $extended_property_names[] = self::PROPERTY_LAST_NAME;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     *
     * @return string
     */
    public function get_full_name()
    {
        return $this->get_first_name() . ' ' . $this->get_last_name();
    }
}
