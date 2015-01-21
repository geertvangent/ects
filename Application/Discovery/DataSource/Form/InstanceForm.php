<?php
namespace Chamilo\Application\Discovery\DataSource\Form;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Format\Tabs\DynamicFormTabsRenderer;
use Chamilo\Libraries\Format\Tabs\DynamicFormTab;
use Chamilo\Application\Discovery\DataSource\DataClass\InstanceSetting;
use Chamilo\Application\Discovery\DataSource\DataClass\Instance;
use Chamilo\Application\Discovery\DataSource\DataManager;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class InstanceForm extends FormValidator
{
    const TYPE_CREATE = 1;
    const TYPE_EDIT = 2;
    const PROPERTY_ENABLED = 'enabled';
    const SETTINGS_PREFIX = 'settings';

    private $instance;

    private $configuration;

    private $form_type;

    public function __construct($form_type, $instance, $action)
    {
        parent :: __construct('instance', 'post', $action);

        $this->instance = $instance;
        $this->configuration = $this->parse_settings();
        $this->form_type = $form_type;
        if ($this->form_type == self :: TYPE_EDIT)
        {
            $this->build_editing_form();
        }
        elseif ($this->form_type == self :: TYPE_CREATE)
        {
            $this->build_creation_form();
        }

        $this->setDefaults();
    }

    public function build_basic_form()
    {
        $instance = $this->instance;
        $configuration = $this->configuration;

        $tabs_generator = new DynamicFormTabsRenderer($this->getAttribute('name'), $this);
        $tabs_generator->add_tab(
            new DynamicFormTab(
                'general',
                'General',
                Theme :: getInstance()->getCommonImagePath() . 'place_tab_view.png',
                'build_general_form'));

        if (count($configuration['settings']) > 0)
        {
            $tabs_generator->add_tab(
                new DynamicFormTab(
                    'settings',
                    'Settings',
                    Theme :: getInstance()->getCommonImagePath() . 'place_tab_settings.png',
                    'build_settings_form'));
        }

        $tabs_generator->render();
    }

    public function build_general_form()
    {
        $this->addElement('hidden', Instance :: PROPERTY_TYPE, $this->instance->get_type());
        $this->addElement(
            'text',
            Instance :: PROPERTY_NAME,
            Translation :: get('Name', null, $this->instance->get_type()),
            array("size" => "50"));
        $this->addRule(
            Instance :: PROPERTY_NAME,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');
        $this->add_html_editor(
            Instance :: PROPERTY_DESCRIPTION,
            Translation :: get('Description', null, $this->instance->get_type()),
            true);
    }

    public function build_settings_form()
    {
        $instance = $this->instance;
        $configuration = $this->configuration;

        $categories = count($configuration['settings']);

        foreach ($configuration['settings'] as $category_name => $settings)
        {
            $has_settings = false;

            foreach ($settings as $name => $setting)
            {
                $label = Translation :: get(
                    Utilities :: underscores_to_camelcase($name),
                    null,
                    $this->instance->get_type());
                $name = self :: SETTINGS_PREFIX . '[' . $name . ']';
                if (! $has_settings && $categories > 1)
                {
                    $this->addElement(
                        'category',
                        Translation :: get(
                            Utilities :: underscores_to_camelcase($category_name),
                            null,
                            $this->instance->get_type()));
                    $has_settings = true;
                }

                if ($setting['locked'] == 'true')
                {
                    $this->addElement('static', $name, $label);
                }
                elseif ($setting['field'] == 'text')
                {
                    $this->add_textfield($name, $label, ($setting['required'] == 'true'));

                    $validations = $setting['validations'];
                    if ($validations)
                    {
                        foreach ($validations as $validation)
                        {
                            if ($this->is_valid_validation_method($validation['rule']))
                            {
                                if ($validation['rule'] != 'regex')
                                {
                                    $validation['format'] = NULL;
                                }

                                $this->addRule(
                                    $name,
                                    Translation :: get($validation['message'], null, $this->instance->get_type()),
                                    $validation['rule'],
                                    $validation['format']);
                            }
                        }
                    }
                }
                elseif ($setting['field'] == 'html_editor')
                {
                    $this->add_html_editor($name, $label, ($setting['required'] == 'true'));
                }
                elseif ($setting['field'] == 'password')
                {
                    $this->add_password($name, $label, ($setting['required'] == 'true'));
                }
                else
                {
                    $options_type = $setting['options']['type'];
                    if ($options_type == 'dynamic')
                    {
                        $options_source = $setting['options']['source'];
                        $class = $instance->get_type() . '\\SettingsConnector';
                        $options = call_user_func(array($class, $options_source));
                    }
                    else
                    {
                        $options = $setting['options']['values'];
                    }

                    if ($setting['field'] == 'radio' || $setting['field'] == 'checkbox')
                    {
                        $group = array();
                        foreach ($options as $option_value => $option_name)
                        {
                            if ($setting['field'] == 'checkbox')
                            {
                                $group[] = & $this->createElement($setting['field'], $name, null, null, $option_value);
                            }
                            else
                            {
                                $group[] = & $this->createElement(
                                    $setting['field'],
                                    $name,
                                    null,
                                    Translation :: get(
                                        Utilities :: underscores_to_camelcase($option_name),
                                        null,
                                        $this->instance->get_type()),
                                    $option_value);
                            }
                        }
                        $this->addGroup($group, $name, $label, '<br/>', false);
                    }
                    elseif ($setting['field'] == 'select')
                    {
                        $this->addElement('select', $name, $label, $options);
                    }
                }
            }

            if ($has_settings && $categories > 1)
            {
                $this->addElement('category');
            }
        }
    }

    public function build_editing_form()
    {
        $this->build_basic_form();

        $this->addElement('hidden', Instance :: PROPERTY_ID);

        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Update', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'positive update'));
        $buttons[] = $this->createElement(
            'style_reset_button',
            'reset',
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    public function build_creation_form()
    {
        $this->build_basic_form();

        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'positive'));
        $buttons[] = $this->createElement(
            'style_reset_button',
            'reset',
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    public function update_instance()
    {
        $instance = $this->instance;
        $values = $this->exportValues();

        $instance->set_name($values[Instance :: PROPERTY_NAME]);
        $instance->set_description($values[Instance :: PROPERTY_DESCRIPTION]);

        if (! $instance->update())
        {
            return false;
        }
        else
        {
            $settings = $values['settings'];
            $failures = 0;

            foreach ($settings as $name => $value)
            {
                $setting = DataManager :: retrieve_instance_setting_from_variable_name($name, $instance->get_id());

                $setting->set_value($value);

                if (! $setting->update())
                {
                    $failures ++;
                }
            }

            if ($failures > 0)
            {
                return false;
            }
        }

        return true;
    }

    public function create_instance()
    {
        $instance = $this->instance;
        $values = $this->exportValues();

        $instance->set_name($values[Instance :: PROPERTY_NAME]);
        $instance->set_description($values[Instance :: PROPERTY_DESCRIPTION]);

        if (! $instance->create())
        {
            return false;
        }
        else
        {
            $settings = $values['settings'];
            $failures = 0;

            foreach ($settings as $name => $value)
            {
                $setting = DataManager :: retrieve_instance_setting_from_variable_name($name, $instance->get_id());

                $setting->set_value($value);

                if (! $setting->update())
                {
                    $failures ++;
                }
            }

            if ($failures > 0)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Sets default values. Traditionally, you will want to extend this method so it sets default for your learning
     * object type's additional properties.
     *
     * @param $defaults array Default values for this form's parameters.
     */
    public function setDefaults($defaults = array ())
    {
        $instance = $this->instance;
        $defaults[Instance :: PROPERTY_ID] = $instance->get_id();
        if (! $instance->get_name())
        {
            $defaults[Instance :: PROPERTY_NAME] = Translation :: get('TypeName', null, $this->instance->get_type());
        }
        else
        {
            $defaults[Instance :: PROPERTY_NAME] = $instance->get_name();
        }

        $defaults[Instance :: PROPERTY_TYPE] = $instance->get_type();

        if (! $instance->get_description())
        {
            $defaults[Instance :: PROPERTY_DESCRIPTION] = Translation :: get(
                'TypeDescription',
                null,
                $this->instance->get_type());
        }
        else
        {
            $defaults[Instance :: PROPERTY_DESCRIPTION] = $instance->get_description();
        }

        if ($instance->is_identified())
        {
            $configuration = $this->configuration;

            foreach ($configuration['settings'] as $category_name => $settings)
            {
                foreach ($settings as $name => $setting)
                {
                    $setting = DataManager :: retrieve_instance_setting_from_variable_name($name, $instance->get_id());

                    if ($setting instanceof InstanceSetting)
                    {
                        $defaults[self :: SETTINGS_PREFIX][$name] = $setting->get_value();
                    }
                }
            }
        }

        parent :: setDefaults($defaults);
    }

    public function parse_settings()
    {
        $instance = $this->instance;

        $file = ClassnameUtilities :: getInstance()->namespaceToFullPath($instance->get_type()) . '/php/settings/settings.xml';
       
        $result = array();

        if (file_exists($file))
        {
            $doc = new \DOMDocument();
            $doc->load($file);
            $object = $doc->getElementsByTagname('package')->item(0);
            $name = $object->getAttribute('name');

            // Get categories
            $categories = $doc->getElementsByTagname('category');
            $settings = array();

            foreach ($categories as $index => $category)
            {
                $category_name = $category->getAttribute('name');
                $category_properties = array();

                // Get settings in category
                $properties = $category->getElementsByTagname('setting');
                $attributes = array('field', 'default', 'locked');

                foreach ($properties as $index => $property)
                {
                    $property_info = array();

                    foreach ($attributes as $index => $attribute)
                    {
                        if ($property->hasAttribute($attribute))
                        {
                            $property_info[$attribute] = $property->getAttribute($attribute);
                        }
                    }

                    if ($property->hasChildNodes())
                    {
                        $property_options = $property->getElementsByTagname('options')->item(0);

                        if ($property_options)
                        {
                            $property_options_attributes = array('type', 'source');

                            foreach ($property_options_attributes as $index => $options_attribute)
                            {
                                if ($property_options->hasAttribute($options_attribute))
                                {
                                    $property_info['options'][$options_attribute] = $property_options->getAttribute(
                                        $options_attribute);
                                }
                            }

                            if ($property_options->getAttribute('type') == 'static' && $property_options->hasChildNodes())
                            {
                                $options = $property_options->getElementsByTagname('option');
                                $options_info = array();
                                foreach ($options as $option)
                                {
                                    $options_info[$option->getAttribute('value')] = $option->getAttribute('name');
                                }
                                $property_info['options']['values'] = $options_info;
                            }
                        }

                        $property_validations = $property->getElementsByTagname('validations')->item(0);

                        if ($property_validations)
                        {
                            if ($property_validations->hasChildNodes())
                            {
                                $validations = $property_validations->getElementsByTagname('validation');
                                $validation_info = array();
                                foreach ($validations as $validation)
                                {
                                    $validation_info[] = array(
                                        'rule' => $validation->getAttribute('rule'),
                                        'message' => $validation->getAttribute('message'),
                                        'format' => $validation->getAttribute('format'));
                                }
                                $property_info['validations'] = $validation_info;
                            }
                        }
                    }
                    $category_properties[$property->getAttribute('name')] = $property_info;
                }

                $settings[$category_name] = $category_properties;
            }

            $result['name'] = $name;
            $result['settings'] = $settings;
        }

        return $result;
    }

    private function is_valid_validation_method($validation_method)
    {
        $available_validation_methods = array('regex', 'email', 'lettersonly', 'alphanumeric', 'numeric', 'required');
        return in_array($validation_method, $available_validation_methods);
    }
}
