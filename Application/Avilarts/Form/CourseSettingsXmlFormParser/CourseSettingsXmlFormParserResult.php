<?php
namespace Ehb\Application\Avilarts\Form\CourseSettingsXmlFormParser;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Format\Form\XmlFormParser;
use Chamilo\Libraries\Format\Form\XmlFormParserResult;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Interfaces\FormLockedSettingsSupport;

/**
 * This class extends the common settings xml form parser result to parse course settings with locked and frozen
 * elements
 * 
 * @package \application\weblcms;
 * @author Sven Vanpoucke - Hogeschool Gent
 */
class CourseSettingsXmlFormParserResult extends XmlFormParserResult
{

    /**
     * The xml parser
     * 
     * @var CourseSettingsXmlFormParser
     */
    private $course_settings_xml_form_parser;

    /**
     * **************************************************************************************************************
     * Inherited functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Constructor
     * 
     * @param $settings_xml_form_parser CourseSettingsXmlFormParser
     */
    public function __construct(CourseSettingsXmlFormParser $course_settings_xml_form_parser)
    {
        parent :: __construct();
        
        $this->course_settings_xml_form_parser = $course_settings_xml_form_parser;
    }

    /**
     * Adds a given element to the form
     * 
     * @param $form FormValidator
     * @param HTML_QuickForm_element
     */
    protected function add_element_fo_form(FormValidator $form, $element)
    {
        parent :: add_element_fo_form($form, $element);
        
        $element_name = $element->getName();
        
        if ($form instanceof FormLockedSettingsSupport && $element->_type != XmlFormParser :: ELEMENT_TYPE_HTML &&
             $element_name != 'course_settings[category]' && $element_name != 'course_settings[titular]')
        {
            $element_label = $element->getLabel();
            
            $form->addElement(
                XmlFormParser :: ELEMENT_TYPE_CHECKBOX, 
                CourseSettingsController :: SETTING_PARAM_LOCKED_PREFIX . $element_name, 
                Translation :: get('SettingLocked', array('SETTING' => $element_label)), 
                '', 
                array('class' => 'no-iphone-style'), 
                '1', 
                '0');
        }
        
        if ($this->course_settings_xml_form_parser->is_element_frozen($element_name))
        {
            $element->freeze();
        }
    }
}
