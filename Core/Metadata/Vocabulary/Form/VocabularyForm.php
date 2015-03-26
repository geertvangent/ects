<?php
namespace Ehb\Core\Metadata\Vocabulary\Form;

use Ehb\Core\Metadata\Element\Storage\DataClass\Element;
use Ehb\Core\Metadata\Vocabulary\Manager;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Configuration\Storage\DataClass\Language;
use Chamilo\Configuration\Configuration;

/**
 * Form for the element
 */
class VocabularyForm extends FormValidator
{

    /**
     *
     * @var Vocabulary
     */
    private $vocabulary;

    /**
     * Constructor
     *
     * @param string $form_url
     * @param Vocabulary $vocabulary
     */
    public function __construct(Vocabulary $vocabulary, $form_url)
    {
        parent :: __construct('vocabulary', 'post', $form_url);

        $this->vocabulary = $vocabulary;
        $this->build_form();

        if ($this->vocabulary->is_identified())
        {
            $this->set_defaults();
        }
    }

    /**
     * Builds this form
     */
    protected function build_form()
    {
        $element = \Ehb\Core\Metadata\Storage\DataManager :: retrieve_by_id(
            Element :: class_name(),
            $this->vocabulary->get_element_id());

        $this->addElement('category', Translation :: get('General'));
        $this->addElement(
            'static',
            null,
            Translation :: get('Element', null, 'Ehb\Core\Metadata'),
            $element->render_name());

        if ($this->vocabulary->isForEveryone())
        {
            $displayUser = Translation :: get('PredefinedValues', null, 'Ehb\Core\Metadata\Element');
        }
        else
        {
            $user = $this->vocabulary->getUser();

            if ($user instanceof User)
            {
                $displayUser = $user->get_fullname();
            }
            else
            {
                throw new \Exception(Translation :: get('UnknownUser'));
            }
        }

        $this->addElement('static', null, Translation :: get('User', null, 'Ehb\Core\Metadata'), $displayUser);

        $this->addElement(
            'text',
            Vocabulary :: PROPERTY_VALUE,
            Translation :: get('Value', null, Utilities :: COMMON_LIBRARIES));
        $this->addRule(
            Vocabulary :: PROPERTY_VALUE,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');

        $this->addElement('checkbox', Vocabulary :: PROPERTY_DEFAULT_VALUE, Translation :: get('DefaultValue'));

        $this->addElement('category');

        $this->addElement('category', Translation :: get('Translations'));

        $languages = \Chamilo\Libraries\Storage\DataManager\DataManager :: retrieves(Language :: class_name());
        $platformLanguage = Configuration :: get('Chamilo\Core\Admin', 'platform_language');

        while ($language = $languages->next_result())
        {
            $fieldName = \Ehb\Core\Metadata\Vocabulary\Manager :: PROPERTY_TRANSLATION . '[' . $language->get_isocode() .
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

        $defaults[Vocabulary :: PROPERTY_VALUE] = $this->vocabulary->get_value();
        $defaults[Vocabulary :: PROPERTY_DEFAULT_VALUE] = $this->vocabulary->get_default_value();

        foreach ($this->vocabulary->getTranslations() as $isocode => $translation)
        {
            $defaults[Manager :: PROPERTY_TRANSLATION][$isocode] = $translation->get_value();
        }

        $this->setDefaults($defaults);
    }
}