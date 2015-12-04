<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @var \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication
     */
    private $publication;

    /**
     *
     * @var \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry[]
     */
    private $entries;

    /**
     *
     * @var \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry[]
     */
    private $indexedEntries = array();

    public function run()
    {
        if (! $this->isAuthorized())
        {
            throw new NotAllowedException();
        }

        $this->set_parameter(
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID,
            $this->getPublication()->getId());
        $this->set_parameter(
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID,
            $this->getTargetIdentifier());
        $this->set_parameter(
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE,
            $this->getEntityType());

        $this->addBreadcrumbs();

        return $this->renderEntries();
    }

    protected function renderEntries()
    {
        $isUserEntity = $this->getEntityType() == Entry :: ENTITY_TYPE_USER;
        $isCourseGroupEntity = $this->getEntityType() == Entry :: ENTITY_TYPE_COURSE_GROUP;
        $isPlatformGroupEntity = $this->getEntityType() == Entry :: ENTITY_TYPE_PLATFORM_GROUP;

        if ($isCourseGroupEntity)
        {
            $group = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieve_by_id(
                CourseGroup :: class_name(),
                $this->getTargetIdentifier());
        }
        elseif ($isPlatformGroupEntity)
        {
            $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve_by_id(
                Group :: class_name(),
                $this->getTargetIdentifier());
        }

        // Check if toolbar should be shown
        if ($isUserEntity &&
             $this->getApplicationConfiguration()->getApplication()->get_user_id() == $this->getTargetIdentifier())
        {
            $displayAdd = true;
        }
        elseif (! $isUserEntity)
        {
            if ($isCourseGroupEntity)
            {
                $displayAdd = \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager :: is_course_group_member(
                    $group->getId(),
                    $this->getApplicationConfiguration()->getApplication()->get_user_id());
            }
            else
            {
                $displayAdd = $this->isGroupMember($group);
            }
        }

        $html = array();

        $html[] = $this->renderHeader($displayAdd);
        $html[] = $this->renderNavigationBar();
        $html[] = '<div class="announcements level_1" style="background-image: url(' .
             Theme :: getInstance()->getCommonImagePath('ContentObject/Introduction') . ')">';
        $html[] = $this->renderDetails();
        return implode(PHP_EOL, $html);
        // Display group members
        if ($this->getEntityType() != Entry :: ENTITY_TYPE_USER)
        {
            $html[] = '<div class="description" style="font-weight:bold;float:left">';
            $html[] = Translation :: get('GroupMembers') . ':&nbsp;';
            $html[] = '</div>';
            $html[] = '<div style="float:left">';
            $html[] = $this->displayGroupMembers();
            $html[] = '<br/><br/></div>';
        }
        $html[] = $this->renderReporting();
        $html[] = '</div>';

        if ($this->getEntityType() == Entry :: ENTITY_TYPE_USER)
        {
            $table = new UserTable($this);
            $html[] = $table->as_html();
        }
        else
        {
            $table = new GroupTable($this);
            $html[] = $table->as_html();
        }

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    protected function renderHeader($displayAdd)
    {
        $html = array();

        $html[] = parent :: render_header();

        $this->action_bar = $this->getToolbar($displayAdd);

        if ($this->action_bar)
        {
            $html[] = $this->action_bar->as_html();
        }

        return implode(PHP_EOL, $html);
    }

    protected function getToolbar($displayAdd)
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);

        if ($this->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            if ($this->getEntries($this->getEntityType(), $this->getTargetIdentifier()))
            {
                $action_bar->add_common_action(
                    new ToolbarItem(
                        Translation :: get('DownloadAllSubmissions'),
                        Theme :: getInstance()->getCommonImagePath('Action/Download'),
                        $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_DOWNLOAD)),
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            }

            $action_bar->add_tool_action(
                new ToolbarItem(
                    Translation :: get('ScoresOverview'),
                    Theme :: getInstance()->getCommonImagePath('Action/Statistics'),
                    $this->get_url(
                        array(
                            \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL => \Chamilo\Application\Weblcms\Manager :: ACTION_REPORTING,
                            \Chamilo\Application\Weblcms\Manager :: PARAM_TEMPLATE_ID => \Chamilo\Application\Weblcms\Integration\Chamilo\Core\Reporting\Template\CourseSubmitterSubmissionsTemplate :: class_name(),
                            \Chamilo\Application\Weblcms\Manager :: PARAM_PUBLICATION => $this->getPublication()->getId(),
                            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID => $this->getTargetIdentifier(),
                            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE => $this->getEntityType(),
                            \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL_ACTION => \Chamilo\Application\Weblcms\Tool\Implementation\Reporting\Manager :: ACTION_VIEW)),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        if ($displayAdd)
        {
            $action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('SubmissionSubmit'),
                    Theme :: getInstance()->getCommonImagePath('Action/Add'),
                    $this->get_url(
                        array(
                            self :: PARAM_ACTION => self :: ACTION_CREATE,
                            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId())),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        return $action_bar;
    }

    /**
     *
     * @param \Chamilo\Core\Group\Storage\DataClass\Group $group
     * @return boolean
     */
    protected function isGroupMember($group)
    {
        $user = DataManager :: retrieve_by_id(
            User :: class_name(),
            $this->getApplicationConfiguration()->getApplication()->get_user_id());
        $platformGroupIdentifiers = $user->get_groups(true);

        return in_array($group->getId(), $platformGroupIdentifiers);
    }

    protected function addBreadcrumbs()
    {
        switch ($this->getEntityType())
        {
            case Entry :: ENTITY_TYPE_USER :
                $breadcrumb_title = \Chamilo\Core\User\Storage\DataManager :: get_fullname_from_user(
                    $this->getTargetIdentifier());
                break;
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                $breadcrumb_title = DataManager :: retrieve_by_id(
                    CourseGroup :: class_name(),
                    $this->getTargetIdentifier())->get_name();
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                $breadcrumb_title = DataManager :: retrieve_by_id(Group :: class_name(), $this->getTargetIdentifier())->get_name();
                break;
        }

        $breadcrumbTrail = BreadcrumbTrail :: get_instance();
        $breadcrumbTrail->remove($breadcrumbTrail->size() - 1);
        $breadcrumbTrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_BROWSE,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $this->getPublication()->getId(),
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID => $this->getTargetIdentifier(),
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE => $this->getEntityType())),
                $breadcrumb_title));
    }

    /**
     *
     * @return integer
     */
    protected function getEntityType()
    {
        return $this->getRequest()->query->get(
            \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: PARAM_ENTITY_TYPE);
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

    protected function isAuthorized()
    {
        if (! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT, $this->getPublication()) ||
             ! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT))
        {
            return false;
        }

        if ($this->is_allowed(WeblcmsRights :: EDIT_RIGHT) || $this->getAssignment()->get_visibility_submissions())
        {
            return true;
        }

        $isMember = false;

        switch ($this->getEntityType())
        {
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                $isMember = $this->isCourseGroupMember($this->getTargetIdentifier(), $this->getUser()->getId());
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                $isMember = $this->isPlatformGroupMember($this->getTargetIdentifier(), $this->getUser()->getId());
                break;
            case Entry :: ENTITY_TYPE_USER :
                $isMember = $this->isCurrentUser($this->getTargetIdentifier());
                break;
        }

        if (! $isMember)
        {
            return false;
        }

        return true;
    }

    /**
     *
     * @param integer $groupIdentifier
     * @param integer $userIdentifier
     * @return boolean
     */
    protected function isCourseGroupMember($groupIdentifier, $userIdentifier)
    {
        return \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager :: is_course_group_member(
            $groupIdentifier,
            $userIdentifier);
    }

    /**
     *
     * @param integer $groupIdentifier
     * @param integer $userIdentifier
     * @return boolean
     */
    protected function isPlatformGroupMember($groupIdentifier, $userIdentifier)
    {
        $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve_by_id(Group :: class_name(), $groupIdentifier);

        if (\Chamilo\Core\Group\Storage\DataManager :: is_group_member($groupIdentifier, $userIdentifier))
        {
            return true;
        }

        if ($group->has_children())
        {
            foreach ($group->get_subgroups() as $subgroup)
            {
                if ($this->isPlatformGroupMember($subgroup->get_id(), $userIdentifier))
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @param integer $userIdentifier
     * @return boolean
     */
    protected function isCurrentUser($userIdentifier)
    {
        return $this->getUser()->getId() == $userIdentifier;
    }

    /**
     *
     * @return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry[]
     */
    protected function getEntriesFromTarget()
    {
        if (! isset($this->entries))
        {
            $orderBy = new OrderBy(new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_SUBMITTED));
            $this->entries = DataManager :: retrieves(
                Entry :: class_name(),
                new DataClassRetrievesParameters($this->getEntryConditions(), null, null, array($orderBy)))->as_array();
        }

        return $this->entries;
    }

    /**
     *
     * @return \Chamilo\Libraries\Storage\Query\Condition\AndCondition
     */
    protected function getEntryConditions()
    {
        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($this->getPublication()->getId()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_SUBMITTER_ID),
            new StaticConditionVariable($this->getTargetIdentifier()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_SUBMITTER_TYPE),
            new StaticConditionVariable($this->getEntityType()));

        return new AndCondition($conditions);
    }

    protected function getEntries($entityType, $entityId)
    {
        if (! isset($this->indexedEntries[$entityType]))
        {
            foreach ($this->getEntriesForType($entityType) as $entry)
            {
                $this->indexedEntries[$entityType][$entry[Entry :: PROPERTY_ENTITY_ID]] = $entry;
            }
        }

        return $this->indexedEntries[$entityType][$entityId];
    }

    protected function getEntriesForType($entityType)
    {
        return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataManager :: retrieveEntriesByType(
            $this->getPublication()->getId(),
            $entityType)->as_array();
    }

    protected function renderDetails()
    {
        $html = array();

        // Title
        $html[] = '<div class="title" style="width:100%;">';
        $html[] = Translation :: get('Details');
        $html[] = '</div><div class="clear">&nbsp;</div><br />';

        // Time titles
        $html[] = '<div style="font-weight:bold;float:left;">';
        $html[] = Translation :: get('StartTime') . ':&nbsp;<br />';
        $html[] = Translation :: get('EndTime') . ':&nbsp;<br />';
        $html[] = '</div>';

        // Times
        $html[] = '<div style="float:left;">';
        $html[] = DatetimeUtilities :: format_locale_date(
            Translation :: get('DateTimeFormatLong', null, Utilities :: COMMON_LIBRARIES),
            $this->getAssignment()->get_start_time()) . '<br />';
        $html[] = DatetimeUtilities :: format_locale_date(
            Translation :: get('DateTimeFormatLong', null, Utilities :: COMMON_LIBRARIES),
            $this->getAssignment()->get_end_time()) . '<br />';
        $html[] = '<br /></div><br />';

        // Description title
        $html[] = '<div class="description" style="font-weight:bold;">';
        $html[] = Translation :: get('Description');
        $html[] = '</div>';

        // Description
        $html[] = '<div class="description">';
        $html[] = $this->getAssignment()->get_description();
        $html[] = '</div>';

        // Attachments
        $attachments = $this->getAssignment()->get_attachments();
        if (count($attachments) > 0)
        {
            $html[] = '<div class="description" style="font-weight:bold;">';
            $html[] = Translation :: get('Attachments');
            $html[] = '</div>';

            Utilities :: order_content_objects_by_title($attachments);

            $html[] = '<div class="description">';
            $html[] = '<ul>';
            foreach ($attachments as $attachment)
            {
                $html[] = '<li>' . $this->renderAttachment($attachment) . '</li>';
            }
            $html[] = '</ul>';
            $html[] = '</div>';
        }

        return implode(PHP_EOL, $html);
    }

    protected function renderAttachment($attachment, $type = null)
    {
        $html = array();

        if ($this->isDownloadable($attachment))
        {
            $download_url = \Chamilo\Core\Repository\Manager :: get_document_downloader_url($attachment->get_id());

            $html[] = '<a href="' . $download_url . '">';
            $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/Download') . '" title="' . Translation :: get(
                'Download') . '"/>';
            $html[] = '</a>';
        }
        else
        {
            $html[] = '<a>';
            $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/DownloadNa') . '" title="' . Translation :: get(
                'DownloadNotPossible') . '"/>';
            $html[] = '</a>';
        }
        $html[] = '<img src="' . $attachment->get_icon_path(Theme :: ICON_MINI) . '" alt="' . htmlentities(
            Translation :: get(
                'TypeName',
                array(),
                ClassnameUtilities :: getInstance()->getNamespaceFromClassname($attachment->get_type()))) . '"/>';
        $html[] = '<a onclick="javascript:openPopup(\'' . $this->renderAttachmentUrl($attachment, $type) .
             '\'); return false;" href="#">';
        $html[] = $attachment->get_title();
        $html[] = '</a>';
        return implode(PHP_EOL, $html);
    }

    protected function renderAttachmentUrl($attachment, $type = null)
    {
        return str_replace(
            '\\',
            '\\\\',
            urldecode(
                $this->get_url(
                    array(
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: ACTION_VIEW_ATTACHMENT,
                        self :: PARAM_PUBLICATION_ID => $this->getPublication()->getId(),
                        self :: PARAM_OBJECT_ID => $attachment->get_id(),
                        self :: PARAM_ATTACHMENT_TYPE => $type))));
    }

    protected function renderNavigationBar()
    {
        $html = array();
        $html[] = '<div class="announcements level_2" style="background-image:url(' .
             Theme :: getInstance()->getCommonImagePath('ContentObject/Introduction') . ';width=100%">';

        if ($this->getAssignment()->get_visibility_submissions() || $this->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            $html[] = $this->renderNavigator();
        }
        $html[] = '</div>';
        $html[] = '<div class="clear">&nbsp;</div><br/>';
        return implode(PHP_EOL, $html);
    }

    protected function renderNavigator()
    {
        $html = array();
        // $html[] = '<div style="text-align:center">';
        // $previous_submitter_url = $this->get_previous_submitter_url();

        // if ($previous_submitter_url)
        // {
        // $html[] = '<a href="' . $previous_submitter_url . '">';
        // $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/Prev') . '"/>';
        // $html[] = Translation :: get('PreviousSubmitter');
        // $html[] = '</a>';
        // }
        // else
        // {
        // $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/PrevNa') . '"/>';
        // $html[] = Translation :: get('PreviousSubmitter');
        // }

        // $html[] = ' [' . $this->get_position_submitter($this->get_submitter_type(), $this->get_target_id()) . '/' .
        // $this->get_count_submitters($this->get_submitter_type()) . '] ';

        // $next_submitter_url = $this->get_next_submitter_url();

        // if ($next_submitter_url)
        // {
        // $html[] = '<a href="' . $next_submitter_url . '">';
        // $html[] = Translation :: get('NextSubmitter');
        // $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/Next') . '"/>';
        // $html[] = '</a>';
        // }
        // else
        // {
        // $html[] = Translation :: get('NextSubmitter');
        // $html[] = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/NextNa') . '"/>';
        // }
        // $html[] = '</div>';
        return implode(PHP_EOL, $html);
    }
}
