<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectory;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectoryCourse;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Ehb\Application\Ects\Storage\DataClass\Trajectory;

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
    const PROPERTY_COURSE = 'course';

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
            $this->subTrajectory = $this->getEctsService()->getSubTrajectoryByIdentifier(
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
            $this->trajectory = $this->getEctsService()->getTrajectoryByIdentifier(
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
            $this->training = $this->getEctsService()->getTrainingByIdentifier($this->getTrajectory()->getTrainingId());
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
        $subTrajectoryCourses = $this->getEctsService()->getSubTrajectoryCoursesForSubTrajectoryIdentifier(
            $this->getCurrentSubTrajectoryIdentifier());

        $formattedSubTrajectoryCourses = array();

        while ($subTrajectoryCourse = $subTrajectoryCourses->next_result())
        {
            $formattedSubTrajectoryCourse = $this->filterProperties(
                $subTrajectoryCourse,
                array(
                    SubTrajectoryCourse::PROPERTY_ID,
                    SubTrajectoryCourse::PROPERTY_NAME,
                    SubTrajectoryCourse::PROPERTY_CREDITS));

            if (! is_null($subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID]))
            {
                $formattedSubTrajectoryCourses[$subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID]][SubTrajectoryCourse::PROPERTY_COURSE_PARTS][] = $formattedSubTrajectoryCourse;
            }
            else
            {
                $formattedSubTrajectoryCourses[$subTrajectoryCourse[SubTrajectoryCourse::PROPERTY_PROGRAMME_ID]] = $formattedSubTrajectoryCourse;
            }
        }

        return $formattedSubTrajectoryCourses;
    }
}
