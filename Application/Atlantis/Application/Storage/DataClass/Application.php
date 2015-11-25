<?php
namespace Ehb\Application\Atlantis\Application\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right;
use Ehb\Application\Atlantis\Application\Storage\DataManager;

/**
 * application.atlantis.application.
 *
 * @author GillardMagali
 */
class Application extends DataClass
{

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

    public function delete()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Right :: class_name(), Right :: PROPERTY_APPLICATION_ID),
            new StaticConditionVariable($this->get_id()));
        $rights = \Ehb\Application\Atlantis\Application\Right\Storage\DataManager :: retrieves(
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
