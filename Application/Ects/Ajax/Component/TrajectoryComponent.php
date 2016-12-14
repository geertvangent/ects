<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectory;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectoryCourse;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Ehb\Application\Ects\Storage\DataClass\Trajectory;
use Chamilo\Core\Tracking\Storage\DataClass\Event;
use Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass\View;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class TrajectoryComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_SUB_TRAJECTORY = 'sub_trajectory';

    // Properties
    const PROPERTY_TRAJECTORY = 'trajectory';
    const PROPERTY_SUB_TRAJECTORY = 'sub_trajectory';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_COURSE = 'courses';

    /**
     *
     * @var integer
     */
    private $currentSubTrajectoryIdentifier;

    private $subTrajectory;

    private $trajectory;

    private $training;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array(self::PARAM_SUB_TRAJECTORY);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();

        $jsonAjaxResult->set_properties(
            array(
                self::PROPERTY_SUB_TRAJECTORY => $this->getSubTrajectoryProperties(),
                self::PROPERTY_TRAJECTORY => $this->getTrajectoryProperties(),
                self::PROPERTY_TRAINING => $this->getTrainingProperties(),
                self::PROPERTY_COURSE => $this->getSubTrajectoryCourses()));

        $this->trackView();
        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string
     */
    private function getCurrentSubTrajectoryIdentifier()
    {
        if (! isset($this->currentSubTrajectoryIdentifier))
        {
            $this->currentSubTrajectoryIdentifier = $this->getRequestedPostDataValue(self::PARAM_SUB_TRAJECTORY);
        }

        return $this->currentSubTrajectoryIdentifier;
    }

    private function getSubTrajectory()
    {
        if (! isset($this->subTrajectory))
        {
            $this->subTrajectory = $this->getBaMaFlexService()->getSubTrajectoryByIdentifier(
                $this->getCurrentSubTrajectoryIdentifier());
        }

        return $this->subTrajectory;
    }

    private function getSubTrajectoryProperties()
    {
        return $this->filterProperties(
            $this->getSubTrajectory()->get_default_properties(),
            array(SubTrajectory::PROPERTY_ID, SubTrajectory::PROPERTY_NAME));
    }

    private function getTrajectory()
    {
        if (! isset($this->trajectory))
        {
            $this->trajectory = $this->getBaMaFlexService()->getTrajectoryByIdentifier(
                $this->getSubTrajectory()->getTrajectoryId());
        }

        return $this->trajectory;
    }

    private function getTrajectoryProperties()
    {
        return $this->filterProperties(
            $this->getTrajectory()->get_default_properties(),
            array(Trajectory::PROPERTY_ID, Trajectory::PROPERTY_NAME));
    }

    private function getTraining()
    {
        if (! isset($this->training))
        {
            $this->training = $this->getBaMaFlexService()->getTrainingByIdentifier(
                $this->getTrajectory()->getTrainingId());
        }

        return $this->training;
    }

    private function getTrainingProperties()
    {
        return $this->filterProperties(
            $this->getTraining()->get_default_properties(),
            array(
                Training::PROPERTY_ID,
                Training::PROPERTY_NAME,
                Training::PROPERTY_FACULTY_ID,
                Training::PROPERTY_FACULTY));
    }

    private function getSubTrajectoryCourses()
    {
        $subTrajectoryCourses = $this->getBaMaFlexService()->getSubTrajectoryCoursesForSubTrajectoryIdentifier(
            $this->getCurrentSubTrajectoryIdentifier());

        $formattedSubTrajectoryCourses = array();

        foreach ($subTrajectoryCourses as $subTrajectoryCourse)
        {
            $formattedSubTrajectoryCourse = $this->filterProperties(
                $subTrajectoryCourse,
                array(
                    SubTrajectoryCourse::PROPERTY_PROGRAMME_ID,
                    SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID,
                    SubTrajectoryCourse::PROPERTY_NAME,
                    SubTrajectoryCourse::PROPERTY_CREDITS,
                    SubTrajectoryCourse::PROPERTY_APPROVED));

            $formattedSubTrajectoryCourse[SubTrajectoryCourse::PROPERTY_COURSE_PARTS] = array();

            if (! is_null($subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID]))
            {
                $formattedSubTrajectoryCourses[$subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID]][SubTrajectoryCourse::PROPERTY_COURSE_PARTS][] = $formattedSubTrajectoryCourse;
            }
            else
            {
                $formattedSubTrajectoryCourses[$subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PROGRAMME_ID]] = $formattedSubTrajectoryCourse;
            }
        }

        usort(
            $formattedSubTrajectoryCourses,
            function ($courseOne, $courseTwo)
            {
                return strcmp(
                    $courseOne[SubTrajectoryCourse::PROPERTY_NAME],
                    $courseTwo[SubTrajectoryCourse::PROPERTY_NAME]);
            });

        $normalizedSubTrajectoryCourses = array();

        foreach ($formattedSubTrajectoryCourses as $formattedSubTrajectoryCourse)
        {
            $formattedSubTrajectoryCourse[SubTrajectoryCourse::PROPERTY_CREDITS] = floatval(
                $formattedSubTrajectoryCourse[SubTrajectoryCourse::PROPERTY_CREDITS]);

            $normalizedSubTrajectoryCourses[] = $formattedSubTrajectoryCourse;

            if (count($formattedSubTrajectoryCourse[SubTrajectoryCourse::PROPERTY_COURSE_PARTS]) > 0)
            {
                foreach ($formattedSubTrajectoryCourse[SubTrajectoryCourse::PROPERTY_COURSE_PARTS] as $formattedSubTrajectoryCoursePart)
                {
                    $formattedSubTrajectoryCoursePart[SubTrajectoryCourse::PROPERTY_CREDITS] = floatval(
                        $formattedSubTrajectoryCoursePart[SubTrajectoryCourse::PROPERTY_CREDITS]);

                    $normalizedSubTrajectoryCourses[] = $formattedSubTrajectoryCoursePart;
                }
            }
        }

        return $normalizedSubTrajectoryCourses;
    }

    private function trackView()
    {
        try
        {
            Event::trigger(
                'View',
                \Ehb\Application\Ects\Manager::context(),
                array(
                    View::PROPERTY_SESSION_ID => session_id(),
                    View::PROPERTY_DATE => time(),
                    View::PROPERTY_ENTITY_TYPE => View::TYPE_TRAJECTORY,
                    View::PROPERTY_ENTITY_ID => $this->getCurrentSubTrajectoryIdentifier()));
        }
        catch (\Exception $exception)
        {
        }
    }
}
