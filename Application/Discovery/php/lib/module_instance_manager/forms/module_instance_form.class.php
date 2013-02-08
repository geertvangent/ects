<?php
namespace application\discovery;

use common\libraries\EqualityCondition;
use common\libraries\Filesystem;
use common\libraries\FormValidator;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\Theme;
use common\libraries\Path;
use common\libraries\DynamicFormTabsRenderer;
use common\libraries\DynamicFormTab;
use DOMDocument;

/**
 * $Id: external_repository_form.class.php 227 2009-11-13 14:45:05Z kariboe $
 *
 * @package home.lib.forms
 */
class ModuleInstanceForm extends FormValidator
{
    const TYPE_CREATE = 1;
    const TYPE_EDIT = 2;
    const PROPERTY_ENABLED = 'enabled';
    const SETTINGS_PREFIX = 'settings';

    private $module_instance;

    private $configuration;

    private $form_type;

    function __construct($form_type, $module_instance, $action)
    {
        parent :: __construct('module_instance', 'post', $action);

        $this->module_instance = $module_instance;
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

    function build_basic_form()
    {
        $module_instance = $this->module_instance;
        $configuration = $this->configuration;

        $tabs_generator = new DynamicFormTabsRenderer($this->getAttribute('name'), $this);
        $tabs_generator->add_tab(
                new DynamicFormTab('general', 'General', Theme :: get_common_image_path() . 'place_tab_view.png',
                        'build_general_form'));

        if (count($configuration['settings']) > 0)
        {
            $tabs_generator->add_tab(
                    new DynamicFormTab('settings', 'Settings',
                            Theme :: get_common_image_path() . 'place_tab_settings.png', 'build_settings_form'));
        }

        $tabs_generator->render();
    }

    function build_general_form()
    {
        $this->addElement('hidden', ModuleInstance :: PROPERTY_TYPE, $this->module_instance->get_type());
        $this->addElement('text', ModuleInstance :: PROPERTY_TITLE,
                Translation :: get('Title', null, $this->module_instance->get_type()), array("size" => "50"));
        $this->addRule(ModuleInstance :: PROPERTY_TITLE,
                Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 'required');
        $this->add_html_editor(ModuleInstance :: PROPERTY_DESCRIPTION,
                Translation :: get('Description', null, $this->module_instance->get_type()), true);
        $this->addElement('checkbox', self :: PROPERTY_ENABLED,
                Translation :: get('Enabled', null, Utilities :: COMMON_LIBRARIES));
    }

    function build_settings_form()
    {
        $module_instance = $this->module_instance;
        $configuration = $this->configuration;

        $path = Path :: namespace_to_path($module_instance->get_type());

        $categories = count($configuration['settings']);

        foreach ($configuration['settings'] as $category_name => $settings)
        {
            $has_settings = false;

            foreach ($settings as $name => $setting)
            {
                $label = Translation :: get(Utilities :: underscores_to_camelcase($name), null,
                        $this->module_instance->get_type());
                $name = self :: SETTINGS_PREFIX . '[' . $name . ']';
                if (! $has_settings && $categories > 1)
                {
                    $this->addElement('category',
                            Translation :: get(Utilities :: underscores_to_camelcase($category_name), null,
                                    ModuleInstanceManager :: get_namespace($this->module_instance->get_instance_type(),
                                            $this->module_instance->get_type())));
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

                                $this->addRule($name,
                                        Translation :: get($validation['message'], null,
                                                ModuleInstanceManager :: get_namespace(
                                                        $this->module_instance->get_instance_type(),
                                                        $this->module_instance->get_type())), $validation['rule'],
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
                        $class = $module_instance->get_type() . '\\SettingsConnector';
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
                                $group[] = & $this->createElement($setting['field'], $name, null,
                                        Translation :: get(Utilities :: underscores_to_camelcase($option_name), null,
                                                ModuleInstanceManager :: get_namespace(
                                                        $this->module_instance->get_instance_type(),
                                                        $this->module_instance->get_type())), $option_value);
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

    function build_editing_form()
    {
        $this->build_basic_form();

        $this->addElement('hidden', ModuleInstance :: PROPERTY_ID);

        $buttons[] = $this->createElement('style_submit_button', 'submit',
                Translation :: get('Update', null, Utilities :: COMMON_LIBRARIES), array('class' => 'positive update'));
        $buttons[] = $this->createElement('style_reset_button', 'reset',
                Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    function build_creation_form()
    {
        $this->build_basic_form();

        $buttons[] = $this->createElement('style_submit_button', 'submit',
                Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES), array('class' => 'positive'));
        $buttons[] = $this->createElement('style_reset_button', 'reset',
                Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    function update_module_instance()
    {
        $module_instance = $this->module_instance;
        $values = $this->exportValues();

        $module_instance->set_title($values[ModuleInstance :: PROPERTY_TITLE]);
        $module_instance->set_description($values[ModuleInstance :: PROPERTY_DESCRIPTION]);

        if (isset($values[self :: PROPERTY_ENABLED]))
        {
            $module_instance->set_content_type($module_instance->get_module_type());
        }
        else
        {
            $module_instance->set_content_type(ModuleInstance :: TYPE_DISABLED);
        }

        if (! $module_instance->update())
        {
            return false;
        }
        else
        {
            $settings = $values['settings'];
            $failures = 0;

            foreach ($settings as $name => $value)
            {
                $setting = DiscoveryDataManager :: get_instance()->retrieve_module_instance_setting_from_variable_name(
                        $name, $module_instance->get_id());
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

    function create_module_instance()
    {
        $module_instance = $this->module_instance;
        $values = $this->exportValues();

        $module_instance->set_title($values[ModuleInstance :: PROPERTY_TITLE]);
        $module_instance->set_description($values[ModuleInstance :: PROPERTY_DESCRIPTION]);

        if (isset($values[self :: PROPERTY_ENABLED]))
        {
            $module_instance->set_content_type($module_instance->get_module_type());
        }
        else
        {
            $module_instance->set_content_type(ModuleInstance :: TYPE_DISABLED);
        }

        $display_order = DiscoveryDataManager :: get_instance()->count_module_instances(
                new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_instance->get_content_type()));
        $display_order ++;

        $module_instance->set_display_order($display_order);

        if (! $module_instance->create())
        {
            return false;
        }
        else
        {
            $settings = $values['settings'];
            $failures = 0;

            foreach ($settings as $name => $value)
            {
                $setting = DiscoveryDataManager :: get_instance()->retrieve_module_instance_setting_from_variable_name(
                        $name, $module_instance->get_id());
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
    function setDefaults($defaults = array ())
    {
        $module_instance = $this->module_instance;
        $defaults[ModuleInstance :: PROPERTY_ID] = $module_instance->get_id();
        if (! $module_instance->get_title())
        {
            $defaults[ModuleInstance :: PROPERTY_TITLE] = Translation :: get('TypeName', null,
                    $this->module_instance->get_type());
        }
        else
        {
            $defaults[ModuleInstance :: PROPERTY_TITLE] = $module_instance->get_title();
        }

        $defaults[ModuleInstance :: PROPERTY_TYPE] = $module_instance->get_type();

        if (! $module_instance->get_description())
        {
            $defaults[ModuleInstance :: PROPERTY_DESCRIPTION] = Translation :: get('TypeDescription', null,
                    $this->module_instance->get_type());
        }
        else
        {
            $defaults[ModuleInstance :: PROPERTY_DESCRIPTION] = $module_instance->get_description();
        }

        if ($module_instance->get_title())
        {
            $defaults[self :: PROPERTY_ENABLED] = (int) $module_instance->is_enabled();
        }
        else
        {
            $defaults[self :: PROPERTY_ENABLED] = 1;
        }

        $configuration = $this->configuration;

        foreach ($configuration['settings'] as $category_name => $settings)
        {
            foreach ($settings as $name => $setting)
            {
                $setting = DiscoveryDataManager :: get_instance()->retrieve_module_instance_setting_from_variable_name(
                        $name, $module_instance->get_id());
                if ($setting instanceof ModuleInstanceSetting)
                {
                    $defaults[self :: SETTINGS_PREFIX][$name] = $setting->get_value();
                }
            }
        }

        parent :: setDefaults($defaults);
    }

    function get_module_instance_types()
    {
        $path = Path :: get_common_extensions_path() . 'module_instance_manager/implementation/';
        $folders = Filesystem :: get_directory_content($path, Filesystem :: LIST_DIRECTORIES, false);

        $types = array();
        foreach ($folders as $folder)
        {
            $types[$folder] = Translation :: get('TypeName', null, ModuleInstanceManager :: get_namespace($folder));
        }
        ksort($types);
        return $types;
    }

    function parse_settings()
    {
        $module_instance = $this->module_instance;
        $path = Path :: namespace_to_path($module_instance->get_type());

        $file = Path :: get(SYS_PATH) . $path . '/php/settings/settings.xml';
        $result = array();

        if (file_exists($file))
        {
            $doc = new DOMDocument();
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
                                    $validation_info[] = array('rule' => $validation->getAttribute('rule'),
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
