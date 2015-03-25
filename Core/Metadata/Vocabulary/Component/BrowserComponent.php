<?php
namespace Ehb\Core\Metadata\Vocabulary\Component;

use Ehb\Core\Metadata\Vocabulary\Manager;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Ehb\Core\Metadata\Vocabulary\Table\Vocabulary\VocabularyTable;
use Ehb\Core\Metadata\Vocabulary\Storage\DataManager;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Architecture\Exceptions\NoObjectSelectedException;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 *
 * @package Ehb\Core\Metadata\Vocabulary\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
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

        if (! $this->getSelectedElementId())
        {
            throw new NoObjectSelectedException(Translation :: get('Element', null, 'Ehb\Core\Metadata\Element'));
        }

        $html = array();

        $html[] = $this->render_header();
        $html[] = $this->as_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function as_html()
    {
        $table = new VocabularyTable($this);

        $html = array();

        $userId = $this->getSelectedUserId();

        if ($userId != 0)
        {
            $user = DataManager :: retrieve_by_id(User :: class_name(), $userId);
            BreadcrumbTrail :: get_instance()->add(
                new Breadcrumb($this->get_url(array(Manager :: PARAM_USER_ID => $userId)), $user->get_fullname()));
        }

        $html[] = $this->get_action_bar()->as_html();
        $html[] = $table->as_html();

        return implode(PHP_EOL, $html);
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
                        \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getSelectedElementId()))));

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

        $searchCondition = $this->get_action_bar()->get_conditions(
            array(new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_VALUE)));

        if ($searchCondition)
        {
            $conditions[] = $searchCondition;
        }

        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_ELEMENT_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($this->getSelectedElementId()));

        $userId = $this->getSelectedUserId();

        if ($userId)
        {
            $conditions[] = new ComparisonCondition(
                new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_USER_ID),
                ComparisonCondition :: EQUAL,
                new StaticConditionVariable($userId));
        }

        return new AndCondition($conditions);
    }
}
