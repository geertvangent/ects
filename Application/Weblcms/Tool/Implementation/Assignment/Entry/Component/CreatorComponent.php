<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Component;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\Repository\Common\Action\ContentObjectCopier;
use Chamilo\Core\Repository\ContentObject\File\Storage\DataClass\File;
use Chamilo\Core\Repository\ContentObject\Webpage\Storage\DataClass\Webpage;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Core\Repository\Workspace\PersonalWorkspace;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Application\Weblcms\Storage\DataClass\CourseEntityRelation;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Cache\DataClassCache;
use Chamilo\Application\Weblcms\Rights\Entities\CourseGroupEntity;
use Chamilo\Application\Weblcms\Rights\Entities\CoursePlatformGroupEntity;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Form\EntryForm;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CreatorComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @var \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication
     */
    private $publication;

    /**
     *
     * @return \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication
     */
    protected function getPublication()
    {
        if (! isset($this->publication))
        {
            $publicationIdentifier = $this->getRequest()->query->get(
                \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
            $this->publication = DataManager :: retrieve_by_id(
                ContentObjectPublication :: class_name(),
                $publicationIdentifier);
        }

        return $this->publication;
    }

    /**
     *
     * @return \Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment
     */
    protected function getAssignment()
    {
        return $this->getPublication()->get_content_object();
    }

    /**
     *
     * @return integer
     */
    protected function getTargetIdentifier()
    {
        return $this->getRequest()->query->get(
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID);
    }

    protected function getEntityType()
    {
        return $this->getRequest()->query->get(
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE);
    }

    /**
     *
     * @param unknown $choices
     * @return string
     */
    protected function renderEntryForm($choices)
    {
        $form = new EntryForm(
            $choices,
            $this->getAssignment(),
            $this->get_url(
                array(
                    \Chamilo\Core\Repository\Viewer\Manager :: PARAM_ACTION => \Chamilo\Core\Repository\Viewer\Manager :: ACTION_PUBLISHER,
                    \Chamilo\Core\Repository\Viewer\Manager :: PARAM_CONTENT_OBJECT_TYPE => Request :: get(
                        \Chamilo\Core\Repository\Viewer\Manager :: PARAM_CONTENT_OBJECT_TYPE),
                    \Chamilo\Core\Repository\Viewer\Manager :: PARAM_ID => $this->getSelectedContentObject())));

        // create submission feedback tracker when form is valid
        if ($form->validate())
        {
            $values = $form->exportValues();

            $entityType = substr($values[Entry :: PROPERTY_ENTITY_ID], 0, 1);
            $entityIdentifier = substr($values[Entry :: PROPERTY_ENTITY_ID], 1);
            $entity = $this->createEntry($entityIdentifier, $this->getSelectedContentObject(), $entityType);

            $this->redirect(
                Translation :: get('SubmissionCreated'),
                false,
                array(
                    self :: PARAM_ACTION => self :: ACTION_BROWSE,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId(),
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE => substr(
                        $entity,
                        0,
                        1),
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID => substr(
                        $entity,
                        1)));
        }
        else // display submission form
        {
            $html = array();

            $html[] = $this->render_header();
            $html[] = $form->toHtml();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }

    /**
     *
     * @return integer[]
     */
    protected function getSelectedContentObject()
    {
        return \Chamilo\Core\Repository\Viewer\Manager :: get_selected_objects();
    }

    /**
     *
     * @param ContentObject $contentObject
     * @return boolean
     */
    protected function isDownloadable(ContentObject $contentObject)
    {
        if ($this->isDocument($contentObject))
        {
            return true;
        }

        return false;
    }

    /**
     *
     * @param ContentObject $contentObject
     * @return boolean
     */
    protected function isDocument(ContentObject $contentObject)
    {
        if ($contentObject instanceof File || $contentObject instanceof Webpage)
        {
            return true;
        }

        return false;
    }

    /**
     *
     * @param integer $entityType
     * @param integer $entityIdentifier
     * @return string
     */
    protected function getEntityName($entityType, $entityIdentifier)
    {
        switch ($entityType)
        {
            case Entry :: ENTITY_TYPE_USER :
                return DataManager :: retrieve_by_id(User :: class_name(), $entityIdentifier)->get_fullname();
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                return DataManager :: retrieve_by_id(CourseGroup :: class_name(), $entityIdentifier)->get_name();
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                return DataManager :: retrieve_by_id(Group :: class_name(), $entityIdentifier)->get_name();
        }
    }

    /**
     *
     * @param integer $entityIdentifier
     * @param integer $selectedContentObject
     * @param integer $entityType
     * @return string
     */
    protected function createEntry($entityIdentifier, $selectedContentObject, $entityType = null)
    {
        $object = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
            ContentObject :: class_name(),
            $selectedContentObject);

        // Create a folder assignment in the root folder
        $assignement_category_id = \Chamilo\Core\Repository\Storage\DataManager :: get_repository_category_by_name_or_create_new(
            $this->getAssignment()->get_owner_id(),
            Translation :: get("Assignments"));

        // Create a folder course in the assignment folder
        $course_category_id = \Chamilo\Core\Repository\Storage\DataManager :: get_repository_category_by_name_or_create_new(
            $this->getAssignment()->get_owner_id(),
            $this->getCourse()->get_visual_code() . ' - ' . $this->getCourse()->get_title(),
            $assignement_category_id);

        // Create a folder with the name of the assignment in the course folder
        $category_id = \Chamilo\Core\Repository\Storage\DataManager :: get_repository_category_by_name_or_create_new(
            $this->getAssignment()->get_owner_id(),
            $this->getAssignment()->get_title(),
            $course_category_id);

        if (is_null($entityType))
        {
            $entityType = $this->getEntityType();
        }

        $copier = new ContentObjectCopier(
            $this->getUser(),
            array($object->get_id()),
            new PersonalWorkspace($this->getUser()),
            $this->getApplicationConfiguration()->getApplication()->get_user_id(),
            new PersonalWorkspace($this->getAssignment()->get_owner()),
            $this->getAssignment()->get_owner_id(),
            $category_id);
        $copiedContentObjectIdentifiers = $copier->run();

        if (count($copiedContentObjectIdentifiers) > 0)
        {
            foreach ($copiedContentObjectIdentifiers as $copiedContentObjectIdentifier)
            {
                $entityName = $this->getEntityName($entityType, $entityIdentifier);

                $copiedContentObject = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
                    ContentObject :: class_name(),
                    $copiedContentObjectIdentifier);
                $copiedContentObject->set_title($entityName . ' - ' . $copiedContentObject->get_title());

                if ($this->isDownloadable($copiedContentObject))
                {
                    $copiedContentObject->set_filename($entityName . ' - ' . $copiedContentObject->get_filename());
                }

                $copiedContentObject->update();
            }
        }
        else
        {
            foreach ($copier->get_messages() as $type)
            {
                $messages .= implode(PHP_EOL, $type);
            }

            $this->redirect(
                $messages,
                true,
                array(
                    self :: PARAM_ACTION => self :: ACTION_BROWSE,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId(),
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE => $entityType,
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID => $entityIdentifier));
        }

        $entry = new Entry();
        $entry->setPublicationId($this->getPublication()->getId());
        $entry->setContentObjectId($copiedContentObjectIdentifier);
        $entry->setEntityId($entityIdentifier);
        $entry->setEntityType($entityType);
        $entry->setSubmitted(time());
        $entry->setUserId($this->getApplicationConfiguration()->getApplication()->get_user_id());
        $entry->setIpAddress($_SERVER['REMOTE_ADDR']);
        $entry->create();

        return $entityType . $entityIdentifier;
    }

    /**
     *
     * @return \Chamilo\Application\Weblcms\Course\Storage\DataClass\Course
     */
    protected function getCourse()
    {
        return $this->getApplicationConfiguration()->getApplication()->get_course();
    }

    /**
     *
     * @return string[]
     */
    protected function compileChoices()
    {
        $choices = array();

        $targetEntities = WeblcmsRights :: get_instance()->get_target_entities(
            WeblcmsRights :: VIEW_RIGHT,
            \Chamilo\Application\Weblcms\Manager :: context(),
            $this->getPublication()->getId(),
            WeblcmsRights :: TYPE_PUBLICATION,
            $this->getCourse()->getId(),
            WeblcmsRights :: TREE_TYPE_COURSE);

        if ($this->getTargetIdentifier())
        {
            switch ($this->getEntityType())
            {
                case Entry :: ENTITY_TYPE_COURSE_GROUP :
                    return array(
                        Entry :: ENTITY_TYPE_COURSE_GROUP . $this->getTargetIdentifier() => DataManager :: retrieve_by_id(
                            CourseGroup :: class_name(),
                            $this->getTargetIdentifier())->get_name());
                case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                    return array(
                        Entry :: ENTITY_TYPE_PLATFORM_GROUP . $this->getTargetIdentifier() => DataManager :: retrieve_by_id(
                            Group :: class_name(),
                            $this->getTargetIdentifier())->get_name());
            }
        }

        $courseGroupResultset = \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager :: retrieve_course_groups_from_user(
            $this->getUser()->getId(),
            $this->getCourse()->getId());

        if ($targetEntities[0])
        {
            // add all course groups the user is member of
            while ($courseGroup = $courseGroupResultset->next_result())
            {
                $choices[Entry :: ENTITY_TYPE_COURSE_GROUP . $courseGroup->get_id()] = $courseGroup->get_name();
            }

            // retrieve platform groups subscribed to course
            $courseGroupConditions = array();
            $courseGroupConditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    CourseEntityRelation :: class_name(),
                    CourseEntityRelation :: PROPERTY_COURSE_ID),
                new StaticConditionVariable($this->getCourse()->get_id()));
            $courseGroupConditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    CourseEntityRelation :: class_name(),
                    CourseEntityRelation :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(CourseEntityRelation :: ENTITY_TYPE_GROUP));

            $groupIdentifiers = DataManager :: distinct(
                CourseEntityRelation :: class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($courseGroupConditions),
                    CourseEntityRelation :: PROPERTY_ENTITY_ID));

            // TODO: Problem with caching. Once caching problems fixed, remove the following line of code.
            DataClassCache :: truncate(Group :: class_name());
            // TODO: Problem with caching. Once caching problems fixed, remove the following line of code. END.

            $groupsResultset = \Chamilo\Core\Group\Storage\DataManager :: retrieve_groups_and_subgroups(
                $groupIdentifiers);
        }
        else
        {
            // add the target course groups the user is member of
            $targets = \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager :: retrieve_course_groups_and_subgroups(
                $targetEntities[CourseGroupEntity :: ENTITY_TYPE]);

            $targetCourseGroups = array();

            while ($target = $targets->next_result())
            {
                $targetCourseGroups[$target->get_id()] = $target->get_id();
            }

            while ($courseGroup = $courseGroupResultset->next_result())
            {
                if ($targetCourseGroups[$courseGroup->get_id()])
                {
                    $choices[Entry :: ENTITY_TYPE_COURSE_GROUP . $courseGroup->get_id()] = $courseGroup->get_name();
                }
            }

            // retrieve target platform groups
            $groupsResultset = \Chamilo\Core\Group\Storage\DataManager :: retrieve_groups_and_subgroups(
                $targetEntities[CoursePlatformGroupEntity :: ENTITY_TYPE]);
        }

        // add platform groups the user is member of
        while ($group = $groupsResultset->next_result())
        {
            if (\Chamilo\Core\Group\Storage\DataManager :: is_group_member($group->get_id(), $this->getUser()->get_id()))
            {
                $choices[Entry :: ENTITY_TYPE_PLATFORM_GROUP . $group->getId()] = $group->get_name();
            }
        }

        return $choices;
    }

    public function run()
    {
        if ($this->getAssignment()->get_allow_group_submissions())
        {
            $choices = $this->compileChoices();

            if (count($choices) == 0 || ! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT))
            {
                $message = Translation :: get('NoOwnGroups');
                $parameters = array();
                $parameters[\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID] = $this->getPublication()->getId();
                $parameters[\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION] = $this->is_allowed(
                    WeblcmsRights :: EDIT_RIGHT) ? self :: ACTION_ENTITIES : self :: ACTION_STUDENT;

                $this->redirect($message, true, $parameters);
            }
        }

        if (\Chamilo\Core\Repository\Viewer\Manager :: is_ready_to_be_published())
        {
            if ($this->getAssignment()->get_allow_group_submissions() &&
                 (! $this->getTargetIdentifier() || $this->getEntityType() == Entry :: ENTITY_TYPE_USER))
            {
                return $this->renderEntryForm($choices);
            }
            else
            {
                $entry = $this->createEntry($this->getTargetIdentifier(), $this->getSelectedContentObject());
                $this->redirect(
                    Translation :: get('SubmissionCreated'),
                    false,
                    array(
                        self :: PARAM_ACTION => self :: ACTION_BROWSE,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId(),
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE => substr(
                            $entry,
                            0,
                            1),
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID => substr(
                            $entry,
                            1)));
            }
        }
        else // construct RepoViewer when no content object created yet
        {
            $result = $this->checkStartEndTime();

            if ($result === true)
            {
                $factory = new ApplicationFactory(
                    \Chamilo\Core\Repository\Viewer\Manager :: context(),
                    new ApplicationConfiguration($this->getRequest(), $this->getUser(), $this));
                $component = $factory->getComponent();
                $component->set_maximum_select(\Chamilo\Core\Repository\Viewer\Manager :: SELECT_SINGLE);
                return $component->run();
            }
            else
            {
                return $result;
            }
        }
    }

    /**
     *
     * @return string[]
     */
    public function get_allowed_content_object_types()
    {
        return explode(',', $this->getAssignment()->get_allowed_types());
    }

    /**
     *
     * @return boolean
     */
    protected function checkStartEndTime()
    {
        if ($this->getAssignment()->get_start_time() > time())
        {
            $html = array();

            $html[] = $this->render_header();
            $date = DatetimeUtilities :: format_locale_date(
                Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES) . ', ' .
                     Translation :: get('TimeNoSecFormat', null, Utilities :: COMMON_LIBRARIES),
                    $this->getAssignment()->get_start_time());
            $html[] = Translation :: get('AssignmentNotStarted') . Translation :: get('StartTime') . ': ' . $date;
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }

        if ($this->getAssignment()->get_end_time() < time() && $this->getAssignment()->get_allow_late_submissions() == 0)
        {
            $html = array();

            $html[] = $this->render_header();
            $date = DatetimeUtilities :: format_locale_date(
                Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES) . ', ' .
                     Translation :: get('TimeNoSecFormat', null, Utilities :: COMMON_LIBRARIES),
                    $this->getAssignment()->get_end_time());
            $html[] = Translation :: get('AssignmentEnded') . Translation :: get('EndTime') . ': ' . $date;
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }

        return true;
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        if (! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT, $this->getPublication()))
        {
            $this->redirect(
                Translation :: get("NotAllowed", null, Utilities :: COMMON_LIBRARIES),
                true,
                array(),
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID,
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID,
                    \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE));
        }

        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => \Chamilo\Application\Weblcms\Tool\Manager :: ACTION_BROWSE)),
                Translation :: get('AssignmentToolBrowserComponent')));

        if ($this->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            $breadcrumbtrail->add(
                new Breadcrumb(
                    $this->get_url(
                        array(
                            self :: PARAM_ACTION => self :: ACTION_ENTITIES,
                            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId())),
                    $this->getAssignment()->get_title()));
        }
        else
        {
            $breadcrumbtrail->add(
                new Breadcrumb(
                    $this->get_url(
                        array(
                            self :: PARAM_ACTION => self :: ACTION_STUDENT,
                            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId())),
                    $this->getAssignment()->get_title()));
        }
    }

    public function get_additional_parameters()
    {
        return array(
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID,
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID,
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE);
    }
}
