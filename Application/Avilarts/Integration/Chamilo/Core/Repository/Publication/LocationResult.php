<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Repository\Publication;

use Chamilo\Core\Repository\Publication\LocationSupport;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package personal_calendar\integration\core\repository\publication
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationResult extends \Chamilo\Core\Repository\Publication\Location\LocationResult
{

    /**
     *
     * @see \core\repository\publication\LocationRenderer::get_header()
     */
    public function get_header()
    {
        $headers = array();
        $headers[] = Translation :: get('Course', null, \Ehb\Application\Avilarts\Manager :: context());
        $headers[] = Translation :: get('Tool', null, \Ehb\Application\Avilarts\Manager :: context());
        return $headers;
    }

    /**
     *
     * @see \core\repository\publication\LocationRenderer::get_location()
     */
    public function get_location(LocationSupport $location)
    {
        $row = array();
        $row[] = $location->get_course_title() . ' (' . $location->get_visual_code() . ')';
        $row[] = $location->get_tool_name();
        return $row;
    }

    /**
     *
     * @see \core\repository\publication\LocationResult::get_link()
     */
    public function get_link(\Chamilo\Core\Repository\Publication\LocationSupport $location, $result)
    {
        $parameters = array();
        $parameters[Application :: PARAM_CONTEXT] = \Ehb\Application\Avilarts\Manager :: context();
        $parameters[\Ehb\Application\Avilarts\Manager :: PARAM_ACTION] = \Ehb\Application\Avilarts\Manager :: ACTION_VIEW_COURSE;
        $parameters[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE] = $location->get_course_id();
        $parameters[\Ehb\Application\Avilarts\Manager :: PARAM_TOOL] = $location->get_tool_id();
        $parameters[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW;
        $parameters[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = $result->get_id();

        $redirect = new Redirect($parameters);
        return $redirect->getUrl();
    }
}