<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Storage\DataClass\Course;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class CourseComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_COURSE = 'course';

    /**
     *
     * @var \Ehb\Application\Ects\Storage\DataClass\Training
     */
    private $currentCourseIdentifier;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array(self::PARAM_COURSE);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();
        $jsonAjaxResult->set_properties($this->getCourseProperties());
        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string
     */
    private function getCurrentCourseIdentifier()
    {
        if (! isset($this->currentCourseIdentifier))
        {
            $this->currentCourseIdentifier = $this->getRequestedPostDataValue(self::PARAM_COURSE);
        }

        return $this->currentCourseIdentifier;
    }

    /**
     *
     * @return \Ehb\Application\Ects\Storage\DataClass\Training
     */
    private function getCourse()
    {
        return $this->getEctsService()->getCourseByIdentifier($this->getCurrentCourseIdentifier());
    }

    /**
     * @retur string[]
     */
    private function getCourseProperties()
    {
        return $this->filterProperties(
            $this->getCourse()->get_default_properties(),
            array(
                Course::PROPERTY_ID,
                Course::PROPERTY_YEAR,
                Course::PROPERTY_NAME,
                Course::PROPERTY_FACULTY_ID,
                Course::PROPERTY_FACULTY,
                Course::PROPERTY_TRAINING_ID,
                Course::PROPERTY_TRAINING,
                Course::PROPERTY_PARENT_ID,
                Course::PROPERTY_CREDITS,
                Course::PROPERTY_APPROVED));
    }
}
