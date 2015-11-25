<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Menu\TreeMenu\GenericTree;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

class PlatformgroupMenuRenderer extends GenericTree
{
    // **************************************************************************
    // GENERAL CONSTANTS
    // **************************************************************************
    const TREE_NAME = __CLASS__;
    const PATH_TO_XML_FEED = 'group/php/xml_feeds/xml_group_menu_feed.php';
    const ROOT_NODE_CLASS = 'home';
    const NODE_CLASS = 'category';

    // **************************************************************************
    // VARIABLES
    // **************************************************************************
    /**
     * The browser holding additional data.
     *
     * @var PersonalMessengerManager
     */
    private $browser;

    // **************************************************************************
    // CONSTRUCTOR
    // **************************************************************************
    /**
     * Constructor. Creates a new group navigation menu for subscribed groups.
     *
     * @param $browser PersonalMessengerManager The browser
     */
    public function __construct($browser, $root_ids, $fake_root = false)
    {
        $this->browser = $browser;
        parent :: __construct($fake_root, $root_ids);
    }

    // **************************************************************************
    // INHERITED FUNCTIONS
    // **************************************************************************
    /**
     * Returns the url of a node
     *
     * @param $node_id int
     * @return string
     */
    public function get_node_url($node_id)
    {
        $params = array();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_GROUP] = $node_id;
        return $this->browser->get_url($params);
    }

    /**
     * Returns the current node id.
     *
     * @return int
     */
    public function get_current_node_id()
    {
        return Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_GROUP);
    }

    /**
     * Returns the node based on the given node_id.
     *
     * @param $node_id int The id of the node
     * @return Node
     */
    public function get_node($node_id)
    {
        return \Chamilo\Core\Group\Storage\DataManager :: retrieve_by_id(Group :: class_name(), $node_id);
    }

    /**
     * Returns the nodes below the given parent(_id).
     *
     * @param $parent_node_id int The parent id
     * @return GroupResultSet
     */
    public function get_node_children($parent_node_id)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
            new StaticConditionVariable($parent_node_id));

        // fetch groups
        return \Chamilo\Core\Group\Storage\DataManager :: retrieves(
            Group :: class_name(),
            new DataClassRetrievesParameters($condition));
    }

    /**
     * Returns if the node has children.
     *
     * @param $node_id int The node id
     * @return boolean
     */
    public function node_has_children($node_id)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
            new StaticConditionVariable($node_id));

        return (\Chamilo\Core\Group\Storage\DataManager :: count(Group :: class_name(), $condition) > 0);
    }

    /**
     * Returns the url to the xml feed.
     *
     * @return string
     */
    public function get_search_url()
    {
        return Path :: getInstance()->getBasePath(true) . self :: PATH_TO_XML_FEED;
    }

    public function get_url_format()
    {
        $url_format = '?application=weblcms';
        $url_format .= '&' . \Ehb\Application\Avilarts\Manager :: PARAM_COURSE . '=' .
             Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE);
        $url_format .= '&' . \Ehb\Application\Avilarts\Manager :: PARAM_ACTION . '=' .
             Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_ACTION);
        $url_format .= '&' . \Ehb\Application\Avilarts\Manager :: PARAM_TOOL . '=' .
             Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_TOOL);
        $url_format .= '&' . \Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION . '=' .
             Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION);
        $url_format .= '&' . Manager :: PARAM_BROWSER_TYPE . '=' . Request :: get(Manager :: PARAM_BROWSER_TYPE);
        $url_format .= '&' . Manager :: PARAM_TAB . '=' . Request :: get(Manager :: PARAM_TAB);
        $url_format .= '&' . \Ehb\Application\Avilarts\Manager :: PARAM_GROUP . '=%s';
        return $url_format;
    }

    public function get_root_node_class()
    {
        return self :: ROOT_NODE_CLASS;
    }

    public function get_node_class($node)
    {
        return self :: NODE_CLASS;
    }

    public function get_root_node_title()
    {
        return Translation :: get('Course', null, Utilities :: COMMON_LIBRARIES);
    }

    public function get_node_title($node)
    {
        return $node->get_name();
    }

    public function get_node_safe_title($node)
    {
        return $this->get_node_title($node);
    }

    public function get_node_id($node)
    {
        return $node->get_id();
    }

    public function get_node_parent($node)
    {
        return $node->get_parent();
    }
}
