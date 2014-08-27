<?php
namespace application\discovery;

abstract class AbstractRenditionImplementation
{

    /**
     *
     * @var \libraries\Application
     */
    private $context;

    /**
     *
     * @var \application\discovery\Module
     */
    private $module;

    /**
     *
     * @param \libraries\Application $context
     * @param \application\discovery\Module $module
     */
    public function __construct($context, Module $module)
    {
        $this->context = $context;
        $this->module = $module;
    }

    /**
     *
     * @return \libraries\Application
     */
    public function get_context()
    {
        return $this->context;
    }

    /**
     *
     * @param \libraries\Application $context
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
