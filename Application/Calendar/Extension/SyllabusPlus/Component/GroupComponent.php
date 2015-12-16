<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $tabs = $this->getTabs();
        $tabs->set_content($this->getContent());

        $html = array();

        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    protected function getContent()
    {
        $calendarService = $this->getCalendarService();
        $userFaculties = $calendarService->getFacultiesForUser($this->getUser());

        $html = array();

        if (count($userFaculties) > 0)
        {
            $html[] = '<h3>' . Translation :: get('MyFaculties') . '</h3>';

            $tabs = new DynamicTabsRenderer('my_faculties');

            foreach ($userFaculties as $userFaculty)
            {
                $content = $this->renderFacultyGroups(
                    $userFaculty,
                    $calendarService->getFacultyGroupsByCode($userFaculty['department_id']));
                $tabs->add_tab(
                    new DynamicContentTab($userFaculty['department_id'], $userFaculty['department_name'], null, $content));
            }

            $html[] = $tabs->render();
        }

        $faculties = $calendarService->getFaculties();

        if (count($faculties) > count($userFaculties))
        {
            $html[] = '<h3>' . Translation :: get('OtherFaculties') . '</h3>';

            $tabs = new DynamicTabsRenderer('other_faculties');

            foreach ($faculties as $faculty)
            {
                $facultyGroups = $calendarService->getFacultyGroupsByCode($faculty['department_id']);

                if (! isset($userFaculties[$faculty['department_id']]) && count($facultyGroups) > 0)
                {
                    $content = $this->renderFacultyGroups($faculty, $facultyGroups);
                    $tabs->add_tab(
                        new DynamicContentTab($faculty['department_id'], $faculty['department_name'], null, $content));
                }
            }

            $html[] = $tabs->render();
        }

        return implode(PHP_EOL, $html);
    }

    protected function renderFacultyGroups($faculty, $facultyGroups)
    {
        $html = array();

        $facultyGroupData = array();

        $html[] = '<ul class="syllabus-group-list">';

        foreach ($facultyGroups as $facultyGroup)
        {
            $html[] = '<li>';
            $html[] = $facultyGroup['name'];
            $html[] = '</li>';
        }

        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }

    protected function getCalendarService()
    {
        if (! isset($this->calendarService))
        {
            $this->calendarService = new CalendarService(new CalendarRepository());
        }

        return $this->calendarService;
    }
}
