<?php
namespace application\atlantis\role\entity;

use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

class RoleEntityTableColumnModel extends NewObjectTableColumnModel implements
        NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        if (! $this->get_component()->has_entity())
        {
            $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_ENTITY_TYPE, false));
            $this->add_column(new ObjectTableColumn('entity_name', false));
        }
        if (! $this->get_component()->has_role_id())
        {
            $this->add_column(new ObjectTableColumn(\application\atlantis\role\Role :: PROPERTY_NAME, false));
        }
        if (! $this->get_component()->has_context_id())
        {
            $this->add_column(
                    new ObjectTableColumn(\application\atlantis\context\Context :: PROPERTY_CONTEXT_NAME, false));
        }
        if (! $this->get_component()->has_start_date())
        {
            $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_START_DATE, false));
        }
        if (! $this->get_component()->has_end_date())
        {
            $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_END_DATE, false));
        }
    }
}
