<?php
namespace application\discovery\module\career\implementation\bamaflex;

use common\libraries\AjaxManager;
use common\libraries\JsonAjaxResult;
use common\libraries\AdvancedElementFinderElements;
use common\libraries\AdvancedElementFinderElement;
use common\libraries\Request;
use common\libraries\PatternMatchCondition;
use common\libraries\OrCondition;
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\ObjectTableOrder;
use user\UserDataManager;
use user\User;

class BamaflexAjaxUsersFeed extends AjaxManager
{
    const PARAM_SEARCH_QUERY = 'query';
    const PROPERTY_ELEMENTS = 'elements';
    const PARAM_MODULE_INSTANCE_ID = 'module_instance_id';
    const PARAM_PARAMETERS = 'parameters';

    function required_parameters()
    {
        return array(self :: PARAM_MODULE_INSTANCE_ID, self :: PARAM_USER_ID);
    }

    /**
     * Runs this ajax component
     */
    function run()
    {
        $result = new JsonAjaxResult();

        $elements = $this->get_elements();
        $elements = $elements->as_array();

        $result->set_property(self :: PROPERTY_ELEMENTS, $elements);

        $result->display();
    }

    /**
     * Returns all the elements for this feed
     *
     * @return Array
     */
    private function get_elements()
    {
        $elements = new AdvancedElementFinderElements();

        // Add user category
        $user_category = new AdvancedElementFinderElement('users', 'category', 'Users', 'Users');
        $elements->add_element($user_category);

        $users = $this->retrieve_users();
        if ($users)
        {
            while ($user = $users->next_result())
            {
                $user_category->add_child(
                        new AdvancedElementFinderElement(RightsUserEntity :: ENTITY_TYPE . '_' . $user->get_id(),
                                'type type_user', $user->get_fullname(), $user->get_official_code()));
            }
        }

        return $elements;
    }

    /**
     * Retrieves the users from the course (direct subscribed and group subscribed)
     *
     * @return ResultSet
     */
    private function retrieve_users()
    {
        $udm = UserDataManager :: get_instance();

        $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);

        // Set the conditions for the search query
        if ($search_query && $search_query != '')
        {
            $q = '*' . $search_query . '*';

            $name_conditions[] = new PatternMatchCondition(User :: PROPERTY_USERNAME, $q);
            $name_conditions[] = new PatternMatchCondition(User :: PROPERTY_FIRSTNAME, $q);
            $name_conditions[] = new PatternMatchCondition(User :: PROPERTY_LASTNAME, $q);
            $conditions[] = new OrCondition($name_conditions);
        }

        $targets_entities = Rights :: get_instance()->get_module_targets_entities(
                $this->get_parameter(self :: PARAM_MODULE_INSTANCE_ID), $this->get_parameter(self :: PARAM_PARAMETERS));

        $conditions[] = new InCondition(User :: PROPERTY_ID, $targets_entities[RightsUserEntity :: ENTITY_TYPE]);
        $condition = new AndCondition($conditions);
        return $udm->retrieve_users($condition, null, null, new ObjectTableOrder(User :: PROPERTY_FIRSTNAME));
    }
}
