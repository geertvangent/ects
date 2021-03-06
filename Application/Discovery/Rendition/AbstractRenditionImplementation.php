<?php
namespace Ehb\Application\Discovery\Rendition;

use Ehb\Application\Discovery\Module;

abstract class AbstractRenditionImplementation
{

    /**
     *
     * @var \libraries\architecture\Application
     */
    private $context;

    /**
     *
     * @var \application\discovery\Module
     */
    private $module;

    /**
     *
     * @param \libraries\architecture\Application $context
     * @param \application\discovery\Module $module
     */
    public function __construct($context, Module $module)
    {
        $this->context = $context;
        $this->module = $module;
    }

    /**
     *
     * @return \libraries\architecture\Application
     */
    public function get_context()
    {
        return $this->context;
    }

    /**
     *
     * @param \libraries\architecture\Application $context
     */
    public function set_context($context)
    {
        $this->context = $context;
    }

    /**
     *
     * @return \application\discovery\Module
     */
    public function get_module()
    {
        return $this->module;
    }

    /**
     *
     * @param \application\discovery\Module $module
     */
    public function set_module(Module $module)
    {
        $this->module = $module;
    }

    /**
     *
     * @return string
     */
    abstract public function get_view();

    /**
     *
     * @return string
     */
    abstract public function get_format();
}
