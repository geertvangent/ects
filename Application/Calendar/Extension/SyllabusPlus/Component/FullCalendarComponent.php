<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Type\FullCalendarRenderer;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FullCalendarComponent extends Manager implements DelegateComponent
{

    private $viewRenderer;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $html = array();

        $html[] = $this->render_header();

        $html[] = '<div class="row">';
        $html[] = $this->getViewRenderer()->render();
        $html[] = '</div>';

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return \Chamilo\Libraries\Calendar\Renderer\Type\FullCalendarRenderer
     */
    protected function getViewRenderer()
    {
        if (! isset($this->viewRenderer))
        {
            $this->viewRenderer = new FullCalendarRenderer($this->getCurrentRendererTime());
        }

        return $this->viewRenderer;
    }
}
