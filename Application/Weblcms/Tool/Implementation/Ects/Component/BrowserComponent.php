<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Ects\Component;

use Ehb\Application\Weblcms\Tool\Implementation\Ects\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Ehb\Application\Discovery\Module;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Ects\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        $html = array();
        $visual_code = $this->get_course()->get_visual_code();

        if (is_numeric($visual_code))
        {
            Request :: set_get('programme_id', $visual_code);
            Request :: set_get('source', '1');

            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
                    \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: PROPERTY_TYPE),
                new StaticConditionVariable('Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex'));

            $moduleInstance = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieve(
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
                new DataClassRetrieveParameters($condition));

            $module = Module :: factory($this, $moduleInstance);
            $course = $module->get_course();

            if ($course instanceof \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Course &&
                 $course->get_approved() == 1)
            {
                // Don't use the Discovery module, for now
                // $rendered_module = \application\discovery\RenditionImplementation :: launch(
                // $module,
                // \application\discovery\Rendition :: FORMAT_HTML,
                // \application\discovery\Rendition :: VIEW_DEFAULT,
                // $this);

                // $link_pattern = "/<a[^>]*>(.*)<\/a>/iU";
                // $rendered_module = preg_replace($link_pattern, "$1", $rendered_module);

                // // Cleanup of the breadcrumbtrail
                // $trail = BreadcrumbTrail :: get_instance();
                // // Remove course name
                // $trail->remove(- 1);
                // // Remove training name
                // $trail->remove(- 1);
                // // Remove faculty name
                // $trail->remove(- 1);
                // // Remove academic year
                // $trail->remove(- 1);

                // $this->display_header();
                // echo $rendered_module;
                // $this->display_footer();

                $bamaflex_uri = array();
                $bamaflex_uri[] = 'http://bamaflexweb.ehb.be/BMFUIDetailxOLOD.aspx?';
                // Programme ID
                $bamaflex_uri[] = 'a=' . $visual_code;
                $bamaflex_uri[] = '&';
                // Display mode ?
                $bamaflex_uri[] = 'b=5';
                $bamaflex_uri[] = '&';
                // Language
                $bamaflex_uri[] = 'c=1';

                $html[] = $this->render_header();
                $html[] = '<iframe id="ects" style="border: 1px solid #DCDCDC; width: 100%; height: 500px; display block;" src="' .
                     implode('', $bamaflex_uri) . '"></iframe>';
                $html[] = $this->render_footer();
            }
            else
            {
                $html[] = $this->render_header();
                $html[] = Display :: warning_message(Translation :: get('EctsNotAvailableYet'));
                $html[] = $this->render_footer();
            }
        }
        else
        {
            $html[] = $this->render_header();
            $html[] = Display :: warning_message(Translation :: get('EctsOnlyForOfficialCourses'));
            $html[] = $this->render_footer();
        }

        return implode(PHP_EOL, $html);
    }
}