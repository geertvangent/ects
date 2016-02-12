<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Ehb\Application\Avilarts\CourseSettingsConnector;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Renderer\PublicationList\Type\ContentObjectPublicationDetailsRenderer;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;
use Chamilo\Core\Repository\Feedback\FeedbackSupport;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Exceptions\ObjectNotExistException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * $Id: viewer.class.php 200 2009-11-13 12:30:04Z kariboe $
 * 
 * @package repository.lib.complex_display.assessment.component
 */
class ViewerComponent extends Manager implements DelegateComponent, FeedbackSupport
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    private $html;

    private $feedback_allowed;

    private $publication;

    public function run()
    {
        // check if the content object has indeed been published for the user
        $this->publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(), 
            $this->get_publication_id());
        
        if (! $this->publication)
        {
            throw new ObjectNotExistException(Translation :: get('Publication'), $this->get_publication_id());
        }
        
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: VIEW_RIGHT, $this->publication))
        {
            $this->redirect(
                Translation :: get("NotAllowed", null, Utilities :: COMMON_LIBRARIES), 
                true, 
                array(), 
                array(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION, 
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID));
        }
        
        $object = $this->publication->get_content_object();
        
        BreadcrumbTrail :: get_instance()->add(
            new Breadcrumb(
                $this->get_url(), 
                Translation :: get('ToolViewerComponent', array('TITLE' => $object->get_title()))));
        
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $renderer = new ContentObjectPublicationDetailsRenderer($this);
        $this->html = $renderer->as_html();
        
        $course_settings_controller = CourseSettingsController :: get_instance();
        $this->feedback_allowed = $course_settings_controller->get_course_setting(
            $this->get_course_id(), 
            CourseSettingsConnector :: ALLOW_FEEDBACK);
        
        if ($this->feedback_allowed)
        {
            $factory = new ApplicationFactory(
                \Chamilo\Core\Repository\Feedback\Manager :: context(), 
                new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
            $result = $factory->run();
        }
        else
        {
            $result = '';
        }
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $result;
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function render_header()
    {
        $html = array();
        
        $html[] = parent :: render_header();
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = '<div id="action_bar_browser">';
        $html[] = $this->html;
        
        if ($this->feedback_allowed)
        {
            $html[] = '<div id="publication_feedback">';
            $html[] = '<h4>' . Translation :: get('Feedbacks') . '</h4>';
        }
        
        return implode(PHP_EOL, $html);
    }

    public function render_footer()
    {
        $html = array();
        
        if ($this->feedback_allowed)
        {
            $html[] = '</div>';
        }
        
        $html[] = '</div>';
        $html[] = parent :: render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function get_publication_id()
    {
        return Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }

    public function get_publication_count()
    {
        return 1;
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar();
            $commonActions = new ButtonGroup();
            $toolActions = new ButtonGroup();
            if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
            {
                $commonActions->addButton(new Button($this->get_access_details_toolbar_item($this)));
            }
            
            if (method_exists($this->get_parent(), 'get_tool_actions'))
            {
                $toolActions->addButton(new Button($this->get_parent()->get_tool_actions()));
            }
            $buttonToolbar->addButtonGroup($toolActions);
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function add_actionbar_item($item)
    {
        $buttonToolbar = $this->buttonToolbarRenderer->getButtonToolBar();
        $commonActions = new ButtonGroup();
        $commonActions->addButton(new Button($item));
        $buttonToolbar->addButtonGroup($commonActions);
    }

    public function can_create_feedback()
    {
        return $this->feedback_allowed;
    }

    public function can_update_feedback($feedback)
    {
        return $this->feedback_allowed;
    }

    public function can_delete_feedback($feedback)
    {
        return $this->feedback_allowed;
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::retrieve_feedbacks()
     */
    public function retrieve_feedbacks()
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_feedback_conditions(), 
            null, 
            null, 
            array(
                new OrderBy(
                    new PropertyConditionVariable(
                        \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: class_name(), 
                        \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: PROPERTY_MODIFICATION_DATE))));
        
        return \Ehb\Application\Avilarts\Storage\DataManager :: retrieves(
            \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: class_name(), 
            $parameters);
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::count_feedbacks()
     */
    public function count_feedbacks()
    {
        $parameters = new DataClassCountParameters($this->get_feedback_conditions());
        return \Ehb\Application\Avilarts\Storage\DataManager :: count(
            \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: class_name(), 
            $parameters);
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::retrieve_feedback()
     */
    public function retrieve_feedback($feedback_id)
    {
        return \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: class_name(), 
            $feedback_id);
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::get_feedback()
     */
    public function get_feedback()
    {
        $feedback = new \Ehb\Application\Avilarts\Storage\DataClass\Feedback();
        $feedback->set_publication_id($this->publication->get_id());
        return $feedback;
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::is_allowed_to_view_feedback()
     */
    public function is_allowed_to_view_feedback()
    {
        return $this->is_allowed(2, $this->publication);
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::is_allowed_to_create_feedback()
     */
    public function is_allowed_to_create_feedback()
    {
        return $this->is_allowed(3, $this->publication);
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::is_allowed_to_update_feedback()
     */
    public function is_allowed_to_update_feedback($feedback)
    {
        return $feedback->get_user_id() == $this->get_user_id();
    }

    /*
     * (non-PHPdoc) @see \core\repository\feedback\FeedbackSupport::is_allowed_to_delete_feedback()
     */
    public function is_allowed_to_delete_feedback($feedback)
    {
        return $feedback->get_user_id() == $this->get_user_id();
    }

    private function get_feedback_conditions()
    {
        $conditions = array();
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: class_name(), 
                \Ehb\Application\Avilarts\Storage\DataClass\Feedback :: PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($this->publication->get_id()));
        
        return new AndCondition($conditions);
    }
}
