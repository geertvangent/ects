<?php
namespace Ehb\Application\Avilarts\Request\Form;

use Ehb\Application\Avilarts\Request\Storage\DataClass\Request;
use Ehb\Application\Avilarts\Rights\CourseManagementRights;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

class RequestForm extends FormValidator
{

    private $request;

    private $course_types;

    function __construct($request, $action)
    {
        parent :: __construct('request', 'post', $action);
        
        $this->request = $request;
        
        $this->build();
        $this->setDefaults();
    }

    function build()
    {
        if ($this->request->get_id())
        {
            $user_details = new \Chamilo\Core\User\UserDetails($this->request->get_user());
            $this->addElement('static', null, Translation :: get('User'), $user_details->toHtml());
        }
        
        $this->addElement(
            'select', 
            Request :: PROPERTY_COURSE_TYPE_ID, 
            Translation :: get('CourseType'), 
            $this->get_course_types());
        $this->addRule(
            Request :: PROPERTY_COURSE_TYPE_ID, 
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
            'required');
        
        $this->addElement('text', Request :: PROPERTY_NAME, Translation :: get('Name'));
        $this->addRule(
            Request :: PROPERTY_NAME, 
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
            'required');
        
        $this->addElement('text', Request :: PROPERTY_SUBJECT, Translation :: get('Subject'));
        $this->addRule(
            Request :: PROPERTY_SUBJECT, 
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
            'required');
        
        $this->addElement(
            'textarea', 
            Request :: PROPERTY_MOTIVATION, 
            Translation :: get('Motivation'), 
            array("cols" => 50, "rows" => 6));
        $this->addRule(
            Request :: PROPERTY_MOTIVATION, 
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
            'required');
        
        if ($this->request->get_id())
        {
            $this->addElement(
                'textarea', 
                Request :: PROPERTY_DECISION_MOTIVATION, 
                Translation :: get('DecisionMotivation'), 
                array("cols" => 50, "rows" => 6));
            $this->addRule(
                Request :: PROPERTY_DECISION_MOTIVATION, 
                Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
                'required');
            
            $buttons[] = $this->createElement(
                'style_submit_button', 
                'submit', 
                Translation :: get('Update', null, Utilities :: COMMON_LIBRARIES), 
                array('class' => 'positive update'));
        }
        else
        {
            $buttons[] = $this->createElement(
                'style_submit_button', 
                'submit', 
                Translation :: get('Send', null, Utilities :: COMMON_LIBRARIES), 
                array('class' => 'positive send'));
        }
        
        $buttons[] = $this->createElement(
            'style_reset_button', 
            'reset', 
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), 
            array('class' => 'normal empty'));
        
        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets default values.
     * 
     * @param $defaults array Default values for this form's parameters.
     */
    function setDefaults($defaults = array ())
    {
        $defaults[Request :: PROPERTY_COURSE_TYPE_ID] = $this->request->get_course_type_id();
        $defaults[Request :: PROPERTY_NAME] = $this->request->get_name();
        $defaults[Request :: PROPERTY_SUBJECT] = $this->request->get_subject();
        $defaults[Request :: PROPERTY_MOTIVATION] = $this->request->get_motivation();
        
        if ($this->request->get_id())
        {
            $defaults[Request :: PROPERTY_DECISION_MOTIVATION] = $this->request->get_decision_motivation();
        }
        
        parent :: setDefaults($defaults);
    }

    function get_course_types()
    {
        if (! isset($this->course_types))
        {
            $course_type_objects = \Chamilo\Application\Weblcms\CourseType\Storage\DataManager :: retrieve_active_course_types();
            $course_management_rights = CourseManagementRights :: get_instance();
            
            while ($course_type = $course_type_objects->next_result())
            {
                if ($course_management_rights->is_allowed(
                    CourseManagementRights :: REQUEST_COURSE_RIGHT, 
                    $course_type->get_id(), 
                    CourseManagementRights :: TYPE_COURSE_TYPE))
                {
                    $this->course_types[$course_type->get_id()] = $course_type->get_title();
                }
            }
        }
        return $this->course_types;
    }
}
?>