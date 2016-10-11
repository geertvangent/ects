<?php
namespace Ehb\Application\Ects\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Ehb\Application\Ects\Manager;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\File\Path;

/**
 *
 * @package Ehb\Application\Ects\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class TrainingsComponent extends Manager implements NoAuthenticationSupport
{

    public function renderBody()
    {
        $html = array();

        $html[] = $this->getJavascript();

        $html[] = '<style>';
        $html[] = '.ects-course-credits';
        $html[] = '{';
        $html[] = '    text-align: center;';
        $html[] = '    width: 100px;';
        $html[] = '}

                   .
            ';
        $html[] = '</style>';

        $html[] = '<div ng-app="trainingBrowserApp" ng-controller="MainController as mainController">';

        $html[] = '<div ng-view></div>';

        $html[] = $this->getTrainingSelectorPanel();
        $html[] = $this->getTrainingDetailsPanel();
        $html[] = $this->getTrajectoryDetailsPanel();

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    private function getJavascript()
    {
        $html = array();

        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'Utilities/chamiloUtilities.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'TrainingBrowser/app.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Controller/MainController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Controller/TrainingCardsController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Controller/TrainingSelectorController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Controller/TrainingDetailsController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Controller/SubTrajectoryDetailsController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/trainingCardsPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/trainingSelectorPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/trainingDetailsPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/subTrajectoryDetailsPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Service/TrainingsService.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Service/CoursesService.js');

        return implode(PHP_EOL, $html);
    }

    private function getTrainingSelectorPanel()
    {
        $html = array();

        $html[] = '<script type="text/ng-template" id="trainingSelectorPanel.html">';
        $html[] = '<div training-selector-panel>';

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        // Form
        $html[] = '<div class="card m-b-2">';
        $html[] = '    <div class="card-header">Zoekcriteria</div>';
        $html[] = '    <div class="card-block">';
        $html[] = '        <div class="card-text form">';
        $html[] = '            <div class="col-xs-12 col-lg-2">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label for="academicYear">Academiejaar</label>';

        $html[] = '                    <select  class="form-select form-control" id="academicYear"';
        $html[] = '                             ng-model="trainingSelector.academicYear"';
        $html[] = '                             ng-options="item for item in trainingSelector.academicYears"';
        $html[] = '                             ng-change="trainingSelector.selectAcademicYear(trainingSelector.academicYear)">';
        $html[] = '                    </select>';

        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '            <div class="col-xs-12 col-lg-5">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label for="faculty">Departement</label>';

        $html[] = '                    <select  class="form-select form-control" id="faculty"';
        $html[] = '                             ng-model="trainingSelector.faculty"';
        $html[] = '                             ng-options="faculty.faculty for faculty in trainingSelector.faculties"';
        $html[] = '                             ng-change="trainingSelector.selectFaculty()">';
        $html[] = '                         <option value="">-- Alle departementen --</option>';
        $html[] = '                    </select>';

        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '            <div class="col-xs-12 col-lg-5">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label for="trainingType">Opleidingstype</label>';

        $html[] = '                    <select  class="form-select form-control" id="trainingType"';
        $html[] = '                             ng-model="trainingSelector.trainingType"';
        $html[] = '                             ng-options="trainingType.type for trainingType in trainingSelector.trainingTypes"';
        $html[] = '                             ng-change="trainingSelector.selectTrainingType()">';
        $html[] = '                         <option value="">-- Alle opleidingstypes --</option>';
        $html[] = '                    </select>';

        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '            <div class="col-xs-12 col-lg-9">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label for="filterText">Filter</label>';
        $html[] = '                    <input   type="text"
                                                class="form-control"
                                                id="filterText"
                                                placeholder="Vrije zoekfilter"
                                                ng-model="trainingSelector.filterText"
                                                ng-change="trainingSelector.setFilterText()"
                                                ng-model-options="{ debounce: 500 }">';
        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '            <div class="col-xs-12 col-lg-3">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label class="hidden-md-down">&nbsp;&nbsp;</label>';
        $html[] = '                    <button class="btn btn-primary form-control" ng-click="trainingSelector.resetFilters()">Wis zoekcriteria</button>';
        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Opleidingslijst

        $html[] = '<div class="row" training-cards-panel>';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<div class="card card-block" ng-repeat="type in trainingCards.trainingsByType">';
        $html[] = '    <h5 class="card-title">{{ type.type }}</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span ng-repeat="training in type.training">';
        $html[] = '            <i class="fa fa-angle-double-right" aria-hidden="true"></i>';
        $html[] = '            &nbsp;&nbsp;';
        $html[] = '            <a class="text-primary" ng-click="mainController.goToPath(\'training/\' + training.id)">{{ training.name }}</a>';
        $html[] = '            <br />';
        $html[] = '        </span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block" ng-show="trainingCards.trainingsByType.length == 0">';
        $html[] = '    <h5 class="card-title">Geen resultaten</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span>Er werden helaas geen opleidingen gevonden die aan je zoekcritera voldoen.</span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</script>';

        return implode(PHP_EOL, $html);
    }

    private function getTrainingDetailsPanel()
    {
        $html = array();

        $html[] = '<script type="text/ng-template" id="trainingDetailsPanel.html">';
        $html[] = '<div training-details-panel>';

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<h3 class="text-primary m-b-2">{{ trainingDetails.selectedTraining.name }}</h3>';

        $html[] = '<div class="card m-b-2">';

        $html[] = '    <div class="card-header">';
        $html[] = '        <ul class="nav nav-tabs card-header-tabs pull-xs-left" role="tablist">';
        $html[] = '            <li class="nav-item">';
        $html[] = '                <a class="nav-link active" data-toggle="tab" href="#general">Algemeen</a>';
        $html[] = '            </li>';
        $html[] = '            <li class="nav-item">';
        $html[] = '                <a class="nav-link" data-toggle="tab" href="#goals">Doelstellingen</a>';
        $html[] = '            </li>';
        $html[] = '        </ul>';
        $html[] = '    </div>';

        $html[] = '    <div class="card-block">';
        $html[] = '        <div class="tab-content">';
        $html[] = '            <div class="tab-pane active" id="general" role="tabpanel">';
        $html[] = '                <span class="text-muted">Academiejaar:</span> {{ trainingDetails.selectedTraining.year }}<br />';
        $html[] = '                <span class="text-muted">Departement:</span> {{ trainingDetails.selectedTraining.faculty }}<br />';
        $html[] = '                <span class="text-muted">Domein:</span> {{ trainingDetails.selectedTraining.domain }}<br />';
        $html[] = '                <span class="text-muted">Studieomvang in studiepunten:</span> {{ trainingDetails.selectedTraining.credits }}<br />';
        $html[] = '            </div>';
        $html[] = '            <div class="tab-pane" id="goals" role="tabpanel">{{ trainingDetails.selectedTraining.goals }}</div>';
        $html[] = '        </div>';
        $html[] = '    </div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Trajectlijst
        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<div class="card">';

        $html[] = '<div class="card-header">';
        $html[] = '    <ul class="nav nav-tabs card-header-tabs pull-xs-left" role="tablist">';
        $html[] = '        <li class="nav-item">';
        $html[] = '            <a class="nav-link active">Opleidingstrajecten</a>';
        $html[] = '        </li>';
        $html[] = '    </ul>';
        $html[] = '</div>';

        $html[] = '<div class="card-block" ng-repeat="trajectory in trainingDetails.selectedTraining.trajectories">';
        $html[] = '    <h5 class="card-title">{{ trajectory.name }}</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span ng-repeat="subTrajectory in trajectory.trajectories">';
        $html[] = '            <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a class="text-primary" ng-click="mainController.goToPath(\'trajectory/\' + subTrajectory.id)">{{ subTrajectory.name }}</a><br />';
        $html[] = '        </span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '        <button type="button" class="btn btn-primary" ng-click="mainController.goToPath(\'\')">';
        $html[] = '             <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Terug';
        $html[] = '        </button>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</script>';

        $html[] = '<script>$(\'#myTab a\').click(function (e) {';
        $html[] = '     e.preventDefault()';
        $html[] = '     $(this).tab(\'show\')';
        $html[] = '})</script>';

        return implode(PHP_EOL, $html);
    }

    private function getTrajectoryDetailsPanel()
    {
        $html = array();

        $html[] = '<script type="text/ng-template" id="subTrajectoryDetailsPanel.html">';
        $html[] = '<div sub-trajectory-details-panel>';

        $html[] = '<div class="row" sub-trajectory-details-panel>';
        $html[] = '    <div class="col-sm-12">';
        $html[] = '        <h3 class="text-primary m-b-2">Programma {{ subTrajectoryDetails.selectedSubTrajectory.training.name }}<br /><small class="text-muted">({{ subTrajectoryDetails.selectedSubTrajectory.sub_trajectory.name }})</small></h3>';
        $html[] = '        <table class="table table-bordered">';
        $html[] = '            <thead>';
        $html[] = '                <tr>';
        $html[] = '                    <th class="ects-course-name">Opleidingsonderdeel</th>';
        $html[] = '                    <th class="ects-course-credits"><i class="fa fa-graduation-cap" aria-hidden="true" title="Studiepunten"></i></th>';
        $html[] = '                </tr>';
        $html[] = '            </thead>';
        $html[] = '            <tbody>';
        $html[] = '                <tr ng-repeat="course in subTrajectoryDetails.selectedSubTrajectory.courses">';
        $html[] = '                    <td class="ects-course-name">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">&nbsp;&nbsp;&#8226;&nbsp;
                                               <a target="_blank" ng-href="https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a={{ course.programme_id }}&amp;b=5&amp;c=1">
                                               {{ course.name }}
                                               </a>
                                           </small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">
                                               <a target="_blank" ng-href="https://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?a={{ course.programme_id }}&amp;b=5&amp;c=1">
                                               {{ course.name }}
                                               </a>
                                           </span>';
        $html[] = '                    </td>';
        $html[] = '                    <td class="ects-course-credits">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">{{ course.credits }}</small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">{{ course.credits }}</span>';
        $html[] = '                    </td>';
        $html[] = '                </tr>';
        $html[] = '            </tbody>';
        $html[] = '        </table>';
        $html[] = '        <button type="button" class="btn btn-primary" ng-click="mainController.goToPath(\'training/\' + subTrajectoryDetails.selectedSubTrajectory.training.id)">';
        $html[] = '             <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Terug';
        $html[] = '        </button>';
        $html[] = '    </div>';
        $html[] = '</div>';
        $html[] = '</script>';

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
