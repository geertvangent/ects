<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class CourseDetailsComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_COURSE = 'course';
    
    // Properties
    const PROPERTY_CONTENT = 'content';

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
        $jsonAjaxResult->set_properties(array(self::PROPERTY_CONTENT => $this->getContent()));
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
     * @return string
     */
    private function getContent()
    {
        return $this->getBaMaFlexService()->getCourseDetailsByIdentifier($this->getCurrentCourseIdentifier());
    }
}
