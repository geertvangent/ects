<?php
namespace application\discovery;

use common\libraries\Utilities;

abstract class Rendition
{
    const FORMAT_HTML = 'html';
    const FORMAT_XLSX = 'xlsx';
    const VIEW_DEFAULT = 'default';

    private $rendition_implementation;

    function __construct($rendition_implementation)
    {
        $this->rendition_implementation = $rendition_implementation;
    }

    /**
     *
     * @return the $rendition_implementation
     */
    public function get_rendition_implementation()
    {
        return $this->rendition_implementation;
    }

    /**
     *
     * @param $rendition_implementation the $rendition_implementation to set
     */
    public function set_rendition_implementation($rendition_implementation)
    {
        $this->rendition_implementation = $rendition_implementation;
    }

    /**
     *
     * @return the $context
     */
    public function get_context()
    {
        return $this->rendition_implementation->get_context();
    }

    /**
     *
     * @param $context the $context to set
     */
    public function set_context($context)
    {
        $this->rendition_implementation->set_context($context);
    }

    /**
     *
     * @return the $module
     */
    public function get_module()
    {
        return $this->rendition_implementation->get_module();
    }

    /**
     *
     * @param $module the $module to set
     */
    public function set_module($module)
    {
        $this->rendition_implementation->set_module($module);
    }

    static function launch($rendition_implementation)
    {
        return self :: factory($rendition_implementation)->render();
    }

    static function factory($rendition_implementation)
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($rendition_implementation->get_format()) . Utilities :: underscores_to_camelcase(
                $rendition_implementation->get_view()) . 'Rendition';
        return new $class($rendition_implementation);
    }
}
?>