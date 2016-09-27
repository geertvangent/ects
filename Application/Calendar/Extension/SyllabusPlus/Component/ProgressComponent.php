<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\ScheduledGroup;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Format\Table\Column\SortableStaticTableColumn;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ProgressComponent extends Manager
{

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    private $calendarService;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $calendarService = $this->getCalendarService();
        $html = array();

        $html[] = $this->render_header();

        $html[] = '<div class="alert alert-info">';
        $html[] = Translation::getInstance()->get('ProgressInformation');
        $html[] = '</div>';

        foreach ($calendarService->getYears() as $year)
        {
            $html[] = '<h4>' . Translation::get('AcademicYear', array('YEAR' => $year)) . '</h4>';
            $html[] = $this->renderProgressTable($year);
        }

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    private function renderProgressTable($year)
    {
        $progressData = $this->getProgressData($year);

        $tableColumns = array();
        $tableColumns[] = new SortableStaticTableColumn(Translation::get('Faculty'));
        $tableColumns[] = new SortableStaticTableColumn(Translation::get('Training'));
        $tableColumns[] = new SortableStaticTableColumn(Translation::get('Progress'));

        $sortableTable = new SortableTableFromArray(
            $progressData,
            $tableColumns,
            array(),
            0,
            200,
            SORT_ASC,
            'progress',
            false,
            false);

        return $sortableTable->toHtml();
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

    private function getProgressData($year)
    {
        $scheduledGroups = $this->getCalendarService()->getScheduledGroupsByYear($year);
        $progressData = array();

        $previousFacultyIdentifier = false;

        while ($scheduledGroup = $scheduledGroups->next_result())
        {
            $row = array();

            if ($previousFacultyIdentifier !== $scheduledGroup[ScheduledGroup::PROPERTY_FACULTY_ID])
            {
                $previousFacultyIdentifier = $scheduledGroup[ScheduledGroup::PROPERTY_FACULTY_ID];
                $row[] = $scheduledGroup[ScheduledGroup::PROPERTY_FACULTY_NAME];
            }
            else
            {
                $row[] = '&nbsp;';
            }

            $row[] = $scheduledGroup[ScheduledGroup::PROPERTY_TRAINING_NAME];
            $row[] = $this->getBar(
                $scheduledGroup[ScheduledGroup::PROPERTY_COUNT_SCHEDULED],
                $scheduledGroup[ScheduledGroup::PROPERTY_COUNT_TO_BE_SCHEDULED]);

            $progressData[] = $row;
        }

        return $progressData;
    }

    private function getBar($status, $total)
    {
        $percent = $status / $total * 100;

        $html = array();

        if ($percent >= 100)
        {
            $percent = 100;
        }

        if ($percent == 100)
        {
            $class = 'progress-bar-success';
        }
        elseif ($percent >= 50)
        {
            $class = 'progress-bar-warning';
        }
        else
        {
            $class = 'progress-bar-danger';
        }

        $displayPercent = floor($percent);

        $html[] = '<div class="progress" style="margin-bottom: 0px;">';
        $html[] = '<div class="progress-bar progress-bar-striped ' . $class . '" role="progressbar" aria-valuenow="' .
             $displayPercent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $displayPercent .
             '%; min-width: 2em;">';
        $html[] = $displayPercent . '%';
        $html[] = '</div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}