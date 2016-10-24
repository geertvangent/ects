<?php
namespace Ehb\Application\Ects\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Ehb\Application\Ects\Manager;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Iterator\DataClassIterator;

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
        $objects = array();
        $objects[] = new User(array(User::PROPERTY_USERNAME => 'Donald'));
        $objects[] = new User(array(User::PROPERTY_USERNAME => 'Scrooge'));
        $objects[] = new User(array(User::PROPERTY_USERNAME => 'Flintheart'));
        $objects[] = new User(array(User::PROPERTY_USERNAME => 'Goldie'));

        $result = new DataClassIterator($objects);

//         $object = new \ArrayObject( $objects );
//         $result = $object->getIterator();

        echo '<strong>PART I</strong><br /><br />';

        echo $result->getCurrentEntryPositionType() . '<br /><br />';

        foreach ($result as $user)
        {

            var_dump($user);
            echo '<br />';
            echo $result->getCurrentEntryPositionType() . '<br /><br />';
        }

        exit();

        $html = array();

        $html[] = $this->getJavascript();

        $html[] = '<style>';
        $html[] = '.ects-course-credits {
	text-align: center;
	width: 100px;
}

.ects-link {
	cursor: pointer;
}

.ects-link-disabled {
	cursor: not-allowed;
}

.ects-course-details {
	width: 100%;
	min-height: 800px;
}

#ctl00_ctl00_cphGeneral_cphMain_divGegevensOpleiding {
	display: none !important;
}

.ects-course-summary {
	font-size: 12px !important;
}

.DetailxOLODBovenLijn {
	position: relative !important;
	display: block !important;
	margin-bottom: .75rem !important;
	background-color: #fff !important;
	border-radius: .25rem !important;
	border: 1px solid rgba(0, 0, 0, .125) !important;
	padding: 1.25rem !important;
}

.DetailxOLODTitelTekst {
	padding: .75rem 1.25rem !important;
	background-color: #f5f5f5 !important;
	border-bottom: 1px solid rgba(0, 0, 0, .125) !important;
	margin: -1.25rem -1.25rem 0 -1.25rem !important;
	color: #373a3c !important;
	font-weight: normal !important;
	border-radius: .25rem .25rem 0 0 !important;
}

table.DetailxOLODtable {
	border-collapse: collapse !important;
	border-spacing: 0px !important;
	max-width: 100% !important;
	margin-bottom: 1rem !important;
}

.DetailxOLODtable td, .DetailxOLODtable th {
	padding: .75rem !important;
	vertical-align: top !important;
	border-top: 1px solid #eceeef !important
}

.DetailxOLODtable .DetailxOLODtable {
	background-color: #fff !important
}

.DetailxOLODtable {
	border: 1px solid #eceeef !important
}

.DetailxOLODtable td, .DetailxOLODtable th {
	border: 1px solid #eceeef !important
}

.DetailxOLODtable tbody th {
	color: #55595c !important;
	background-color: #eceeef !important
}';

        $html[] = '</style>';

        $html[] = '<link rel="stylesheet" href="' . Theme::getInstance()->getCssPath(Manager::context(), true) .
             'BaMaFlexWeb.css">';

        $html[] = '<div ng-app="trainingBrowserApp" ng-controller="MainController as mainController">';

        $html[] = '<div ng-view></div>';

        $html[] = $this->getTrainingSelectorPanel();
        $html[] = $this->getTrainingDetailsPanel();
        $html[] = $this->getTrajectoryDetailsPanel();
        $html[] = $this->getCourseDetailsPanel();

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    private function getJavascript()
    {
        $html = array();

        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'Angular/angular.min.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'Angular/angular-route.min.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) . 'Angular/angular-sanitize.min.js');
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
                 'TrainingBrowser/Controller/CourseDetailsController.js');
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
                 'TrainingBrowser/Directive/courseDetailsPanel.js');
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

        $html[] = '<nav class="breadcrumb">';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'\')">Studiegids</a>';
        $html[] = '</nav>';

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
        $html[] = '                             ng-model="trainingSelector.filterAcademicYear"';
        $html[] = '                             ng-options="item for item in trainingSelector.academicYears"';
        $html[] = '                             ng-change="trainingSelector.selectAcademicYear()">';
        $html[] = '                    </select>';

        $html[] = '                </div>';
        $html[] = '            </div>';
        $html[] = '            <div class="col-xs-12 col-lg-5">';
        $html[] = '                <div class="form-group">';
        $html[] = '                    <label for="faculty">Departement</label>';

        $html[] = '                    <select  class="form-select form-control" id="faculty"';
        $html[] = '                             ng-model="trainingSelector.filterFaculty"';
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
        $html[] = '                             ng-model="trainingSelector.filterTrainingType"';
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

        $html[] = '<div class="card card-block" ng-repeat="type in trainingCards.trainingsByType" ng-hide="trainingCards.isLoadingTrainingList">';
        $html[] = '    <h5 class="card-title">{{ type.type }}</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span ng-repeat="training in type.training">';
        $html[] = '            <i class="fa fa-angle-double-right" aria-hidden="true"></i>';
        $html[] = '            &nbsp;&nbsp;';
        $html[] = '            <a class="ects-link text-primary" ng-click="mainController.goToPath(\'training/\' + training.id)">{{ training.name }}</a>';
        $html[] = '            <br />';
        $html[] = '        </span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block" ng-show="trainingCards.trainingsByType.length == 0 && trainingCards.isLoadingTrainingList == false">';
        $html[] = '    <h5 class="card-title">Geen resultaten</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span>Er werden helaas geen opleidingen gevonden die aan je zoekcritera voldoen.</span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block" ng-show="trainingCards.isLoadingTrainingList">';
        $html[] = '    <p class="card-text text-xs-center text-muted">';
        $html[] = '        <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>';
        $html[] = '        <span class="sr-only">Loading...</span>';
        $html[] = '        <br />';
        $html[] = '        <h6 class="text-xs-center text-muted">Opleidingen worden geladen</h6>';
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

        $html[] = '<div class="card card-block" ng-show="trainingDetails.isLoadingTraining">';
        $html[] = '    <p class="card-text text-xs-center text-muted">';
        $html[] = '        <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>';
        $html[] = '        <span class="sr-only">Loading...</span>';
        $html[] = '        <br />';
        $html[] = '        <h6 class="text-xs-center text-muted">Opleiding wordt geladen</h6>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div ng-hide="trainingDetails.isLoadingTraining">';

        $html[] = '<nav class="breadcrumb">';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'\')">Studiegids</a>';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'training/\' + trainingDetails.training.id)">{{ trainingDetails.training.name }}</a>';
        $html[] = '</nav>';

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<h3 class="text-primary m-b-2">{{ trainingDetails.training.name }}</h3>';

        $html[] = '<div class="card m-b-2">';

        $html[] = '    <div class="card-header">Algemeen</div>';
        $html[] = '    <div class="card-block">';
        $html[] = '        <span class="text-muted">Academiejaar:</span> {{ trainingDetails.training.year }}<br />';
        $html[] = '        <span class="text-muted">Departement:</span> {{ trainingDetails.training.faculty }}<br />';
        $html[] = '        <span class="text-muted">Domein:</span> {{ trainingDetails.training.domain }}<br />';
        $html[] = '        <span class="text-muted">Studieomvang in studiepunten:</span> {{ trainingDetails.training.credits }}<br />';
        $html[] = '    </div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Trajectlijst
        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<div class="card">';

        $html[] = '    <div class="card-header">Opleidingstrajecten</div>';

        $html[] = '    <div class="card-block" ng-repeat="trajectory in trainingDetails.training.trajectories">';
        $html[] = '        <h5 class="card-title">{{ trajectory.name }}</h5>';
        $html[] = '        <p class="card-text">';
        $html[] = '            <span ng-repeat="subTrajectory in trajectory.trajectories">';
        $html[] = '                <i class="fa fa-angle-double-right" aria-hidden="true"></i>';
        $html[] = '                &nbsp;&nbsp;';
        $html[] = '                <a class="ects-link text-primary" ng-click="mainController.goToPath(\'trajectory/\' + subTrajectory.id)">{{ subTrajectory.name }}</a>';
        $html[] = '                <br />';
        $html[] = '            </span>';
        $html[] = '        </p>';
        $html[] = '    </div>';

        $html[] = '</div>';

        $html[] = '<button type="button" class="btn btn-primary" ng-click="mainController.goToPath(\'\')">';
        $html[] = '    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Terug';
        $html[] = '</button>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</script>';

        return implode(PHP_EOL, $html);
    }

    private function getTrajectoryDetailsPanel()
    {
        $html = array();

        $html[] = '<script type="text/ng-template" id="subTrajectoryDetailsPanel.html">';
        $html[] = '<div sub-trajectory-details-panel>';

        $html[] = '<div class="card card-block" ng-show="subTrajectoryDetails.isLoadingSubTrajectory">';
        $html[] = '    <p class="card-text text-xs-center text-muted">';
        $html[] = '        <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>';
        $html[] = '        <span class="sr-only">Loading...</span>';
        $html[] = '        <br />';
        $html[] = '        <h6 class="text-xs-center text-muted">Traject wordt geladen</h6>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div ng-hide="subTrajectoryDetails.isLoadingSubTrajectory">';

        $html[] = '<nav class="breadcrumb">';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'\')">Studiegids</a>';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'training/\' + subTrajectoryDetails.subTrajectory.training.id)">{{ subTrajectoryDetails.subTrajectory.training.name }}</a>';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'training/\' + subTrajectoryDetails.subTrajectory.sub_trajectory.id)">{{ subTrajectoryDetails.subTrajectory.sub_trajectory.name }}</a>';
        $html[] = '</nav>';

        $html[] = '<div class="row">';
        $html[] = '    <div class="col-sm-12">';
        $html[] = '        <h3 class="text-primary m-b-2">Programma {{ subTrajectoryDetails.subTrajectory.training.name }}<br /><small class="text-muted">({{ subTrajectoryDetails.subTrajectory.sub_trajectory.name }})</small></h3>';
        $html[] = '        <table class="table table-bordered">';
        $html[] = '            <thead>';
        $html[] = '                <tr>';
        $html[] = '                    <th class="ects-course-name">Opleidingsonderdeel</th>';
        $html[] = '                    <th class="ects-course-credits"><i class="fa fa-graduation-cap" aria-hidden="true" title="Studiepunten"></i></th>';
        $html[] = '                </tr>';
        $html[] = '            </thead>';
        $html[] = '            <tbody>';
        $html[] = '                <tr ng-repeat="course in subTrajectoryDetails.subTrajectory.courses">';
        $html[] = '                    <td class="ects-course-name" ng-if="course.approved == 1">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">';
        $html[] = '                            &nbsp;&nbsp;&#8226;&nbsp;';
        $html[] = '                            <a class="ects-link text-primary" ng-click="mainController.goToPath(\'course/\' + course.programme_id)">';
        $html[] = '                            {{ course.name }}';
        $html[] = '                            </a>';
        $html[] = '                        </small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">';
        $html[] = '                            <a class="ects-link text-primary" ng-click="mainController.goToPath(\'course/\' + course.programme_id)">';
        $html[] = '                            {{ course.name }}';
        $html[] = '                            </a>';
        $html[] = '                        </span>';
        $html[] = '                    </td>';
        $html[] = '                    <td class="ects-course-name" ng-if="course.approved == 0">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">&nbsp;&nbsp;&#8226;&nbsp;';
        $html[] = '                            <span class="ects-link-disabled">{{ course.name }}</span>';
        $html[] = '                        </small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null" class="ects-link-disabled">';
        $html[] = '                            {{ course.name }}';
        $html[] = '                        </span>';
        $html[] = '                    </td>';
        $html[] = '                    <td class="ects-course-credits">';
        $html[] = '                        <small class="text-muted" ng-if="course.parent_programme_id != null">{{ course.credits }}</small>';
        $html[] = '                        <span ng-if="course.parent_programme_id == null">{{ course.credits }}</span>';
        $html[] = '                    </td>';
        $html[] = '                </tr>';
        $html[] = '            </tbody>';
        $html[] = '        </table>';
        $html[] = '        <button type="button" class="btn btn-primary" ng-click="mainController.goToPath(\'training/\' + subTrajectoryDetails.subTrajectory.training.id)">';
        $html[] = '             <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Terug';
        $html[] = '        </button>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</script>';

        return implode(PHP_EOL, $html);
    }

    private function getCourseDetailsPanel()
    {
        $html = array();

        $html[] = '<script type="text/ng-template" id="courseDetailsPanel.html">';
        $html[] = '<div course-details-panel>';

        $html[] = '<div class="card card-block" ng-show="courseDetails.isLoadingCourse">';
        $html[] = '    <p class="card-text text-xs-center text-muted">';
        $html[] = '        <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>';
        $html[] = '        <span class="sr-only">Loading...</span>';
        $html[] = '        <br />';
        $html[] = '        <h6 class="text-xs-center text-muted">Opleidingsonderdeel wordt geladen</h6>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div ng-hide="courseDetails.isLoadingCourse">';

        $html[] = '<nav class="breadcrumb">';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'\')">Studiegids</a>';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'training/\' + courseDetails.course.training_id)">{{ courseDetails.course.training }}</a>';
        $html[] = '<a class="breadcrumb-item ects-link text-primary" ng-click="mainController.goToPath(\'course/\' + courseDetails.course.id)">{{ courseDetails.course.name }}</a>';
        $html[] = '</nav>';

        $html[] = '<h3 class="text-primary m-b-2">{{ courseDetails.course.name }}</h3>';

        $html[] = '<div class="row">';
        $html[] = '    <div class="col-sm-12">';
        $html[] = '        <div class="card ects-course-summary">';
        $html[] = '            <div class="card-block">';
        $html[] = '                <span class="text-muted">Academiejaar:</span> {{ courseDetails.course.year }}<br />';
        $html[] = '                <span class="text-muted">Departement:</span> {{ courseDetails.course.faculty }}<br />';
        $html[] = '                <span class="text-muted">Opleiding:</span> {{ courseDetails.course.training }}<br />';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '<div class="card card-block" ng-show="courseDetails.isLoadingCourseDetails">';
        $html[] = '    <p class="card-text text-xs-center text-muted">';
        $html[] = '        <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>';
        $html[] = '        <span class="sr-only">Loading...</span>';
        $html[] = '        <br />';
        $html[] = '        <h6 class="text-xs-center text-muted">ECTS fiche wordt geladen</h6>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div ng-hide="courseDetails.isLoadingCourseDetails">';

        $html[] = '<div class="row">';
        $html[] = '    <div class="col-sm-12">';
        $html[] = '        <div ng-bind-html="courseDetails.getCourseDetails()"></div>';
        $html[] = '    </div>';
        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</script>';

        return implode(PHP_EOL, $html);
    }
}
