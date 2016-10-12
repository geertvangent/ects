<?php
namespace Ehb\Application\Ects\Service;

use Ehb\Application\Ects\Repository\EctsRepository;

/**
 *
 * @package Ehb\Application\Ects\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class EctsService
{

    /**
     *
     * @var \Ehb\Application\Ects\Repository\EctsRepository
     */
    private $ectsRepository;

    /**
     *
     * @param \Ehb\Application\Ects\Repository\EctsRepository $ectsRepository
     */
    public function __construct(EctsRepository $ectsRepository)
    {
        $this->ectsRepository = $ectsRepository;
    }

    /**
     *
     * @return \Ehb\Application\Ects\Repository\EctsRepository
     */
    public function getEctsRepository()
    {
        return $this->ectsRepository;
    }

    /**
     *
     * @param \Ehb\Application\Ects\Repository\EctsRepository $ectsRepository
     */
    public function setEctsRepository(EctsRepository $ectsRepository)
    {
        $this->ectsRepository = $ectsRepository;
    }

    /**
     *
     * @return string[]
     */
    public function getYears()
    {
        return $this->getEctsRepository()->findYears();
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultiesForYear($year)
    {
        return $this->getEctsRepository()->findFacultiesForYear($year);
    }

    /**
     *
     * @param string $year
     * @param string $facultyIdentifier
     * @return string[][]
     */
    public function getTypesForYearAndFacultyIdentifier($year, $facultyIdentifier = null)
    {
        return $this->getEctsRepository()->findTypesForYearAndFacultyIdentifier($year, $facultyIdentifier);
    }

    /**
     *
     * @param string $year
     * @param string $facultyIdentifier
     * @param string $typeIdentifier
     * @param string $text
     * @return string[][]
     */
    public function getTrainingsForYearFacultyIdentifierTypeIdentifierAndText($year, $facultyIdentifier = null,
        $typeIdentifier = null, $text = null)
    {
        return $this->getEctsRepository()->findTrainingsForYearFacultyIdentifierTypeIdentifierAndText(
            $year,
            $facultyIdentifier,
            $typeIdentifier,
            $text);
    }

    /**
     *
     * @param integer $trainingIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Training
     */
    public function getTrainingByIdentifier($trainingIdentifier)
    {
        return $this->getEctsRepository()->findTrainingByIdentifier($trainingIdentifier);
    }

    /**
     *
     * @param integer $trainingIdentifier
     * @return string[][]
     */
    public function getTrajectoriesForTrainingIdentifier($trainingIdentifier)
    {
        return $this->getEctsRepository()->findTrajectoriesForTrainingIdentifier($trainingIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return string[][]
     */
    public function getSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier)
    {
        return $this->getEctsRepository()->findSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\SubTrajectory
     */
    public function getSubTrajectoryByIdentifier($subTrajectoryIdentifier)
    {
        return $this->getEctsRepository()->findSubTrajectoryByIdentifier($subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Trajectory
     */
    public function getTrajectoryByIdentifier($trajectoryIdentifier)
    {
        return $this->getEctsRepository()->findtrajectoryByIdentifier($trajectoryIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet\RecordResultSet
     */
    public function getSubTrajectoryCoursesForSubTrajectoryIdentifier($subTrajectoryIdentifier)
    {
        return $this->getEctsRepository()->findSubTrajectoryCoursesForSubTrajectoryIdentifier($subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $courseIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Course
     */
    public function getCourseByIdentifier($courseIdentifier)
    {
        return $this->getEctsRepository()->findCourseByIdentifier($courseIdentifier);
    }
}