<?php
namespace Ehb\Application\Discovery\DataSource\Bamaflex\DataClassGenerator;

use Ehb\Application\Discovery\DataSource\Bamaflex\DataClassGenerator\DataClassGenerator\DataClassGenerator;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;

ini_set('include_path', realpath(dirname(__FILE__) . '/../../../../../../../common/libraries/plugin/pear'));
require_once dirname(__FILE__) . '/../../../../../../../common/global.inc.php';

$data_class_generator = new DataClassGenerator();

$xml_path = dirname(__FILE__) . '/xml_schemas/';
$xml_files = Filesystem :: get_directory_content($xml_path, Filesystem :: LIST_FILES, false);

foreach ($xml_files as $xml_file)
{
    if ($xml_file != 'placeholder')
    {
        $xml_file_path = $xml_path . $xml_file;
        log_message('Start generating content object for: ' . $xml_file);
        log_message('Retrieving properties');
        
        $dom_document = new \DOMDocument('1.0', 'UTF-8');
        $dom_document->load($xml_file_path);
        $dom_xpath = new \DOMXPath($dom_document);
        
        $content_object = $dom_xpath->query('/object')->item(0);
        
        if (file_exists(
            dirname(__FILE__) . '/xml_schemas/php/' .
                 ClassnameUtilities :: getInstance()->namespaceToPath($content_object->getAttribute('namespace')) .
                 '/php/lib/' . $content_object->getAttribute('name') . '.class.php'))
        {
            Filesystem :: remove(
                dirname(__FILE__) . '/xml_schemas/php/' .
                 ClassnameUtilities :: getInstance()->namespaceToPath($content_object->getAttribute('namespace')) .
                 '/php/lib/' . $content_object->getAttribute('name') . '.class.php');
            log_message('Object type already exists');
        }
        
        // dump($xml_definition);
        log_message('Generating data class');
        $data_class_generator->generate_data_class($dom_xpath);
        
        echo '<hr />';
    }
}

exit();

/**
 * Create folders for the application
 * 
 * @param $location String - The location of the application
 * @param $name String - The name of the application
 */
function create_folder($name)
{
    $location = Path :: get_repository_path() . 'lib/content_object/';
    Filesystem :: create_dir($location . $name);
}

/**
 * Move a file from the root to the install folder
 * 
 * @param $file String - Path of the file
 * @return String $new_file - New path of the file
 */
function move_file($name)
{
    $old_file = dirname(__FILE__) . '/xml_schemas/' . $name . '.xml';
    $new_file = Path :: get_repository_path() . 'lib/content_object/' . $name . '/install/' . $name . '.xml';
    Filesystem :: copy_file($old_file, $new_file);
    return $new_file;
}

/**
 * Retrieves the properties from a data xml file
 * 
 * @param $file String - The xml file
 * @return Array of String - The properties
 */
function retrieve_properties_from_xml_file($file)
{
    $name = '';
    $properties = array();
    $indexes = array();
    
    $doc = new \DOMDocument();
    $doc->load($file);
    $object = $doc->getElementsByTagname('object')->item(0);
    $name = $object->getAttribute('name');
    $namespace = $object->getAttribute('namespace');
    $xml_properties = $doc->getElementsByTagname('property');
    $attributes = array('type', 'length', 'unsigned', 'notnull', 'default', 'autoincrement', 'fixed');
    foreach ($xml_properties as $index => $property)
    {
        $property_info = array();
        foreach ($attributes as $index => $attribute)
        {
            if ($property->hasAttribute($attribute))
            {
                $property_info[$attribute] = $property->getAttribute($attribute);
            }
        }
        $properties[$property->getAttribute('name')] = $property_info;
    }
    $xml_indexes = $doc->getElementsByTagname('index');
    foreach ($xml_indexes as $key => $index)
    {
        $index_info = array();
        $index_info['type'] = $index->getAttribute('type');
        $index_properties = $index->getElementsByTagname('indexproperty');
        foreach ($index_properties as $subkey => $index_property)
        {
            $index_info['fields'][$index_property->getAttribute('name')] = array(
                'length' => $index_property->getAttribute('length'));
        }
        $indexes[$index->getAttribute('name')] = $index_info;
    }
    $result = array();
    $result['name'] = $name;
    $result['namespace'] = $namespace;
    $result['properties'] = $properties;
    $result['indexes'] = $indexes;
    
    return $result;
}

/**
 * Log a message to the screen
 * 
 * @param $message String - The message
 */
function log_message($message)
{
    $total_message = date('[H:m:s] ') . $message . '<br />';
    echo $total_message;
}
