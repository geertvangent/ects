<?php
namespace Ehb\Core\Metadata\Relation\Form;

use Ehb\Core\Metadata\Relation\Manager;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Core\Metadata\Relation\Storage\DataClass\RelationType;
use Chamilo\Configuration\Storage\DataClass\Language;
use Chamilo\Configuration\Configuration;

/**
 * Form for the element
 */
class RelationTypeForm extends FormValidator
{

    /**
     *
     * @var RelationType
     */
    private $relation_type;

    /**
     * Constructor
     *
     * @param string $form_url
     * @param RelationType $relation_type
     */
    public function __construct(RelationType $relation_type, $form_url)
    {
        parent :: __construct('relation_type', 'post', $form_url);

        $this->relation_type = $relation_type;
        $this->build_form();

        if ($this->relation_type->is_identified())
        {
            $this->set_defaults();
        }
    }

    /**
     * Builds this form
     */
    protected function build_form()
    {
        $this->addElement('category', Translation :: get('General'));

        $this->addElement(
            'text',
            RelationType :: PROPERTY_NAME,
            Translation :: get('Name', null, Utilities :: COMMON_LIBRARIES));
        $this->addRule(
            RelationType :: PROPERTY_NAME,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');

        $this->addElement('category');

        $this->addElement('category', Translation :: get('Translations'));

        $languages = \Chamilo\Libraries\Storage\DataManager\DataManager :: retrieves(Language :: class_name());
        $platformLanguage = Configuration :: get('Chamilo\Core\Admin', 'platform_language');

        while ($language = $languages->next_result())
        {
            $fieldName = \Ehb\Core\Metadata\Relation\Manager :: PROPERTY_TRANSLATION . '[' . $language->get_isocode() .
                 ']';
            $this->addElement('text', $fieldName, $language->get_original_name());

            if ($language->get_isocode() == $platformLanguage)
            {
                $this->addRule(
                    $fieldName,
                    Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
                    'required');
            }
        }

        $this->addElement('category');

        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Save', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'positive'));

        $buttons[] = $this->createElement(
            'style_reset_button',
            'reset',
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets the default values
     *
     * @param Element $element
     */
    protected function set_defaults()
    {
        $defaults = array();

        $defaults[RelationType :: PROPERTY_NAME] = $this->relation_type->get_name();

        foreach ($this->relation_type->getTranslations() as $isocode => $translation)
        {
            $defaults[Manager :: PROPERTY_TRANSLATION][$isocode] = $translation->get_value();
        }

        $this->setDefaults($defaults);
    }
}