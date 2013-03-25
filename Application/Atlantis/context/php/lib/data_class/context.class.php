<?php
namespace application\atlantis\context;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\DataClassRetrieveParameters;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\DataClassCountParameters;
use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.context.
 * 
 * @author GillardMagali
 */
class Context extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Context properties
     */
    const PROPERTY_PARENT_ID = 'parent_id';
    const PROPERTY_PARENT_TYPE = 'parent_type';
    const PROPERTY_CONTEXT_ID = 'context_id';
    const PROPERTY_CONTEXT_NAME = 'context_name';
    const PROPERTY_CONTEXT_TYPE = 'context_type';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PARENT_ID;
        $extended_property_names[] = self :: PROPERTY_PARENT_TYPE;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_ID;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_NAME;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_TYPE;
        
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
     * Returns the parent_id of this Context.
     * 
     * @return integer The parent_id.
     */
    public function get_parent_id()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_ID);
    }

    /**
     * Sets the parent_id of this Context.
     * 
     * @param integer $parent_id
     */
    public function set_parent_id($parent_id)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_ID, $parent_id);
    }

    /**
     * Returns the parent_type of this Context.
     * 
     * @return integer The parent_type.
     */
    public function get_parent_type()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_TYPE);
    }

    /**
     * Sets the parent_type of this Context.
     * 
     * @param integer $parent_type
     */
    public function set_parent_type($parent_type)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_TYPE, $parent_type);
    }

    /**
     * Returns the context_id of this Context.
     * 
     * @return integer The context_id.
     */
    public function get_context_id()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_ID);
    }

    /**
     * Sets the context_id of this Context.
     * 
     * @param integer $context_id
     */
    public function set_context_id($context_id)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_ID, $context_id);
    }

    /**
     * Returns the context_name of this Context.
     * 
     * @return text The context_name.
     */
    public function get_context_name()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_NAME);
    }

    /**
     * Sets the context_name of this Context.
     * 
     * @param text $context_name
     */
    public function set_context_name($context_name)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_NAME, $context_name);
    }

    /**
     * Returns the context_type of this Context.
     * 
     * @return integer The context_type.
     */
    public function get_context_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_TYPE);
    }

    /**
     * Sets the context_type of this Context.
     * 
     * @param integer $context_type
     */
    public function set_context_type($context_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_TYPE, $context_type);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function has_children()
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, $this->get_context_id());
        $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, $this->get_context_type());
        $condition = new AndCondition($conditions);
        
        return DataManager :: count(Context :: class_name(), new DataClassCountParameters($condition)) > 0;
    }

    public function is_parent_of($context)
    {
        if ($context->get_parent_id() == $this->get_context_id() && $context->get_parent_type() == $this->get_context_type())
        {
            
            return true;
        }
        elseif ($this->get_id() == 0)
        {
            return true;
        }
        elseif ($context->get_parent_id() != 0)
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, $this->get_context_id());
            $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, $this->get_context_type());
            $condition = new AndCondition($conditions);
            
            $children = DataManager :: retrieves(Context :: class_name(), new DataClassRetrievesParameters($condition));
            while ($child = $children->next_result())
            {
                if ($child->is_parent_of($context))
                {
                    return true;
                }
            }
            return false;
        }
        
        else
        {
            return false;
        }
    }
}
