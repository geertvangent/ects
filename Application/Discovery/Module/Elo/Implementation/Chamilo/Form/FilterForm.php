<?php
namespace Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\Form;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\Rendition\RenditionImplementation;
use Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeDataFilter;

class FilterForm extends FormValidator
{
    const FILTER_TYPE = 'filter_type';

    private $renderer;

    private $rendition_implementation;

    private $module_class_name;

    private $filters;

    public function __construct(RenditionImplementation $rendition_implementation, $module_class_name, $filters, $url)
    {
        parent :: __construct('repository_filter_form', 'post', $url);

        $this->renderer = clone $this->defaultRenderer();
        $this->rendition_implementation = $rendition_implementation;
        $this->module_class_name = $module_class_name;
        $this->filters = $filters;
        $this->build_form();

        $this->accept($this->renderer);
    }

    /**
     * Build the simple search form.
     */
    private function build_form()
    {
        $this->renderer->setFormTemplate(
            '<form {attributes}><div class="filter_form">{content}</div><div class="clear">&nbsp;</div></form>');
        $this->renderer->setElementTemplate('<div class="row"><div class="formw">{label}&nbsp;{element}</div></div>');

        foreach ($this->filters as $filter)
        {
            $select = $this->addElement(
                'select',
                $filter,
                Translation :: get('Filter' . Utilities :: underscores_to_camelcase($filter)),
                TypeDataFilter :: factory($this->module_class_name)->get_options($filter),
                array('class' => 'postback'));
        }

        $this->addElement(
            'style_submit_button',
            'submit',
            Translation :: get('Filter', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'normal filter'));

        $this->addElement(
            'html',
            \Chamilo\Libraries\Format\Utilities\ResourceManager :: get_instance()->get_resource_html(
                Path :: getInstance()->getConfigurationPath(true) . 'Resources/Javascript/Postback.js'));
    }

    /**
     * Display the form
     */
    public function display()
    {
        $html = array();
        $html[] = '<div style="text-align: right;">';
        $html[] = $this->renderer->toHTML();
        $html[] = '</div>';
        return implode('', $html);
    }
}
