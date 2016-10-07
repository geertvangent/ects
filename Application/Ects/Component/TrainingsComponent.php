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
                 'TrainingBrowser/Directive/trainingCardsPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/trainingSelectorPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Service/TrainingsService.js');

        // Filters
        $html[] = '<div ng-app="trainingBrowserApp" ng-controller="MainController as mainController">';

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        // Form
        $html[] = '<div class="card card-inverse card-primary card-block m-b-2" training-selector-panel>';
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

        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12" training-cards-panel>
        {{ trainingSelector.test }}';

        $html[] = '<div class="card card-block" ng-repeat="type in trainingCards.trainingsByType">';
        $html[] = '    <h5 class="card-title">{{ type.type }}</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <span ng-repeat="training in type.training"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">{{ training.name }}</a><br /></span>';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
