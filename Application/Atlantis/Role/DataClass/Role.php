<?php
namespace Chamilo\Application\Atlantis\Role\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Application\Atlantis\Role\DataManager;

/**
 * application.atlantis.role.
 *
 * @author GillardMagali
 */
class Role extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Role properties
     */
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';

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
     * Returns the name of this Role.
     *
     * @return text The name.
     */
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Role.
     *
     * @param text $name
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * Returns the description of this Role.
     *
     * @return text The description.
     */
    public function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Role.
     *
     * @param text $description
     */
    public function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    public function delete()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_ROLE_ID),
            new StaticConditionVariable($this->get_id()));
        $role_entities = \Chamilo\Application\Atlantis\Role\Entity\Storage\DataManager :: retrieves(
            RoleEntity :: class_name(),
            new DataClassRetrievesParameters($condition));

        while ($role_entity = $role_entities->next_result())
        {
            if (! $role_entity->delete())
            {
                return false;
            }
        }

        $condition = new EqualityCondition(
            new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_ROLE_ID),
            new StaticConditionVariable($this->get_id()));
        $entitlements = \Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataManager :: retrieves(
            Entitlement :: class_name(),
            new DataClassRetrievesParameters($condition));

        while ($entitlement = $entitlements->next_result())
        {
            if (! $entitlement->delete())
            {
                return false;
            }
        }

        return parent :: delete();
    }
}
