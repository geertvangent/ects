<?php
namespace Ehb\Core\Metadata\Relation\Form;

use Ehb\Core\Metadata\Relation\Manager;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Core\Metadata\Relation\Storage\DataClass\Relation;
use Chamilo\Configuration\Storage\DataClass\Language;
use Chamilo\Configuration\Configuration;

/**
 * Form for the element
 */
class RelationForm extends FormValidator
{

    /**
     *
     * @var Relation
     */
    private $relation;

    /**
     * Constructor
     *
     * @param string $form_url
     * @param Relation $relation
     */
    public function __construct(Relation $relation, $form_url)
    {
        parent :: __construct('relation', 'post', $form_url);

        $this->relation = $relation;
        $this->build_form();

        if ($this->relation->is_identified())
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
            Relation :: PROPERTY_NAME,
            Translation :: get('Name', null, Utilities :: COMMON_LIBRARIES));
        $this->addRule(
            Relation :: PROPERTY_NAME,
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

        $defaults[Relation :: PROPERTY_NAME] = $this->relation->get_name();

        foreach ($this->relation->getTranslations() as $isocode => $translation)
        {
            $defaults[Manager :: PROPERTY_TRANSLATION][$isocode] = $translation->get_value();
        }

        $this->setDefaults($defaults);
    }
}