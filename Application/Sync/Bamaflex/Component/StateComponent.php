<?php
namespace Ehb\Application\Sync\Bamaflex\Component;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\Group\Storage\DataClass\GroupRelUser;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Storage\DataClass\Property\DataClassProperties;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertiesConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex\BamaflexDataConnector;
use Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex\BamaflexResultSet;
use Ehb\Application\Sync\Bamaflex\Manager;

class StateComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        $years = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'academic_year'));
        $years = explode(',', $years);
        
        foreach ($years as $year)
        {
            $platformGroups = $this->getPlatformGroups($year);
            $bamaFlexGroups = $this->getBamaFlexGroups($year);
            $bamaFlexGroupIdentifiers = array_keys($bamaFlexGroups);
            
            $oldGroups = array();
            $groupIdentifiers = array();
            
            while ($platformGroup = $platformGroups->next_result())
            {
                $bamaFlexId = str_replace('COU_STU_', '', $platformGroup[Group::PROPERTY_CODE]);
                $bamaFlexId = str_replace('COU_OP_', '', $bamaFlexId);
                
                if (! in_array($bamaFlexId, $bamaFlexGroupIdentifiers))
                {
                    $oldGroups[] = array(
                        $platformGroup[Group::PROPERTY_ID], 
                        $platformGroup[Group::PROPERTY_NAME], 
                        $platformGroup[Group::PROPERTY_CODE]);
                    $groupIdentifiers[] = $platformGroup[Group::PROPERTY_ID];
                }
            }
        }
        
        $html = array();
        $html[] = $this->render_header();
        
        if ($this->oldGroupsHaveUsers($groupIdentifiers))
        {
            $query = 'SELECT * FROM group_group_rel_user WHERE group_id IN (' . implode(', ', $groupIdentifiers) . ')';
            
            $tableColumns = array();
            $tableColumns[] = new StaticTableColumn('id');
            $tableColumns[] = new StaticTableColumn('name');
            $tableColumns[] = new StaticTableColumn('code');
            
            $sortableTable = new SortableTableFromArray(
                $oldGroups, 
                $tableColumns, 
                array(), 
                1, 
                20, 
                SORT_ASC, 
                'state_table', 
                false, 
                false, 
                false);
            
            $html[] = $sortableTable->toHtml();
            $html[] = Display::normal_message('<b>Total number of dead groups: ' . count($groupIdentifiers) . '</b>');
            $html[] = Display::normal_message($query);
        }
        else
        {
            $html[] = Display::message(Display::MESSAGE_TYPE_CONFIRM, 'Everything is OK');
        }
        
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    private function oldGroupsHaveUsers($groupIdentifiers)
    {
        $condition = new InCondition(
            new PropertyConditionVariable(GroupRelUser::class_name(), GroupRelUser::PROPERTY_GROUP_ID), 
            $groupIdentifiers);
        
        return DataManager::count(GroupRelUser::class_name(), new DataClassCountParameters($condition));
    }

    private function getBamaFlexGroups($year)
    {
        $query = 'SELECT id, name, name_code, training, faculty FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE year = \'' .
             $year . '\'';
        
        $dataManager = BamaflexDataConnector::getInstance();
        $statement = $dataManager->get_connection()->query($query);
        $resultSet = new BamaflexResultSet($statement);
        
        $bamaFlexCourses = array();
        
        while ($bamaFlexCourse = $resultSet->next_result())
        {
            $bamaFlexCourses[$bamaFlexCourse['id']] = $bamaFlexCourse;
        }
        
        return $bamaFlexCourses;
    }

    private function getPlatformGroups($year)
    {
        $yearGroup = $this->getYearGroup($year);
        
        $conditions = array();
        $conditions[] = new InequalityCondition(
            new PropertyConditionVariable(Group::class_name(), Group::PROPERTY_LEFT_VALUE), 
            InequalityCondition::GREATER_THAN, 
            new StaticConditionVariable($yearGroup->get_left_value()));
        $conditions[] = new InequalityCondition(
            new PropertyConditionVariable(Group::class_name(), Group::PROPERTY_RIGHT_VALUE), 
            InequalityCondition::LESS_THAN, 
            new StaticConditionVariable($yearGroup->get_right_value()));
        
        $patternConditions = array();
        
        $patternConditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group::class_name(), Group::PROPERTY_CODE), 
            '*COU_STU_*');
        $patternConditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group::class_name(), Group::PROPERTY_CODE), 
            '*COU_OP_*');
        
        $conditions[] = new OrCondition($patternConditions);
        
        $condition = new AndCondition($conditions);
        
        return DataManager::records(
            Group::class_name(), 
            new RecordRetrievesParameters(
                new DataClassProperties(array(new PropertiesConditionVariable(Group::class_name()))), 
                $condition));
    }

    /**
     *
     * @param string $year
     * @return \Chamilo\Libraries\Storage\DataClass\DataClass
     */
    private function getYearGroup($year)
    {
        $code = 'AY_' . $year;
        return \Chamilo\Core\Group\Storage\DataManager::retrieve_group_by_code($code);
    }
}