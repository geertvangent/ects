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
        $html[] = '</head>';

        $html[] = '<body dir="ltr">';

        $html[] = '<style>';
        $html[] = '.row{margin-bottom: 15px;}';
        $html[] = '</style>';

        $html[] = '<div class="container-fluid">';

        // Filters
        $html[] = '<div class="row">';
        $html[] = '<div class="col-sm-12">';

        // Toolbar
        $html[] = '<div class="btn-toolbar form form-inline" role="toolbar" aria-label="Toolbar with button groups">';
        $html[] = '  <div class="btn-group" role="group">';

        // Academiejaar
        $html[] = '  <div class="btn-group" role="group">';
        $html[] = '    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html[] = '      2016-17';
        $html[] = '    </button>';
        $html[] = '    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
        $html[] = '      <a class="dropdown-item" href="#">2016-17</a>';
        $html[] = '      <a class="dropdown-item" href="#">2015-16</a>';
        $html[] = '    </div>';
        $html[] = '  </div>';

        // Departement
        $html[] = '  <div class="btn-group" role="group">';
        $html[] = '    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html[] = '      Alle departementen';
        $html[] = '    </button>';
        $html[] = '    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
        $html[] = '      <a class="dropdown-item" href="#">Design &amp; Technologie</a>';
        $html[] = '      <a class="dropdown-item" href="#">Management, Media en Maatschappij</a>';
        $html[] = '    </div>';
        $html[] = '  </div>';

        // Types opleidingen
        $html[] = '  <div class="btn-group" role="group">';
        $html[] = '    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html[] = '      Alle opleidingstypes';
        $html[] = '    </button>';
        $html[] = '    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
        $html[] = '      <a class="dropdown-item" href="#">Professioneel gerichte bacheloropleiding</a>';
        $html[] = '      <a class="dropdown-item" href="#">Academisch gerichte bacheloropleiding</a>';
        $html[] = '    </div>';
        $html[] = '  </div>';

        $html[] = '  </div>';

        // Zoekveld
        $html[] = '  <div class="btn-group" role="group">';
        $html[] = '  <div class="form-group">';
        $html[] = '     <label class="sr-only" for="freeText">Vrije zoekfilter</label>';
        $html[] = '     <input type="text" class="form-control" id="freeText" placeholder="Vrije zoekfilter">';
        $html[] = '  </div>';
        $html[] = '  </div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Opleidingslijst

        $html[] = '<div class="row">';
        $html[] = '<div class="col-sm-12">';

        $html[] = '<div class="list-group">';
        $html[] = '<a href="#" class="list-group-item">Dapibus ac facilisis in</a>';
        $html[] = '<a href="#" class="list-group-item">Morbi leo risus</a>';
        $html[] = '<a href="#" class="list-group-item">Porta ac consectetur ac</a>';
        $html[] = '<a href="#" class="list-group-item">Vestibulum at eros</a>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Results

        $html[] = '</div>';
        $html[] = '</body>';

        echo implode(PHP_EOL, $html);
    }
}