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

        $html[] = '<h3 class="text-primary m-b-2">Programma Bachelor in Multimedia en Communicatietechnologie<br /><small>(1 Ba Multimedia en Communicatietechnologie)</small></h3>';

        $html[] = '
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Opleidingsonderdeel</th>
                        <th>Credits</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Audiovisual & IT Principles</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Audiovisual Design</td>
                        <td>5</td>
                    </tr>
                </tbody>
            </table>

            ';

        $html[] = '</div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
