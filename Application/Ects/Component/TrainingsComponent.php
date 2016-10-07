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
                 'TrainingBrowser/Controller/TrainingSelectorController.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Directive/trainingSelectorPanel.js');
        $html[] = ResourceManager::get_instance()->get_resource_html(
            Path::getInstance()->getJavascriptPath(Manager::context(), true) .
                 'TrainingBrowser/Service/TrainingsService.js');

        // Filters
        $html[] = '<div class="row" ng-app="trainingBrowserApp" ng-controller="MainController as mainController">';
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
        $html[] = '<div class="col-xs-12">';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Professioneel gerichte bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Biomedische Laboratoriumtechnologie</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Journalistiek</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Landschaps- en Tuinarchitectuur</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Landschaps- en Tuinarchitectuur - Werktraject</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Multimedia en de Communicatietechnologie</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Pedagogie van het Jonge Kind</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Toegepaste Informatica</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Verpleegkunde 180sp</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Verpleegkunde 240sp</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Voedings- en Dieetkunde</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Vroedkunde</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Communicatiemanagement</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Hotelmanagement</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Office Management</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs - Kleuteronderwijs</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs - Lager Onderwijs</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs: Secundair Onderwijs 2 OV</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Sociaal Werk</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Sociaal Werk - gezamenlijke opleiding</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Toerisme- en het Recreatiemanagement</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in Idea & Innovation Management</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in Musical</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Academisch gerichte bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Muziek</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Drama</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Masteropleiding die aansluit bij een bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in de Muziek</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in het Drama</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
