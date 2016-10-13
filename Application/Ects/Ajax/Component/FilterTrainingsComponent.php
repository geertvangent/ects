<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Core\Tracking\Storage\DataClass\Event;
use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass\Search;
use Ehb\Application\Ects\Storage\DataClass\Training;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FilterTrainingsComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_YEAR = 'year';
    const PARAM_FACULTY = 'faculty';
    const PARAM_TYPE = 'type';
    const PARAM_TEXT = 'text';

    // Properties
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_TYPE = 'type';

    /**
     *
     * @var string
     */
    private $currentYear;

    /**
     *
     * @var string
     */
    private $currentFacultyIdentifier;

    /**
     *
     * @var string
     */
    private $currentTypeIdentifier;

    /**
     *
     * @var string
     */
    private $currentText;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array(self::PARAM_YEAR);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();
        $jsonAjaxResult->set_properties(array(self::PROPERTY_TYPE => $this->getTrainings()));

        Event::trigger(
            'Search',
            \Ehb\Application\Ects\Manager::context(),
            array(
                Search::PROPERTY_SESSION_ID => session_id(),
                Search::PROPERTY_DATE => time(),
                Search::PROPERTY_YEAR => $this->getCurrentYear(),
                Search::PROPERTY_FACULTY => $this->getCurrentFacultyIdentifier(),
                Search::PROPERTY_TYPE => $this->getCurrentTypeIdentifier(),
                Search::PROPERTY_TEXT => $this->getCurrentText()));

        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string
     */
    private function getCurrentYear()
    {
        if (! isset($this->currentYear))
        {
            $this->currentYear = $this->getRequestedPostDataValue(self::PARAM_YEAR);
        }

        return $this->currentYear;
    }

    /**
     *
     * @return string
     */
    private function getCurrentFacultyIdentifier()
    {
        if (! isset($this->currentFacultyIdentifier))
        {
            $this->currentFacultyIdentifier = $this->getRequestedPostDataValue(self::PARAM_FACULTY);
        }

        return $this->currentFacultyIdentifier;
    }

    /**
     *
     * @return string
     */
    private function getCurrentTypeIdentifier()
    {
        if (! isset($this->currentTypeIdentifier))
        {
            $this->currentTypeIdentifier = $this->getRequestedPostDataValue(self::PARAM_TYPE);
        }

        return $this->currentTypeIdentifier;
    }

    /**
     *
     * @return string
     */
    private function getCurrentText()
    {
        if (! isset($this->currentText))
        {
            $this->currentText = $this->getRequestedPostDataValue(self::PARAM_TEXT);
        }

        return $this->currentText;
    }

    /**
     *
     * @return string[]
     */
    private function getTrainings()
    {
        $trainings = $this->getEctsService()->getTrainingsForYearFacultyIdentifierTypeIdentifierAndText(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier(),
            $this->getCurrentTypeIdentifier(),
            $this->getCurrentText());

        $categorizedTrainings = array();

        foreach ($trainings as $training)
        {
            if (! isset($categorizedTrainings[$training[Training::PROPERTY_TYPE_ID]]))
            {
                $categorizedTrainings[$training[Training::PROPERTY_TYPE_ID]] = array(
                    Training::PROPERTY_TYPE_ID => $training[Training::PROPERTY_TYPE_ID],
                    Training::PROPERTY_TYPE => $training[Training::PROPERTY_TYPE],
                    self::PROPERTY_TRAINING => array());
            }

            $categorizedTrainings[$training[Training::PROPERTY_TYPE_ID]][self::PROPERTY_TRAINING][] = $training;
        }

        return $categorizedTrainings;
    }

    /**
     *
     * @return string[]
     */
    private function getCurrentFaculty()
    {
        foreach ($this->getFaculties() as $faculty)
        {
            if ($faculty[Training::PROPERTY_FACULTY_ID] == $this->getCurrentFacultyIdentifier())
            {
                return $faculty;
            }
        }

        return null;
    }

    /**
     *
     * @return string[]
     */
    private function getCurrentType()
    {
        foreach ($this->getTypes() as $type)
        {
            if ($type[Training::PROPERTY_TYPE_ID] == $this->getCurrentTypeIdentifier())
            {
                return $type;
            }
        }

        return null;
    }
}
