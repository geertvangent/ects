<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\GroupCalendarRendererProvider;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Breadcrumb;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupComponent extends BrowserComponent
{
    const UNORDERED_GROUPS = 'Andere';

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->initialize();
        return $this->renderCalendar();
    }

    /**
     *
     * @return string[]
     */
    protected function getDisplayParameters()
    {
        return array(
            self :: PARAM_CONTEXT => self :: package(),
            self :: PARAM_ACTION => self :: ACTION_GROUP,
            ViewRenderer :: PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer :: PARAM_TIME => $this->getCurrentRendererTime(),
            self :: PARAM_USER_USER_ID => $this->getUserCalendar()->get_id(),
            self :: PARAM_GROUP_ID => $this->getGroupIdentifier());
    }

    protected function renderGroups()
    {
        $calendarService = $this->getCalendarService();
        $userFaculties = $calendarService->getFacultiesForUser($this->getUserCalendar());
        $userGroups = $calendarService->getFacultiesGroupsForUser($this->getUserCalendar());

        $html = array();

        if (count($userFaculties) > 0)
        {
            $html[] = '<h3>' . Translation :: get('MyFaculties') . '</h3>';

            $tabs = new DynamicTabsRenderer('my_faculties');

            foreach ($userFaculties as $userFaculty)
            {
                $content = $this->renderFacultyGroups(
                    $userFaculty,
                    $calendarService->getFacultyGroupsByCode($userFaculty['department_id']),
                    $userGroups[$userFaculty['department_id']]);
                $tabs->add_tab(
                    new DynamicContentTab($userFaculty['department_id'], $userFaculty['department_name'], null, $content));
            }

            $html[] = $tabs->render();

            $html[] = '<h3>' . Translation :: get('OtherFaculties') . '</h3>';
        }

        $faculties = $calendarService->getFaculties();

        if (count($faculties) > count($userFaculties))
        {
            $tabs = new DynamicTabsRenderer('other_faculties');

            foreach ($faculties as $faculty)
            {
                $facultyGroups = $calendarService->getFacultyGroupsByCode($faculty['department_id']);

                if (! isset($userFaculties[$faculty['department_id']]) && count($facultyGroups) > 0)
                {
                    $content = $this->renderFacultyGroups(
                        $faculty,
                        $facultyGroups,
                        $userGroups[$faculty['department_id']]);
                    $tabs->add_tab(
                        new DynamicContentTab($faculty['department_id'], $faculty['department_name'], null, $content));
                }
            }

            $html[] = $tabs->render();
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param string[] $faculty
     * @param string[] $facultyGroups
     * @param string[] $userGroups
     * @return string
     */
    protected function renderFacultyGroups($faculty, $facultyGroups, $userGroups)
    {
        $html = array();

        $facultyGroupData = array();

        if (count($userGroups) > 0)
        {
            $html[] = '<h4>' . Translation :: get('MyGroups') . '</h4>';

            $html[] = '<ul class="syllabus-group-list">';

            foreach ($userGroups as $userGroup)
            {
                $html[] = '<li>';
                $html[] = $this->renderGroupLink($userGroup['group_name'], $userGroup['group_id']);
                $html[] = '</li>';
            }

            $html[] = '</ul>';

            $html[] = '<h4>' . Translation :: get('OtherGroups') . '</h4>';
        }

        $facultyGroups = $this->orderFacultyGroups($facultyGroups);

        $html[] = '<ul class="syllabus-group-list">';

        foreach ($facultyGroups as $facultyTypeGroup => $facultyTypeGroups)
        {
            if (count($facultyGroups) > 1)
            {
                $html[] = '<li>' . $facultyTypeGroup;
                $html[] = '<ul class="syllabus-group-list syllabus-group-sublist">';
            }

            foreach ($facultyTypeGroups as $facultyTypeGroup)
            {

                if (! key_exists($facultyTypeGroup['id'], $userGroups))
                {
                    $html[] = '<li>';
                    $html[] = $this->renderGroupLink($facultyTypeGroup['name'], $facultyTypeGroup['id']);
                    $html[] = '</li>';
                }
            }

            if (count($facultyGroups) > 1)
            {
                $html[] = '</li>';
                $html[] = '</ul>';
            }
        }

        $html[] = '</ul>';

        $html[] = ResourceManager :: get_instance()->get_resource_html(
            Path :: getInstance()->getJavascriptPath(self :: package(), true) . 'Group.js');

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param string[] $facultyGroups
     * @return string[]
     */
    protected function orderFacultyGroups($facultyGroups)
    {
        $orderedFacultyGroups = array();

        foreach ($facultyGroups as $facultyGroup)
        {
            $groupNameParts = explode('/', $facultyGroup['name'], 3);

            if (count($groupNameParts) == 3)
            {
                $facultyGroup['name'] = $groupNameParts[2];
                $orderedFacultyGroups[$groupNameParts[1]][] = $facultyGroup;
            }
            else
            {
                $facultyGroup['name'] = $groupNameParts[1];
                $orderedFacultyGroups[self :: UNORDERED_GROUPS][] = $facultyGroup;
            }
        }

        if (count($orderedFacultyGroups) == 1 && key_exists(self :: UNORDERED_GROUPS, $orderedFacultyGroups))
        {
            $orderedFacultyGroups = array();

            foreach ($facultyGroups as $facultyGroup)
            {
                $groupNameParts = explode('/', $facultyGroup['name'], 2);
                $groupName = $groupNameParts[1];

                $firstCharacter = substr($groupName, 0, 1);

                if (is_numeric($firstCharacter))
                {
                    $facultyGroup['name'] = substr($groupName, 1);
                    $orderedFacultyGroups[$firstCharacter][] = $facultyGroup;
                }
                else
                {
                    $facultyGroup['name'] = $groupName;
                    $orderedFacultyGroups[self :: UNORDERED_GROUPS][] = $facultyGroup;
                }
            }
        }

        return $orderedFacultyGroups;
    }

    /**
     *
     * @param string $originalGroupName
     * @return string
     */
    protected function renderGroupLink($groupName, $groupId)
    {
        $groupLink = new Redirect(
            array(
                self :: PARAM_CONTEXT => self :: package(),
                self :: PARAM_ACTION => self :: ACTION_GROUP,
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                self :: PARAM_GROUP_ID => $groupId));

        return '<a href="' . $groupLink->getUrl() . '">' . $groupName . '</a>';
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    protected function getCalendarService()
    {
        if (! isset($this->calendarService))
        {
            $this->calendarService = new CalendarService(new CalendarRepository());
        }

        return $this->calendarService;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider
     */
    protected function getCalendarDataProvider()
    {
        if (! isset($this->calendarDataProvider))
        {
            $this->calendarDataProvider = new GroupCalendarRendererProvider(
                $this->getGroupIdentifier(),
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }

    protected function renderCalendar()
    {
        $groupUrl = new Redirect($this->getDisplayParameters(), array(self :: PARAM_GROUP_ID));

        BreadcrumbTrail :: get_instance()->add(
            new Breadcrumb($groupUrl->getUrl(), Translation :: get('GroupComponent')));

        $groupIdentifier = $this->getGroupIdentifier();

        if ($groupIdentifier)
        {
            $group = $this->getCalendarService()->getGroupByIdentifier($groupIdentifier);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $group['name']));

            return parent :: renderCalendar();
        }
        else
        {

            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->renderGroups();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }

    /**
     *
     * @return string
     */
    protected function getGroupIdentifier()
    {
        if (! isset($this->groupIdentifier))
        {
            $this->groupIdentifier = $this->getRequest()->query->get(self :: PARAM_GROUP_ID);
        }

        return $this->groupIdentifier;
    }
}
