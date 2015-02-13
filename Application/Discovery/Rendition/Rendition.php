<?php
namespace Ehb\Application\Discovery\Rendition;

use Chamilo\Libraries\Utilities\StringUtilities;

abstract class Rendition
{
    use \Chamilo\Libraries\Architecture\Traits\ClassContext;
    // Formats
    const FORMAT_HTML = 'html';
    const FORMAT_XLSX = 'xlsx';
    const FORMAT_ZIP = 'zip';
    const VIEW_DEFAULT = 'default';

    private $rendition_implementation;

    public function __construct($rendition_implementation)
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

    public static function launch($rendition_implementation)
    {
        return static :: factory($rendition_implementation)->render();
    }

    public static function factory($rendition_implementation)
    {
        $class = static :: context() . '\View\\' .
             StringUtilities :: getInstance()->createString($rendition_implementation->get_format())->upperCamelize() .
             '\\' .
             StringUtilities :: getInstance()->createString($rendition_implementation->get_format())->upperCamelize() .
             StringUtilities :: getInstance()->createString($rendition_implementation->get_view())->upperCamelize() .
             'Rendition';

        return new $class($rendition_implementation);
    }

    /**
     *
     * @return string
     */
    public static function package()
    {
        return static :: context();
    }
}
