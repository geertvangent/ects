<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Tracking\Storage\DataClass;

class SubmissionNote extends \Chamilo\Core\Tracking\Storage\DataClass\SimpleTracker
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SUBMISSION_ID = 'submission_id';
    const PROPERTY_CREATED = 'created';
    const PROPERTY_MODIFIED = 'modified';
    const PROPERTY_NOTE = 'note';
    const PROPERTY_USER_ID = 'user_id';

    public function validate_parameters(array $parameters = array())
    {
        $this->set_submission_id($parameters[self :: PROPERTY_SUBMISSION_ID]);
        $this->set_created($parameters[self :: PROPERTY_CREATED]);
        $this->set_modified($parameters[self :: PROPERTY_MODIFIED]);
        $this->set_note($parameters[self :: PROPERTY_NOTE]);
        $this->set_user_id($parameters[self :: PROPERTY_USER_ID]);
    }

    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_SUBMISSION_ID, 
                self :: PROPERTY_CREATED, 
                self :: PROPERTY_MODIFIED, 
                self :: PROPERTY_NOTE, 
                self :: PROPERTY_USER_ID));
    }

    public function get_submission_id()
    {
        return $this->get_default_property(self :: PROPERTY_SUBMISSION_ID);
    }

    public function set_submission_id($submission_id)
    {
        $this->set_default_property(self :: PROPERTY_SUBMISSION_ID, $submission_id);
    }

    public function get_created()
    {
        return $this->get_default_property(self :: PROPERTY_CREATED);
    }

    public function set_created($created)
    {
        $this->set_default_property(self :: PROPERTY_CREATED, $created);
    }

    public function get_modified()
    {
        return $this->get_default_property(self :: PROPERTY_MODIFIED);
    }

    public function set_modified($modified)
    {
        $this->set_default_property(self :: PROPERTY_MODIFIED, $modified);
    }

    public function get_note()
    {
        return $this->get_default_property(self :: PROPERTY_NOTE);
    }

    public function set_note($note)
    {
        $this->set_default_property(self :: PROPERTY_NOTE, $note);
    }

    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }
}
