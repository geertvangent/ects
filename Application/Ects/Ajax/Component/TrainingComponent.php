<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Core\Tracking\Storage\DataClass\Event;
use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass\View;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Ehb\Application\Ects\Storage\DataClass\Trajectory;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class TrainingComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_TRAINING = 'training';

    // Properties
    const PROPERTY_TRAJECTORIES = 'trajectories';

    /**
     *
     * @var \Ehb\Application\Ects\Storage\DataClass\Training
     */
    private $currentTraining;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array(self::PARAM_TRAINING);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();

        $jsonAjaxResult->set_properties($this->getTrainingProperties());

        $this->trackView();
        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string
     */
    private function getCurrentTrainingIdentifier()
    {
        if (! isset($this->currentTrainingIdentifier))
        {
            $this->currentTrainingIdentifier = $this->getRequestedPostDataValue(self::PARAM_TRAINING);
        }

        return $this->currentTrainingIdentifier;
    }

    /**
     *
     * @return \Ehb\Application\Ects\Storage\DataClass\Training
     */
    private function getTraining()
    {
        return $this->getBaMaFlexService()->getTrainingByIdentifier($this->getCurrentTrainingIdentifier());
    }

    private function getTrainingProperties()
    {
        $properties = $this->filterProperties(
            $this->getTraining()->get_default_properties(),
            array(
                Training::PROPERTY_ID,
                Training::PROPERTY_YEAR,
                Training::PROPERTY_NAME,
                Training::PROPERTY_FACULTY_ID,
                Training::PROPERTY_FACULTY,
                Training::PROPERTY_DOMAIN_ID,
                Training::PROPERTY_DOMAIN,
                Training::PROPERTY_CREDITS,
                Training::PROPERTY_GOALS));

        $properties[self::PROPERTY_TRAJECTORIES] = $this->getTrajectories();

        return $properties;
    }

    private function getTrajectories()
    {
        $trajectories = $this->getBaMaFlexService()->getTrajectoriesForTrainingIdentifier(
            $this->getCurrentTrainingIdentifier());
        $structuredTrajectories = array();

        foreach ($trajectories as $trajectory)
        {
            $structuredTrajectory = array();
            $structuredTrajectory[Trajectory::PROPERTY_ID] = $trajectory[Trajectory::PROPERTY_ID];
            $structuredTrajectory[Trajectory::PROPERTY_NAME] = $trajectory[Trajectory::PROPERTY_NAME];

            $structuredTrajectory[self::PROPERTY_TRAJECTORIES] = $this->getBaMaFlexService()->getSubTrajectoriesForTrajectoryIdentifier(
                $trajectory[Trajectory::PROPERTY_ID]);

            $structuredTrajectories[] = $structuredTrajectory;
        }

        return $structuredTrajectories;
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
                    View::PROPERTY_ENTITY_TYPE => View::TYPE_TRAINING,
                    View::PROPERTY_ENTITY_ID => $this->getCurrentTrainingIdentifier()));
        }
        catch (\Exception $exception)
        {
        }
    }
}
