<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\NotAllowedException;
use common\libraries\Utilities;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        if (! $this->get_application()->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        $html = array();

        $module_type = $this->get_module()->get_module_type();
        if ($module_type)
        {
            $module_class_name = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($module_type) . 'Data';

            $filters = $module_class_name :: get_filters();

            $form = new FilterForm(
                $this,
                $module_class_name,
                $filters,
                $this->get_application()->get_url(array(Module :: PARAM_MODULE_TYPE => $module_type)));
            $html[] = $form->display();
        }
        else
        {
            foreach (Module :: get_module_types() as $type)
            {
                $html[] = '<a href="' . $this->get_application()->get_url(array(Module :: PARAM_MODULE_TYPE => $type)) .
                     '">';
                $html[] = '<div class="create_block" style="background-image: url(' . Theme :: get_image_path() . 'type/' .
                     $type . '.png);">';
                $html[] = Translation :: get(Utilities :: underscores_to_camelcase($type) . 'Component');
                $html[] = '</div>';
                $html[] = '</a>';
            }
        }

        return implode("\n", $html);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
