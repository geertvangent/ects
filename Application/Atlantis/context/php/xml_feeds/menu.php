<?php
namespace application\atlantis\context;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\AndCondition;
use common\libraries\Request;
use common\libraries\EqualityCondition;
use common\libraries\Authentication;
use common\libraries\ObjectTableOrder;

/**
 * $Id: xml_group_feed.php 224 2009-11-13 14:40:30Z kariboe $
 * 
 * @package group.xml_feeds
 * @author Hans De Bisschop
 * @author Dieter De Neef
 */
require_once dirname(__FILE__) . '/../../../../../common/global.inc.php';

$context_tree = array();

if (Authentication :: is_valid())
{
    $parent_id = Request :: get('parent_id');
    
    $context = DataManager :: retrieve_by_id(Context :: class_name(), (int) $parent_id);
    $conditions = array();
    $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, $context->get_context_id());
    $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, $context->get_context_type());
    $condition = new AndCondition($conditions);
    
    $contexts = DataManager :: retrieves(Context :: class_name(), new DataClassRetrievesParameters($condition))->as_array();
}

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n", '<tree>' . "\n";
echo dump_tree($contexts);
echo '</tree>';

function dump_tree($contexts)
{
    $html = array();
    
    if (contains_results($contexts))
    {
        
        dump_contexts_tree($contexts);
    }
}

function dump_contexts_tree($contexts)
{
    foreach ($contexts as $context)
    {
        $has_children = $context->has_children() ? 1 : 0;
        echo '<leaf id="' . $context->get_id() . '" classes="category" has_children="' . $has_children . '" title="' . htmlspecialchars(
                $context->get_context_name()) . '" description="' . htmlspecialchars($context->get_context_name()) . '"/>' . "\n";
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

?>
