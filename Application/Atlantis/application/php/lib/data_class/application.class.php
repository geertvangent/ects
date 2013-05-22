<?php
namespace application\atlantis\application;

use common\libraries\Utilities;
use common\libraries\DataClass;
use common\libraries\EqualityCondition;
use application\atlantis\application\right\Right;
use common\libraries\DataClassRetrievesParameters;

/**
 * application.atlantis.application.
 *
 * @author GillardMagali
 */
class Application extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Application properties
     */
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_URL = 'url';
    const PROPERTY_CODE = 'code';

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_URL;
        $extended_property_names[] = self :: PROPERTY_CODE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the name of this Application.
     *
     * @return text The name.
     */
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Application.
     *
     * @param text $name
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * Returns the description of this Application.
     *
     * @return text The description.
     */
    public function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Application.
     *
     * @param text $description
     */
    public function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     * Returns the url of this Application.
     *
     * @return text The url.
     */
    public function get_url()
    {
        return $this->get_default_property(self :: PROPERTY_URL);
    }

    /**
     * Sets the url of this Application.
     *
     * @param text $url
     */
    public function set_url($url)
    {
        $this->set_default_property(self :: PROPERTY_URL, $url);
    }

    public function get_code()
    {
        return $this->get_default_property(self :: PROPERTY_CODE);
    }

    public function set_code($code)
    {
        $this->set_default_property(self :: PROPERTY_CODE, $code);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function delete()
    {
        $condition = new EqualityCondition(Right :: PROPERTY_APPLICATION_ID, $this->get_id());
        $rights = \application\atlantis\application\right\DataManager :: retrieves(
            Right :: class_name(),
            new DataClassRetrievesParameters($condition));

        while ($right = $rights->next_result())
        {
            if (! $right->delete())
            {
                return false;
            }
        }

        return parent :: delete();
    }
}
