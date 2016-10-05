<?php
namespace Ehb\Application\Ects\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Ehb\Application\Ects\Manager;

/**
 *
 * @package Ehb\Application\Ects\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class CoursesComponent extends Manager implements NoAuthenticationSupport
{

    public function renderBody()
    {
        $html = array();

        $trajectoryIdentifier = $this->getRequest()->query->get('trajectory_id', 7063);

        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'TrajectoryRenderer/trajectoryRenderer.js');

        $html[] = '
<style>
.ects-course-credits
{
    text-align: center;
    width: 100px;
}
</style>

<div class="row">
    <div class="col-sm-12" ng-app="trajectoryRendererApp">
        <trajectory-renderer trajectory-id="' . $trajectoryIdentifier . '">
            <h3 class="text-primary m-b-2">Programma {{ mainController.trajectoryProperties.training.name }}<br /><small class="text-muted">({{ mainController.trajectoryProperties.sub_trajectory.name }})</small></h3>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="ects-course-name">Opleidingsonderdeel</th>
                        <th class="ects-course-credits"><i class="fa fa-graduation-cap" aria-hidden="true" title="Studiepunten"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="course in mainController.trajectoryProperties.course">
                        <td class="ects-course-name">
                            <small class="text-muted" ng-if="course.parent_programme_id != null">&nbsp;&nbsp;&#8226;&nbsp;{{ course.name }}</small>
                            <span ng-if="course.parent_programme_id == null">{{ course.name }}</span>
                        </td>
                        <td class="ects-course-credits">
                            <small class="text-muted" ng-if="course.parent_programme_id != null">{{ course.credits }}</small>
                            <span ng-if="course.parent_programme_id == null">{{ course.credits }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </trajectory-renderer>
    </div>
</div>';

        return implode(PHP_EOL, $html);
    }
}
