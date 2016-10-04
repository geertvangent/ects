<?php
namespace Ehb\Application\Ects\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
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

        // Opleidingsinformatie
        $html[] = '<div class="row">';
        $html[] = '<div class="col-sm-12">';

        $html[] = '<h3 class="text-primary m-b-2">Programma Bachelor in Multimedia en Communicatietechnologie<br /><small class="text-muted">(1 Ba Multimedia en Communicatietechnologie)</small></h3>';

        $html[] = '
            <style>
            .ects-course-credits
            {
                text-align: center;
                width: 100px;
            }
            </style>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="ects-course-name">Opleidingsonderdeel</th>
                        <th class="ects-course-credits"><i class="fa fa-graduation-cap" aria-hidden="true" title="Studiepunten"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="ects-course-name">Semester 1 Artistieke vorming</td><td class="ects-course-credits">9</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Esthetica, kleurenleer</small></td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Tuin- en bouwkunstgeschiedenis</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Vormleer</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Waarnemings- en voorstellingstechnieken</td><td class="ects-course-credits"><small class="text-muted">3</td></tr>
                    <tr><td class="ects-course-name">Semester 1 Atelier: ontwerpintroductie - tuin</td><td class="ects-course-credits">3</td></tr>
                    <tr><td class="ects-course-name">Semester 1 Atelier: ontwerpleer + vormgeving LTA1</td><td class="ects-course-credits">4</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Ontwerpintroductie - vormgeving</td><td class="ects-course-credits"><small class="text-muted">3</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Ontwerpleer</td><td class="ects-course-credits"><small class="text-muted">1</td></tr>
                    <tr><td class="ects-course-name">Semester 1 Plantenleer</td><td class="ects-course-credits">3</td></tr>
                    <tr><td class="ects-course-name">Semester 1 Technieken</td><td class="ects-course-credits">5</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Constructietechnieken</td><td class="ects-course-credits"><small class="text-muted">3</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Materialenkennis</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name">Semester 1 Wetenschappen</td><td class="ects-course-credits">6</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Aard- en bodemkunde</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Algemene plantkunde</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Ecologie</td><td class="ects-course-credits"><small class="text-muted">1</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S1 Plantenaardrijkskunde</td><td class="ects-course-credits"><small class="text-muted">1</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Artistieke vorming</td><td class="ects-course-credits">5</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Tuin- en bouwkunstgeschiedenis</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Vormleer</td><td class="ects-course-credits"><small class="text-muted">1</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Waarnemings- en voorstellingstechnieken</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Atelier</td><td class="ects-course-credits">4</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Beplantingsleer</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Communicatie & participatie</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Atelier: ontwerpintroductie - landschap</td><td class="ects-course-credits">4</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Atelier: ontwerpintroductie - publieke ruimte</td><td class="ects-course-credits">4</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Plantenleer</td><td class="ects-course-credits">3</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Technieken</td><td class="ects-course-credits">6</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 CAD</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Constructietechnieken</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Topografie</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name">Semester 2 Wetenschappen</td><td class="ects-course-credits">4</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Landschappen en geografie</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                    <tr><td class="ects-course-name"><small class="text-muted">&nbsp;&nbsp;&#8226;&nbsp;S2 Publieke ruimte en sociologie</td><td class="ects-course-credits"><small class="text-muted">2</td></tr>
                </tbody>
            </table>

            ';

        $html[] = '</div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
