<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Photo\Component;

use Ehb\Application\Weblcms\Tool\Implementation\Photo\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Discovery\AccessAllowedInterface;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Ehb\Application\Weblcms\Tool\Implementation\Photo\Module;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Photo\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements DelegateComponent, AccessAllowedInterface
{

    public function run()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: PROPERTY_TYPE),
            new StaticConditionVariable('Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex'));

        $moduleInstance = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieve(
            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
            new DataClassRetrieveParameters($condition));

        $module = new Module($this, $moduleInstance);

        $view = Request :: get(
            \Ehb\Application\Discovery\Manager :: PARAM_VIEW,
            \Ehb\Application\Discovery\Rendition\Format\HtmlRendition :: VIEW_DEFAULT);

        if ($view == \Ehb\Application\Discovery\Rendition\Format\HtmlRendition :: VIEW_ZIP)
        {
            $rendition = new \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition\Zip\ZipDefaultRenditionImplementation(
                $this,
                $module);
            $file_path = $rendition->render();
            $file_name = Filesystem :: create_safe_name(
                $this->get_course()->get_title() . ' - ' . Translation :: get('UserPhotos')) . '.zip';

            if (Filesystem :: file_send_for_download($file_path, true, $file_name, 'application\zip'))
            {
                Filesystem :: remove($file_path);
                exit();
            }
            else
            {
                throw new \Exception(Translation :: get('FileSendForDownloadFailed'));
            }
        }
        else
        {
            $rendition = new \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition\Html\HtmlDefaultRenditionImplementation(
                $this,
                $module);
            $rendered_module = $rendition->render();

            $link_pattern = '/<a[^>]*>\n<img src="(.*)"[^>]*>\n<\/a>/iU';
            $rendered_module = preg_replace(
                $link_pattern,
                "<img style=\"width: 150px; border: 1px solid grey;\" src=\"$1\">",
                $rendered_module);

            $html = array();

            $html[] = $this->render_header();
            $html[] = $rendered_module;
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }
}