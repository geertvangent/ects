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

        $html[] = '<div ng-app="trainingBrowserApp" ng-controller="MainController as mainController">';

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

        $html[] = '<div training-selector-panel ng-show="mainController.selectedTraining == null && mainController.selectedSubTrajectory == null">';

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        // Form
        $html[] = '<div class="card card-inverse card-primary card-block m-b-2">';
        $html[] = '    <div class="card-text form">';
        $html[] = '        <div class="col-xs-12 col-lg-2">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="academicYear">Academiejaar</label>';

        $html[] = '                <select  class="form-select form-control" id="academicYear"';
        $html[] = '                         ng-model="trainingSelector.academicYear"';
        $html[] = '                         ng-options="item for item in trainingSelector.academicYears"';
        $html[] = '                         ng-change="trainingSelector.selectAcademicYear(trainingSelector.academicYear)">';
        $html[] = '                </select>';

        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-xs-12 col-lg-5">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="faculty">Departement</label>';

        $html[] = '                <select  class="form-select form-control" id="faculty"';
        $html[] = '                         ng-model="trainingSelector.faculty"';
        $html[] = '                         ng-options="faculty.faculty for faculty in trainingSelector.faculties"';
        $html[] = '                         ng-change="trainingSelector.selectFaculty()">';
        $html[] = '                     <option value="">-- Alle departementen --</option>';
        $html[] = '                </select>';

        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-xs-12 col-lg-5">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="trainingType">Opleidingstype</label>';

        $html[] = '                <select  class="form-select form-control" id="trainingType"';
        $html[] = '                         ng-model="trainingSelector.trainingType"';
        $html[] = '                         ng-options="trainingType.type for trainingType in trainingSelector.trainingTypes"';
        $html[] = '                         ng-change="trainingSelector.selectTrainingType()">';
        $html[] = '                     <option value="">-- Alle opleidingstypes --</option>';
        $html[] = '                </select>';

        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-xs-12">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="exampleInputEmail1">Filter</label>';
        $html[] = '                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Vrije zoekfilter">';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Opleidingslijst

        $html[] = '<div class="row" training-cards-panel>';
        $html[] = '<div class="col-xs-12">
        {{ trainingSelector.test }}';

        $html[] = '<div class="card card-block" ng-repeat="type in trainingCards.trainingsByType">';
        $html[] = '    <h5 class="card-title">{{ type.type }}</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span ng-repeat="training in type.training"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a class="text-primary" ng-click="mainController.selectTraining(training)">{{ training.name }}</a><br /></span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    private function getTrainingDetailsPanel()
    {
        $html = array();

        $html[] = '<div training-details-panel ng-show="mainController.selectedTraining && !mainController.selectedSubTrajectory">';

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
        $html[] = '            <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a class="text-primary" ng-click="mainController.selectSubTrajectory(subTrajectory)">{{ subTrajectory.name }}</a><br />';
        $html[] = '        </span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '<script>$(\'#myTab a\').click(function (e) {';
        $html[] = '     e.preventDefault()';
        $html[] = '     $(this).tab(\'show\')';
        $html[] = '})</script>';

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    private function getTrajectoryDetailsPanel()
    {
        $html = array();

        $html[] = '<div sub-trajectory-details-panel ng-show="mainController.selectedSubTrajectory">';

        $html[] = '<style>';
        $html[] = '.ects-course-credits';
        $html[] = '{';
        $html[] = '    text-align: center;';
        $html[] = '    width: 100px;';
        $html[] = '}';
        $html[] = '</style>';

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
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">&nbsp;&nbsp;&#8226;&nbsp;{{ course.name }}</small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">{{ course.name }}</span>';
        $html[] = '                    </td>';
        $html[] = '                    <td class="ects-course-credits">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">{{ course.credits }}</small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">{{ course.credits }}</span>';
        $html[] = '                    </td>';
        $html[] = '                </tr>';
        $html[] = '            </tbody>';
        $html[] = '        </table>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
