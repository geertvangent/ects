<?php
namespace Ehb\Core\Metadata\Vocabulary\Component;

use Ehb\Core\Metadata\Vocabulary\Manager;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Ehb\Core\Metadata\Vocabulary\Table\Vocabulary\VocabularyTable;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Architecture\Exceptions\NoObjectSelectedException;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Ehb\Core\Metadata\Vocabulary\Entity\UserEntity;
use Ehb\Core\Metadata\Vocabulary\Entity\PlatformGroupEntity;
use Chamilo\Libraries\File\Redirect;

class BrowserComponent extends Manager implements TableSupport
{

    /**
     * The action bar of this browser
     *
     * @var ActionBarRenderer
     */
    private $action_bar;

    /**
     * Executes this controller
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        if (! $this->getElementId())
        {
            throw new NoObjectSelectedException(Translation :: get('Element', null, 'Ehb\Core\Metadata\Element'));
        }

        $html = array();

        $html[] = $this->render_header();
        $html[] = $this->as_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    /**
     * Renders this components output as html
     */
    public function as_html()
    {
        $this->action_bar = $this->get_action_bar();

        $tabs = new DynamicVisualTabsRenderer('vocabulary');

        $tabs->add_tab(
            new DynamicVisualTab(
                0,
                Translation :: get('Everyone', null, self :: package()),
                null,
                $this->get_url(
                    array(
                        \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getElementId(),
                        self :: PARAM_ENTITY_TYPE => 0)),
                $this->getEntityType() == 0));

        $userEntity = new UserEntity();

        $tabs->add_tab(
            new DynamicVisualTab(
                UserEntity :: ENTITY_TYPE,
                $userEntity->get_entity_translated_name(),
                $userEntity->get_entity_icon(),
                $this->get_url(
                    array(
                        \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getElementId(),
                        self :: PARAM_ENTITY_TYPE => UserEntity :: ENTITY_TYPE)),
                $this->getEntityType() == UserEntity :: ENTITY_TYPE));

        $groupEntity = new PlatformGroupEntity();

        $tabs->add_tab(
            new DynamicVisualTab(
                PlatformGroupEntity :: ENTITY_TYPE,
                $groupEntity->get_entity_translated_name(),
                $groupEntity->get_entity_icon(),
                $this->get_url(
                    array(
                        \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getElementId(),
                        self :: PARAM_ENTITY_TYPE => PlatformGroupEntity :: ENTITY_TYPE)),
                $this->getEntityType() == PlatformGroupEntity :: ENTITY_TYPE));

        $tabs->set_content($this->getContent());

        $html = array();

        $html[] = $this->action_bar->as_html();
        $html[] = $tabs->render();

        return implode(PHP_EOL, $html);
    }

    public function getContent()
    {
        $table = new VocabularyTable($this);
        return $table->as_html();
    }

    public function getEntityType()
    {
        return $this->getRequest()->query->get(self :: PARAM_ENTITY_TYPE, 0);
    }

    public function getEntityId()
    {
        return $this->getRequest()->query->get(self :: PARAM_ENTITY_ID);
    }

    /**
     * Builds the action bar
     *
     * @return ActionBarRenderer
     */
    protected function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_url());

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                Theme :: getInstance()->getCommonImagePath('Action/Create'),
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_CREATE,
                        \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getElementId()))));

        return $action_bar;
    }

    /**
     * Returns the condition
     *
     * @param string $table_class_name
     *
     * @return \libraries\storage\Condition
     */
    public function get_table_condition($table_class_name)
    {
        $conditions = array();

        $searchCondition = $this->action_bar->get_conditions(
            array(new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_VALUE)));

        if ($searchCondition)
        {
            $conditions[] = $searchCondition;
        }

        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_ELEMENT_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($this->getElementId()));

        $entityType = $this->getEntityType();

        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_ENTITY_TYPE),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($entityType));
        $entityId = $this->getEntityId();

        if ($entityId)
        {
            $conditions[] = new ComparisonCondition(
                new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_ENTITY_ID),
                ComparisonCondition :: EQUAL,
                new StaticConditionVariable($entityId));
        }

        return new AndCondition($conditions);
    }
}
