<?php
namespace Ehb\Application\Ects\Repository;

use Chamilo\Libraries\Cache\Doctrine\Provider\FilesystemCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Ects\Storage\DataClass\Course;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectory;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectoryCourse;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Ehb\Application\Ects\Storage\DataClass\Trajectory;

/**
 *
 * @package Ehb\Application\Ects\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class BaMaFlexRepository
{
    const LIFETIME_IN_MINUTES = 60;

    /**
     *
     * @return string
     */
    public function findYears()
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new ComparisonCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                ComparisonCondition::GREATER_THAN_OR_EQUAL,
                new StaticConditionVariable('2007-08'));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $years = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    Training::PROPERTY_YEAR,
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                            SORT_DESC))));

            $cache->save($cacheIdentifier, $years, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $year
     * @return string[][]
     */
    public function findFacultiesForYear($year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));
            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $faculties = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Training::PROPERTY_FACULTY_ID, Training::PROPERTY_FACULTY),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $faculties, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $year
     * @param string $facultyIdentifier
     * @return string[][]
     */
    public function findTypesForYearAndFacultyIdentifier($year, $facultyIdentifier = null)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $facultyIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            if (! empty($facultyIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    new StaticConditionVariable($facultyIdentifier));
            }

            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $conditions[] = new NotCondition(
                new InCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    array(14, 16, 18)));

            $types = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Training::PROPERTY_TYPE_ID, Training::PROPERTY_TYPE),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $types, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    public function findTrainingsForYearFacultyIdentifierTypeIdentifierAndText($year, $facultyIdentifier = null,
        $typeIdentifier = null, $text = null)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $facultyIdentifier, $typeIdentifier, $text)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            if (! empty($facultyIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    new StaticConditionVariable($facultyIdentifier));
            }

            if (! empty($typeIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    new StaticConditionVariable($typeIdentifier));
            }

            if (! empty($text))
            {
                $conditions[] = new PatternMatchCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_NAME),
                    '*' . $text . '*');
            }

            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $conditions[] = new NotCondition(
                new InCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    array(14, 16, 18)));

            $types = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(
                        Training::PROPERTY_ID,
                        Training::PROPERTY_NAME,
                        Training::PROPERTY_FACULTY_ID,
                        Training::PROPERTY_FACULTY,
                        Training::PROPERTY_TYPE_ID,
                        Training::PROPERTY_TYPE),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_NAME),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $types, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $trainingIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Training
     */
    public function findTrainingByIdentifier($trainingIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            Training::class_name(),
            $trainingIdentifier);
    }

    /**
     *
     * @param integer $trainingIdentifier
     * @return string[][]
     */
    public function findTrajectoriesForTrainingIdentifier($trainingIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $trainingIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_TRAINING_ID),
                new StaticConditionVariable($trainingIdentifier));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $trajectories = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Trajectory::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Trajectory::PROPERTY_ID, Trajectory::PROPERTY_NAME, Trajectory::PROPERTY_SORT),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_SORT),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $trajectories, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return string[][]
     */
    public function findSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $trajectoryIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_TRAJECTORY_ID),
                new StaticConditionVariable($trajectoryIdentifier));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $trajectories = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                SubTrajectory::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(SubTrajectory::PROPERTY_ID, SubTrajectory::PROPERTY_NAME, SubTrajectory::PROPERTY_SORT),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_SORT),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $trajectories, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\SubTrajectory
     */
    public function findSubTrajectoryByIdentifier($subTrajectoryIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            SubTrajectory::class_name(),
            $subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Trajectory
     */
    public function findTrajectoryByIdentifier($trajectoryIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            Trajectory::class_name(),
            $trajectoryIdentifier);
    }

    public function findSubTrajectoryCoursesForSubTrajectoryIdentifier($subTrajectoryIdentifier)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                SubTrajectoryCourse::class_name(),
                SubTrajectoryCourse::PROPERTY_SUB_TRAJECTORY_ID),
            new StaticConditionVariable($subTrajectoryIdentifier));

        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::records(
            SubTrajectoryCourse::class_name(),
            new RecordRetrievesParameters(
                null,
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            SubTrajectoryCourse::class_name(),
                            SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID),
                        new PropertyConditionVariable(
                            SubTrajectoryCourse::class_name(),
                            SubTrajectoryCourse::PROPERTY_NAME)))));
    }

    /**
     *
     * @param integer $courseIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Course
     */
    public function findCourseByIdentifier($courseIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            Course::class_name(),
            $courseIdentifier);
    }

    /**
     *
     * @param integer $courseIdentifier
     * @return string
     */
    public function findCourseDetailsByIdentifier($courseIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $courseIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $httpClient = new \GuzzleHttp\Client(['base_url' => 'https://bamaflexweb.ehb.be/']);

            $request = $httpClient->createRequest(
                'GET',
                'BMFUIDetailxOLOD.aspx',
                ['query' => ['a' => $courseIdentifier, 'b' => 5, 'c' => 1]]);

            try
            {
                $courseDetailsBody = $httpClient->send($request)->getBody()->getContents();
                $courseDetailsBody = mb_convert_encoding($courseDetailsBody, 'html-entities', 'UTF-8');

                $domDocument = new \DOMDocument();
                $domDocument->loadHTML($courseDetailsBody);

                if ($domDocument->firstChild instanceof \DOMNode)
                {
                    $domXpath = new \DOMXPath($domDocument);
                    $contentNode = $domXpath->query('//div[@id=\'content\']')->item(0);

                    // Replace sources for e.g. images
                    $sourceNodes = $domXpath->query('//*[@src]', $contentNode);

                    foreach ($sourceNodes as $sourceNode)
                    {
                        $newSourceValue = 'https://bamaflexweb.ehb.be/' . $sourceNode->getAttribute('src');
                        $sourceNode->setAttribute('src', $newSourceValue);
                    }

                    // Fix the links to subcourses
                    $subCourseSpanNodes = $domXpath->query('//span[@class="xOLODDetailLink"]', $contentNode);

                    foreach ($subCourseSpanNodes as $subCourseSpanNode)
                    {
                        $onclickValue = $subCourseSpanNode->getAttribute('onclick');
                        preg_match('/a=([0-9]*)&b=5&c=1/', $onclickValue, $matches);

                        $subCourseLinkNode = $domDocument->createElement('a');
                        $subCourseLinkNode->setAttribute('class', 'xOLODDetailLink');
                        $subCourseLinkNode->nodeValue = $subCourseSpanNode->nodeValue;

                        if (isset($matches[1]))
                        {
                            $subCourseLinkNode->setAttribute('href', '#/course/' . $matches[1]);
                        }

                        $subCourseSpanNode->parentNode->replaceChild($subCourseLinkNode, $subCourseSpanNode);
                    }

                    // Fix onclick links
                    $onclickNodes = $domXpath->query('//a[@onclick]', $contentNode);

                    foreach ($onclickNodes as $onclickNode)
                    {
                        $onclickNode->removeAttribute('onclick');
                    }

                    // Fix javascript links
                    $onclickNodes = $domXpath->query('//a[contains(@href, "javascript:")]', $contentNode);

                    foreach ($onclickNodes as $onclickNode)
                    {
                        $onclickNode->removeAttribute('href');
                    }

                    $courseDetails = $domDocument->saveHTML($contentNode);
                }
                else
                {
                    $courseDetails = '';
                }
            }
            catch (\Exception $exception)
            {
                $courseDetails = '';
            }

            $cache->save($cacheIdentifier, $courseDetails, 86400);
        }

        return $cache->fetch($cacheIdentifier);
    }
}