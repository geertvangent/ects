<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\XmlFeeds;

use Chamilo\Libraries\Storage\DataClassRetrievesParameters;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Authentication\Authentication;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Libraries\Storage\StaticConditionVariable;
use Chamilo\Libraries\Storage\OrderBy;

/**
 * $Id: xml_group_feed.php 224 2009-11-13 14:40:30Z kariboe $
 *
 * @package group.xml_feeds
 * @author Hans De Bisschop
 * @author Dieter De Neef
 */
require_once dirname(__FILE__) . '/../../../common/global.inc.php';

$groups_tree = array();

if (Authentication :: is_valid())
{
    $parent_id = Request :: get('parent_id');
    $condition = new EqualityCondition(
        new PropertyConditionVariable(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_PARENT_ID),
        new StaticConditionVariable($parent_id));
    $groups_tree = \Chamilo\Core\Group\DataManager :: retrieves(
        \Chamilo\Core\Group\Group :: class_name(),
        new DataClassRetrievesParameters(
            $condition,
            null,
            null,
            new OrderBy(
                new PropertyConditionVariable(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_NAME))))->as_array();
}

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n", '<tree>' . "\n";
echo dump_tree($groups_tree);
echo '</tree>';

function dump_tree($groups)
{
    $html = array();

    if (contains_results($groups))
    {
        dump_groups_tree($groups);
    }
}

function dump_groups_tree($groups)
{
    foreach ($groups as $group)
    {
        $has_children = $group->has_children() ? 1 : 0;
        echo '<leaf id="' . $group->get_id() . '" classes="category" has_children="' . $has_children . '" title="' .
             htmlspecialchars($group->get_name()) . '" description="' . htmlspecialchars($group->get_name()) . '"/>' .
             "\n";
    }
}

function contains_results($objects)
{
    if (count($objects))
    {
        return true;
    }
    return false;
}
