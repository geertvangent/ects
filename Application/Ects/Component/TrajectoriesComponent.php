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
class TrajectoriesComponent extends Manager implements NoAuthenticationSupport
{

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function renderBody()
    {
        $html = array();

        // Opleidingsinformatie
        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<h3 class="text-primary m-b-2">Bachelor in Multimedia en Communicatietechnologie</h3>';

        $html[] = '<div class="card m-b-2">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs pull-xs-left" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#general">Algemeen</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#goals">Doelstellingen</a>
      </li>
    </ul>
  </div>
  <div class="card-block">';

        $html[] = '<div class="tab-content">';
        $html[] = '<div class="tab-pane active" id="general" role="tabpanel">';

        $html[] = '    <span class="text-muted">Academiejaar:</span> 2016-17<br />';
        $html[] = '    <span class="text-muted">Departement:</span> Design &amp; Technologie<br />';
        $html[] = '    <span class="text-muted">Domein:</span> Industriële wetenschappen en technologie<br />';
        $html[] = '    <span class="text-muted">Studieomvang in studiepunten:</span> 180<br />';

        $html[] = '    </div>';

        $html[] = '<div class="tab-pane" id="goals" role="tabpanel">';

        $html[] = '        1. Creativiteit.<br />
Creatief zijn, deze creativiteit koppelen aan techniek en inzetten in communicatie naar derden via interactieve multimediale toepassingen en/of presentatievormen.<br />
2. Digitaal bronmateriaal.<br />
Audio- en visueel bronmateriaal aanmaken (via digitale fotografie, digitale video, tekenpakketten, 3D-software en geluidsopnames), bewerken, integreren en incorporeren in geavanceerde multimediale toepassingen en producties, aangepast aan de noden van het doelpubliek.<br />
3. Digitale technologie.<br />
Inschatten van, omgaan met en zich bewust zijn van de rol van nieuwe media, digitale multimediale technologieën, audiovisuele apparatuur en hardware, en naargelang de multimediatoepassing deze aanwenden in een bruikbaar concept.<br />
4. Analyse en ontwerp.<br />
Een multimediaprobleem analyseren en deze analyse omzetten naar een praktisch realiseerbaar, klantgericht en gebruiksvriendelijk implementatie-ontwerp dat rekening houdt met de gestelde vereisten.<br />
5. Productie en implementatie.<br />
Vanuit een technologische informatica-achtergrond multimediaoplossingen uitwerken, programmeren, implementeren, documenteren, testen en beveiligen. <br />
6. Multidisciplinair teamwork.<br />
Systematisch, productief, creatief vernieuwend en met zin voor initiatief handelen en functioneren in een multidisciplinair team in de context van multimediale producties. <br />
7. Projectmanagement.<br />
Ontwerpen, interpreteren, uitvoeren, aanpassen en toelichten van een projectplan zodat opdrachten op projectmatige wijze aangepakt worden en de planningen gerespecteerd worden.<br />
8. Onderzoek en innovatie.<br />
Meewerken aan het initiëren, plannen en uitvoeren van toegepast onderzoek rond innovatieve multimediaoplossingen in diverse omgevingen en hierover rapporteren aan vakgenoten en leken.<br />
9. Communicatie.<br />
Mondeling en schriftelijk communiceren op een correcte en heldere manier over beroepsgerichte informatie, ideeën, problemen en oplossingen met collega\'s, opdrachtgevers en andere belanghebbenden, in het Nederlands, Engels of Frans. <br />
10. Professionele ingesteldheid en attitude.<br />
Reflecteren over de eigen professionele ontwikkeling en beroepshandelingen en op basis daarvan zich verder professionaliseren zodat men optimaal kan functioneren in wisselende omstandigheden.<br />
11. Kwaliteitsvol handelen en ondernemerschap.<br />
Producten en diensten in de verschillende domeinen van de multimediamarkt op basis van kwaliteitskenmerken situeren, evalueren en toelichten, daarbij strevend naar een kwaliteitsvolle taakuitvoering zodat het resultaat voldoet aan de eisen van een steeds wisselende economische en maatschappelijke omgeving.<br />
12. Kritische ingesteldheid.<br />
Alternatieven afwegen, een persoonlijke standpunt innemen en een kritische ingesteldheid tonen bij de formulering en aanpak van een multimediavraagstuk en tegenover maatschappelijke trends en vernieuwingen.<br />
13. Deontologisch handelen. <br />
Zich bewust zijn van de plaats van technologie en techniek in de brede maatschappelijke context, het belang van maatschappelijke en juridische implicaties van het werken met deze technologieën inschatten en deontologisch verantwoord handelen. <br />
14. Professionele contacten.<br />
Professionele interne en externe contacten opbouwen en onderhouden, waarbij kennis en kunde gedeeld worden.';

        $html[] = '</div>';

        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        // Trajectlijst
        $html[] = '<div class="row">';
        $html[] = '<div class="col-xs-12">';

        $html[] = '<div class="card">';

        $html[] = '<h4 class="card-header">Opleidingstrajecten</h4>';

        $html[] = '<div class="card-block">';
        $html[] = '    <h5 class="card-title">Bachelor in Multimedia en Communicatietechnologie</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">1 Ba Multimedia en Communicatietechnologie</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">2 Ba Multimedia en Communicatietechnologie</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">3 Ba Multimedia en Communicatietechnologie</a><br />';
        $html[] = '    </p>';
        $html[] = '    <h5 class="card-title">Bachelor MCT verkort traject</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Ba MCT - Verkort traject 1</a><br />';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Ba MCT - Verkort traject 2</a><br />';
        $html[] = '    </p>';
        $html[] = '    <h5 class="card-title">Bachelor MCT - INT</h5>';
        $html[] = '    <p class="card-text">';
        $html[] = '        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;<a href="#">Inkomende studenten</a><br />';
        $html[] = '    </p>';
        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '<script>$(\'#myTab a\').click(function (e) {
  e.preventDefault()
  $(this).tab(\'show\')
})</script>';

        return implode(PHP_EOL, $html);
    }
}
