<?php
namespace Ehb\Core\Metadata\Service;

use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Format\Form\FormValidator;
use Ehb\Core\Metadata\Element\Service\ElementService;
use Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstance;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\File\Path;
use Ehb\Core\Metadata\Element\Storage\DataClass\Element;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Ehb\Core\Metadata\Service
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class EntityFormService
{

    /**
     *
     * @var \Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstance
     */
    private $schemaInstance;

    /**
     *
     * @var \Chamilo\Core\Repository\Storage\DataClass\ContentObject
     */
    private $contentObject;

    /**
     *
     * @var \Chamilo\Libraries\Format\Form\FormValidator
     */
    private $formValidator;

    /**
     *
     * @param \Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstance $schemaInstance
     * @param \Chamilo\Core\Repository\Storage\DataClass\ContentObject $contentObject
     * @param \Chamilo\Libraries\Format\Form\FormValidator $formValidator
     */
    public function __construct(SchemaInstance $schemaInstance, ContentObject $contentObject,
        FormValidator $formValidator)
    {
        $this->schemaInstance = $schemaInstance;
        $this->contentObject = $contentObject;
        $this->formValidator = $formValidator;
    }

    /**
     *
     * @return \Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstance
     */
    public function getSchemaInstance()
    {
        return $this->schemaInstance;
    }

    /**
     *
     * @param \Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstancea $schemaInstance
     */
    public function setSchemaInstance($schemaInstance)
    {
        $this->schemaInstance = $schemaInstance;
    }

    /**
     *
     * @return \Chamilo\Core\Repository\Storage\DataClass\ContentObject
     */
    public function getContentObject()
    {
        return $this->contentObject;
    }

    /**
     *
     * @param \Chamilo\Core\Repository\Storage\DataClass\ContentObject $contentObject
     */
    public function setContentObject($contentObject)
    {
        $this->contentObject = $contentObject;
    }

    /**
     *
     * @return \Chamilo\Libraries\Format\Form\FormValidator
     */
    public function getFormValidator()
    {
        return $this->formValidator;
    }

    /**
     *
     * @param \Chamilo\Libraries\Format\Form\FormValidator $formValidator
     */
    public function setFormValidator($formValidator)
    {
        $this->formValidator = $formValidator;
    }

    public function addElements()
    {
        $this->addDependencies();

        $elementService = new ElementService();
        $elements = $elementService->getElementsForSchemaInstance($this->schemaInstance);

        while ($element = $elements->next_result())
        {
            $elementName = EntityService :: PROPERTY_METADATA_SCHEMA . '[' . $this->schemaInstance->get_schema_id() .
                 '][' . $this->schemaInstance->get_id() . '][' . $element->get_id() . ']';
            $this->formValidator->addElement(
                'text',
                $elementName,
                $element->get_display_name(),
                array(
                    'class' => 'metadata-input',
                    'data-schema-id' => $this->schemaInstance->get_schema_id(),
                    'data-schema-instance-id' => $this->schemaInstance->get_id(),
                    'data-element-id' => $element->get_id(),
                    'size' => 50));
//             $this->addJavascript($element);
        }
    }

    /**
     * Adds the dependencies to the form
     */
    private function addDependencies()
    {
        $resource_manager = ResourceManager :: get_instance();
        $plugin_path = Path :: getInstance()->getJavascriptPath('Chamilo\Core\Repository', true) .
             'Plugin/Bootstrap/Tagsinput/';

        $dependencies = array();

        $dependencies[] = $resource_manager->get_resource_html($plugin_path . 'bootstrap-typeahead.js');
        $dependencies[] = $resource_manager->get_resource_html($plugin_path . 'bootstrap-tagsinput.min.js');
        $dependencies[] = $resource_manager->get_resource_html($plugin_path . 'bootstrap-tagsinput.css');
        $dependencies[] = $resource_manager->get_resource_html(
            Path :: getInstance()->getJavascriptPath('Ehb\Core\Metadata', true) . 'metadataInput.js');

        $this->formValidator->addElement('html', implode(PHP_EOL, $dependencies));
    }

    /**
     * Adds the javascript to the form
     *
     * @param array $available_tags
     */
    protected function addJavascript(Element $element, $available_tags = array())
    {
        $json = json_encode($available_tags);

        $urlRenderer = new Redirect(
            array(
                Application :: PARAM_CONTEXT => \Ehb\Core\Metadata\Vocabulary\Ajax\Manager :: context(),
                Application :: PARAM_ACTION => \Ehb\Core\Metadata\Vocabulary\Ajax\Manager :: ACTION_VOCABULARY));

        $html = array();

        $html[] = '<script type="text/javascript">';
        $html[] = '$(\'#' . $this->getElementId($element) . '\').tagsinput({';
        $html[] = 'typeahead: {';
        $html[] = 'name: \'tags\',';
        $html[] = 'source: function(query) {
                var options = [];
		var response = $.ajax({
			type : "POST",
			url : \'' . $urlRenderer->getUrl() . '\',
			async : false,
			dataType : "json"
		}).done(
				function(data, textStatus, jqXHR) {
					options = data;
				});
			    return options;
    }';
        $html[] = '}';
        $html[] = '});';
        $html[] = '</script>';

        $this->formValidator->addElement('html', implode(PHP_EOL, $html));
    }

    public function getElementId(Element $element)
    {
        return EntityService :: PROPERTY_METADATA_SCHEMA . '-' . $this->schemaInstance->get_schema_id() . '-' .
             $this->schemaInstance->get_id() . '-' . $element->get_id();
    }

    public function setDefaults()
    {
    }
}
