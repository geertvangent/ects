<?php
namespace Ehb\Application\Ects\Service;

use Ehb\Application\Ects\Repository\BaMaFlexRepository;

/**
 *
 * @package Ehb\Application\Ects\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class BaMaFlexService
{

    /**
     *
     * @var \Ehb\Application\Ects\Repository\BaMaFlexRepository
     */
    private $baMaFlexRepository;

    /**
     *
     * @param \Ehb\Application\Ects\Repository\BaMaFlexRepository $baMaFlexRepository
     */
    public function __construct(BaMaFlexRepository $baMaFlexRepository)
    {
        $this->baMaFlexRepository = $baMaFlexRepository;
    }

    /**
     *
     * @return \Ehb\Application\Ects\Repository\BaMaFlexRepository
     */
    public function getBaMaFlexRepository()
    {
        return $this->baMaFlexRepository;
    }

    /**
     *
     * @param \Ehb\Application\Ects\Repository\BaMaFlexRepository $baMaFlexRepository
     */
    public function setBaMaFlexRepository(BaMaFlexRepository $baMaFlexRepository)
    {
        $this->baMaFlexRepository = $baMaFlexRepository;
    }

    /**
     *
     * @return string[]
     */
    public function getYears()
    {
        return $this->getBaMaFlexRepository()->findYears();
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultiesForYear($year)
    {
        return $this->getBaMaFlexRepository()->findFacultiesForYear($year);
    }

    /**
     *
     * @param string $year
     * @param string $facultyIdentifier
     * @return string[][]
     */
    public function getTypesForYearAndFacultyIdentifier($year, $facultyIdentifier = null)
    {
        return $this->getBaMaFlexRepository()->findTypesForYearAndFacultyIdentifier($year, $facultyIdentifier);
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
        return $this->getBaMaFlexRepository()->findTrainingsForYearFacultyIdentifierTypeIdentifierAndText(
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
        return $this->getBaMaFlexRepository()->findTrainingByIdentifier($trainingIdentifier);
    }

    /**
     *
     * @param integer $trainingIdentifier
     * @return string[][]
     */
    public function getTrajectoriesForTrainingIdentifier($trainingIdentifier)
    {
        return $this->getBaMaFlexRepository()->findTrajectoriesForTrainingIdentifier($trainingIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return string[][]
     */
    public function getSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier)
    {
        return $this->getBaMaFlexRepository()->findSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\SubTrajectory
     */
    public function getSubTrajectoryByIdentifier($subTrajectoryIdentifier)
    {
        return $this->getBaMaFlexRepository()->findSubTrajectoryByIdentifier($subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Trajectory
     */
    public function getTrajectoryByIdentifier($trajectoryIdentifier)
    {
        return $this->getBaMaFlexRepository()->findtrajectoryByIdentifier($trajectoryIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet\RecordResultSet
     */
    public function getSubTrajectoryCoursesForSubTrajectoryIdentifier($subTrajectoryIdentifier)
    {
        return $this->getBaMaFlexRepository()->findSubTrajectoryCoursesForSubTrajectoryIdentifier(
            $subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $courseIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Course
     */
    public function getCourseByIdentifier($courseIdentifier)
    {
        return $this->getBaMaFlexRepository()->findCourseByIdentifier($courseIdentifier);
    }

    /**
     *
     * @param integer $courseIdentifier
     * @return string
     */
    public function getCourseDetailsByIdentifier($courseIdentifier)
    {
        return $this->getBaMaFlexRepository()->findCourseDetailsByIdentifier($courseIdentifier);
    }
}