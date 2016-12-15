<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Glyph\BootstrapGlyph;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\GroupCalendarRendererProvider;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Group;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\StudentGroup;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupBrowserComponent extends UserBrowserComponent
{
    const UNORDERED_GROUPS = 'Andere';

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @var string
     */
    private $year;

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
            self::PARAM_CONTEXT => self::package(),
            self::PARAM_ACTION => self::ACTION_BROWSE_GROUP,
            ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id(),
            self::PARAM_GROUP_ID => $this->getGroupIdentifier(),
            self::PARAM_YEAR => $this->getYear());
    }

    /**
     *
     * @return string
     */
    protected function renderYears()
    {
        $calendarService = $this->getCalendarService();

        foreach ($calendarService->getYears() as $year)
        {
            $html[] = '<h4>' . Translation::get('AcademicYear', array('YEAR' => $year)) . '</h4>';
            $html[] = $this->renderGroups($year);
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return string
     */
    protected function renderGroups($year)
    {
        $calendarService = $this->getCalendarService();
        $userFaculties = $calendarService->getFacultiesByYearAndUser($year, $this->getUserCalendar());
        $userGroups = $calendarService->getFacultiesGroupsByYearAndUser($year, $this->getUserCalendar());

        $html = array();

        if (count($userFaculties) > 0)
        {
            $html[] = '<h5>' . Translation::get('MyFaculties') . '</h3>';

            $tabs = new DynamicTabsRenderer('my-faculties-' . $year);

            foreach ($userFaculties as $userFaculty)
            {
                $content = $this->renderFacultyGroups(
                    $userFaculty,
                    $calendarService->getFacultyGroupsByYearAndCode($userFaculty[StudentGroup::PROPERTY_FACULTY_ID]),
                    $userGroups[$userFaculty[StudentGroup::PROPERTY_FACULTY_ID]]);
                $tabs->add_tab(
                    new DynamicContentTab(
                        $year . '-' . $userFaculty[StudentGroup::PROPERTY_FACULTY_ID],
                        $userFaculty[StudentGroup::PROPERTY_FACULTY_NAME],
                        null,
                        $content));
            }

            $html[] = $tabs->render();

            $html[] = '<h5>' . Translation::get('OtherFaculties') . '</h3>';
        }

        $faculties = $calendarService->getFacultiesByYear($year);

        if (count($faculties) > count($userFaculties))
        {
            $tabs = new DynamicTabsRenderer('other-faculties-' . $year);

            foreach ($faculties as $faculty)
            {
                $facultyGroups = $calendarService->getFacultyGroupsByYearAndCode(
                    $year,
                    $faculty[Group::PROPERTY_FACULTY_ID]);

                if (! isset($userFaculties[$faculty[Group::PROPERTY_FACULTY_ID]]) && count($facultyGroups) > 0)
                {
                    $content = $this->renderFacultyGroups(
                        $faculty,
                        $facultyGroups,
                        $userGroups[$faculty[Group::PROPERTY_FACULTY_ID]]);
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $year . '-' . $faculty[Group::PROPERTY_FACULTY_ID],
                            $faculty[Group::PROPERTY_FACULTY_NAME],
                            null,
                            $content));
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
            $html[] = '<h4>' . Translation::get('MyGroups') . '</h4>';

            $html[] = '<ul class="syllabus-group-list">';

            foreach ($userGroups as $userGroup)
            {
                $html[] = '<li>';
                $html[] = $this->renderGroupLink(
                    $userGroup[StudentGroup::PROPERTY_YEAR],
                    $userGroup[StudentGroup::PROPERTY_GROUP_NAME],
                    $userGroup['group_id']);
                $html[] = '</li>';
            }

            $html[] = '</ul>';

            $html[] = '<h4>' . Translation::get('OtherGroups') . '</h4>';
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
                if (! key_exists($facultyTypeGroup[Group::PROPERTY_ID], $userGroups))
                {
                    $html[] = '<li>';
                    $html[] = $this->renderGroupLink(
                        $facultyTypeGroup[Group::PROPERTY_YEAR],
                        $facultyTypeGroup[Group::PROPERTY_NAME],
                        $facultyTypeGroup[Group::PROPERTY_ID]);
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

        $html[] = ResourceManager::getInstance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(self::package(), true) . 'Group.js');

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
                $orderedFacultyGroups[self::UNORDERED_GROUPS][] = $facultyGroup;
            }
        }

        if (count($orderedFacultyGroups) == 1 && key_exists(self::UNORDERED_GROUPS, $orderedFacultyGroups))
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
                    $orderedFacultyGroups[self::UNORDERED_GROUPS][] = $facultyGroup;
                }
            }
        }

        return $orderedFacultyGroups;
    }

    /**
     *
     * @param string $year
     * @param string $groupName
     * @param string $groupId
     * @return string
     */
    protected function renderGroupLink($year, $groupName, $groupId)
    {
        $groupLink = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_BROWSE_GROUP,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                self::PARAM_YEAR => $year,
                self::PARAM_GROUP_ID => $groupId));

        return '<a href="' . $groupLink->getUrl() . '">' . $groupName . '</a>';
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
                $this->getService('ehb.application.calendar.extension.syllabus_plus.service.calendar_service'),
                $this->getYear(),
                $this->getGroupIdentifier(),
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }

    protected function renderCalendar()
    {
        $groupUrl = new Redirect($this->getDisplayParameters(), array(self::PARAM_GROUP_ID));

        BreadcrumbTrail::getInstance()->add(
            new Breadcrumb($groupUrl->getUrl(), Translation::get('GroupBrowserComponent')));

        $groupIdentifier = $this->getGroupIdentifier();
        $year = $this->getYear();

        if ($groupIdentifier)
        {
            BreadcrumbTrail::getInstance()->add(
                new Breadcrumb(null, Translation::get('AcademicYear', array('YEAR' => $year))));

            $group = $this->getCalendarService()->getGroupByYearAndIdentifier($year, $groupIdentifier);

            BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $group['name']));

            return parent::renderCalendar();
        }
        else
        {

            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->renderYears();
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
            $this->groupIdentifier = $this->getRequest()->query->get(self::PARAM_GROUP_ID);
        }

        return $this->groupIdentifier;
    }

    /**
     *
     * @return string
     */
    protected function getYear()
    {
        if (! isset($this->year))
        {
            $this->year = $this->getRequest()->query->get(self::PARAM_YEAR);
        }

        return $this->year;
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\UserBrowserComponent::getViewActions()
     */
    protected function getViewActions()
    {
        $actions = array();

        $generalButtonGroup = new ButtonGroup();

        $printUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_BROWSE_GROUP,
                ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_GROUP_ID => $this->getGroupIdentifier(),
                self::PARAM_PRINT => 1));

        $generalButtonGroup->addButton(
            new Button(Translation::get('PrinterComponent'), new BootstrapGlyph('print'), $printUrl->getUrl()));

        $iCalUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_GROUP,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_GROUP_ID => $this->getGroupIdentifier()));

        $generalButtonGroup->addButton(
            new Button(Translation::get('ICalExternal'), new BootstrapGlyph('globe'), $iCalUrl->getUrl()));

        $actions[] = $generalButtonGroup;

        return $actions;
    }
}
