<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;

/**
 *
 * @package Ehb\Application\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class HtmlComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $html = array();

        // Header
        $html[] = '<!DOCTYPE html>';
        $html[] = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
        $html[] = '<head>';
        $html[] = '<meta charset="utf-8">';
        $html[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $html[] = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $html[] = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">';
        $html[] = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>';
        $html[] = '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>';
        $html[] = '<script src="https://use.fontawesome.com/9f5c1b77a9.js"></script>';
        $html[] = '</head>';

        $html[] = '<body dir="ltr">';

        $html[] = '<style>';
        $html[] = '.row{margin-bottom: 15px;}';
        $html[] = '</style>';

        $html[] = '<div class="container-fluid">';

        // Filters
        $html[] = '<div class="row">';
        $html[] = '<div class="col-sm-12">';

        // Form
        $html[] = '<div class="card card-inverse card-primary card-block">';
        $html[] = '    <div class="card-text form">';
        $html[] = '        <div class="col-sm-1">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="exampleSelect1">Academiejaar</label>';
        $html[] = '                <select class="form-control" id="exampleSelect1">';
        $html[] = '                    <option>2016-17</option>';
        $html[] = '                    <option>2015-16</option>';
        $html[] = '                </select>';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-sm-4">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="exampleSelect1">Departement</label>';
        $html[] = '                <select class="form-control" id="exampleSelect1">';
        $html[] = '                    <option>Design &amp; Technologie</option>';
        $html[] = '                    <option>Management, Media en Maatschappij</option>';
        $html[] = '                </select>';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-sm-3">';
        $html[] = '            <div class="form-group">';
        $html[] = '                <label for="exampleSelect1">Opleidingstype</label>';
        $html[] = '                <select class="form-control" id="exampleSelect1">';
        $html[] = '                    <option>Professioneel gerichte bacheloropleiding</option>';
        $html[] = '                    <option>Academisch gerichte bacheloropleiding</option>';
        $html[] = '                </select>';
        $html[] = '            </div>';
        $html[] = '        </div>';
        $html[] = '        <div class="col-sm-4">';
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
        $html[] = '<div class="col-sm-12">';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Professioneel gerichte bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Biomedische Laboratoriumtechnologie</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Journalistiek</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Landschaps- en Tuinarchitectuur</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Landschaps- en Tuinarchitectuur - Werktraject</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Multimedia en de Communicatietechnologie</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Pedagogie van het Jonge Kind</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Toegepaste Informatica</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Verpleegkunde 180sp</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Verpleegkunde 240sp</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Voedings- en Dieetkunde</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Vroedkunde</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Communicatiemanagement</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Hotelmanagement</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Office Management</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs - Kleuteronderwijs</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs - Lager Onderwijs</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Onderwijs: Secundair Onderwijs 2 OV</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Sociaal Werk</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Sociaal Werk - gezamenlijke opleiding</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Toerisme- en het Recreatiemanagement</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in Idea & Innovation Management</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in Musical</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Academisch gerichte bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in de Muziek</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Bachelor in het Drama</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '<div class="card card-block">';
        $html[] = '    <h5 class="card-title">Masteropleiding die aansluit bij een bacheloropleiding</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in de Audiovisuele Kunsten</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in de Muziek</a><br />';
        $html[] = '        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Master in het Drama</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Results

        $html[] = '</div>';
        $html[] = '</body>';

        echo implode(PHP_EOL, $html);
    }
}