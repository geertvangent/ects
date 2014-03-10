<?php
namespace application\atlantis\context;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\Translation;
use common\libraries\AndCondition;
use common\libraries\Utilities;
use common\libraries\Path;
use common\libraries\EqualityCondition;
use common\libraries\ObjectTableOrder;
use common\libraries\OptionsMenuRenderer;
use common\libraries\TreeMenuRenderer;
use HTML_Menu;
use HTML_Menu_ArrayRenderer;

/**
 * $Id: group_menu.class.php 224 2009-11-13 14:40:30Z kariboe $
 *
 * @package group.lib
 */
/**
 * This class provides a navigation menu to allow a user to browse through categories of courses.
 *
 * @author Bart Mollet
 */
class Menu extends HTML_Menu
{
    const TREE_NAME = __CLASS__;

    /**
     * The string passed to sprintf() to format category URLs
     */
    private $urlFmt;

    /**
     * The array renderer used to determine the breadcrumbs.
     */
    private $array_renderer;

    private $include_root;

    private $current_category;

    private $show_complete_tree;

    private $hide_current_category;

    /**
     * Creates a new category navigation menu.
     *
     * @param int $owner The ID of the owner of the categories to provide in this menu.
     * @param int $current_category The ID of the current category in the menu.
     * @param string $url_format The format to use for the URL of a category. Passed to sprintf(). Defaults to the
     *            string "?category=%s".
     * @param array $extra_items An array of extra tree items, added to the root.
     */
    function __construct($current_category, $url_format = '?application=atlantis&go=context&context_id=%s', $include_root = true, $show_complete_tree = false,
        $hide_current_category = false)
    {
        $this->include_root = $include_root;
        $this->show_complete_tree = $show_complete_tree;
        $this->hide_current_category = $hide_current_category;

        if ($current_category == '0' || is_null($current_category))
        {
            // $conditions = array();
            // $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, 0);
            // $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, 0);
            // $condition = new AndCondition($conditions);

            // $context = DataManager :: retrieve(Context :: class_name(), new DataClassRetrieveParameters($condition));

            $context = new Context();
            $context->set_id(0);
            $context->set_context_type(0);
            $context->set_context_id(0);
            $context->set_context_name(Translation :: get('Root'));
            $this->current_category = $context;
        }
        else
        {
            $context = DataManager :: retrieve_by_id(Context :: class_name(), (int) $current_category);
            $this->current_category = $context;
        }

        $this->urlFmt = $url_format;
        $menu = $this->get_menu();
        parent :: __construct($menu);
        $this->array_renderer = new HTML_Menu_ArrayRenderer();
        $this->forceCurrentUrl($this->get_url($this->current_category->get_id()));
    }

    function get_menu()
    {
        $include_root = $this->include_root;

        $context = new Context();
        $context->set_id(0);
        $context->set_context_type(0);
        $context->set_context_id(0);
        $context->set_context_name(Translation :: get('Root'));

        if (! $include_root)
        {
            return $this->get_menu_items($context->get_context_id(), $context->get_context_type());
        }
        else
        {
            $menu = array();

            $menu_item = array();
            $menu_item['title'] = $context->get_context_name();
            $menu_item['url'] = $this->get_url($context->get_id());

            $sub_menu_items = $this->get_menu_items($context->get_context_id(), $context->get_context_type());
            if (count($sub_menu_items) > 0)
            {
                $menu_item['sub'] = $sub_menu_items;
            }

            $menu_item['class'] = 'home';
            $menu_item[OptionsMenuRenderer :: KEY_ID] = $context->get_id();
            $menu[$context->get_id()] = $menu_item;
            return $menu;
        }
    }

    /**
     * Returns the menu items.
     *
     * @param array $extra_items An array of extra tree items, added to the root.
     * @return array An array with all menu items. The structure of this array is the structure needed by
     *         PEAR::HTML_Menu, on which this class is based.
     */
    private function get_menu_items($parent_id = 0, $parent_type = 0)
    {
        $current_category = $this->current_category;

        $show_complete_tree = $this->show_complete_tree;
        $hide_current_category = $this->hide_current_category;

        $conditions = array();
        $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, $parent_id);
        $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, $parent_type);
        $condition = new AndCondition($conditions);

        $contexts = DataManager :: retrieves(
            Context :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(new ObjectTableOrder(Context :: PROPERTY_CONTEXT_NAME))));
       
        while ($context = $contexts->next_result())
        {
            $context_id = $context->get_id();

            if (! ($context_id == $current_category->get_id() && $hide_current_category))
            {
                $menu_item = array();
                $menu_item['title'] = $context->get_context_name();
                $menu_item['url'] = $this->get_url($context->get_id());

                if ($context->is_parent_of($current_category) || $context->get_id() == $current_category->get_id() ||
                     $show_complete_tree)
                {
                    if ($context->has_children())
                    {
                        $menu_item['sub'] = $this->get_menu_items(
                            $context->get_context_id(),
                            $context->get_context_type());
                    }
                }
                else
                {
                    if ($context->has_children())
                    {
                        $menu_item['children'] = 'expand';
                    }
                }

                $menu_item['class'] = 'category';
                $menu_item[OptionsMenuRenderer :: KEY_ID] = $context->get_id();
                $menu[$context->get_id()] = $menu_item;
            }
        }

        return $menu;
    }

    /**
     * Gets the URL of a given category
     *
     * @param int $category The id of the category
     * @return string The requested URL
     */
    function get_url($context)
    {
        // TODO: Put another class in charge of the htmlentities() invocation
        return htmlentities(sprintf($this->urlFmt, $context));
    }

    private function get_home_url($category)
    {
        // TODO: Put another class in charge of the htmlentities() invocation
        return htmlentities(str_replace('&context_id=%s', '', $this->urlFmt));
    }

    /**
     * Get the breadcrumbs which lead to the current category.
     *
     * @return array The breadcrumbs.
     */
    function get_breadcrumbs()
    {
        $this->render($this->array_renderer, 'urhere');
        $breadcrumbs = $this->array_renderer->toArray();
        foreach ($breadcrumbs as $crumb)
        {
            $crumb['name'] = $crumb['title'];
            unset($crumb['title']);
        }
        return $breadcrumbs;
    }

    /**
     * Renders the menu as a tree
     *
     * @return string The HTML formatted tree
     */
    function render_as_tree()
    {
        $renderer = new TreeMenuRenderer(
            $this->get_tree_name(),
            Path :: get(WEB_PATH) . 'application/atlantis/context/php/xml_feeds/menu.php',
            $this->urlFmt);
        $this->render($renderer, 'sitemap');
        return $renderer->toHTML();
    }

    static function get_tree_name()
    {
        return Utilities :: get_classname_from_namespace(self :: TREE_NAME, true);
    }
}
