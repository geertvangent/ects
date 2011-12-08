<?php
namespace application\discovery\connection\bamaflex;

use common\libraries\Path;
use common\libraries\Utilities;

/**
 * Dataclass generator used to generate dataclasses with given properties
 * @author Sven Vanpoucke
 */
class DataClassGenerator
{
    private $template;

    /**
     * Constructor
     */
    function __construct()
    {
    }

    /**
     * Generate a dataclass with the given info
     * @param string $location - The location of the class
     * @param string $classname the classname
     * @param array of strings $properties the properties
     * @param string $package the package
     * @param string $description the description
     * @param string $author, the author
     */
    function generate_data_class($dom_xpath)
    {
        $content_object = $dom_xpath->query('/object')->item(0);

        $query = "SELECT column_name, data_type FROM [INFORDATSYNC].[INFORMATION_SCHEMA].[COLUMNS] WHERE table_name LIKE '" . $content_object->getAttribute('table') . "' ORDER BY ordinal_position";

        $connection = Connection :: get_instance(1);
        $statement = $connection->get_connection()->prepare($query);
        $results = $statement->execute();

        $columns = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                if ($result->column_name != 'id')
                {
                    $columns[$result->column_name] = $result->data_type;
                }
            }

            $this->template = new MyTemplate();
            $this->template->set_rootdir(dirname(__FILE__));

            $location = dirname(__FILE__) . '/../xml_schemas/php/';

            if (! is_dir($location))
                mkdir($location, 0777, true);

            if (! is_dir($location . Path :: namespace_to_path($content_object->getAttribute('namespace')) . '/php/lib/'))
                mkdir($location . Path :: namespace_to_path($content_object->getAttribute('namespace')) . '/php/lib/', 0777, true);

            $file = fopen($location . Path :: namespace_to_path($content_object->getAttribute('namespace')) . '/php/lib/' . $content_object->getAttribute('name') . '.class.php', 'w+');

            if ($file)
            {
                $this->template->set_filenames(array('dataclass' => 'data_class.template'));

                $property_names = array();

                $this->template->assign_vars(array(
                        'PACKAGE' => str_replace('\\', '.', $content_object->getAttribute('namespace')),
                        'AUTHOR' => $content_object->getAttribute('author'),
                        'OBJECT_CLASS' => Utilities :: underscores_to_camelcase($content_object->getAttribute('name')),
                        'L_OBJECT_CLASS' => $content_object->getAttribute('name'),
                        'NAMESPACE' => $content_object->getAttribute('namespace')));

                foreach ($columns as $property_name => $property_type)
                {
                    switch ($property_type)
                    {
                        case 'datetime' :
                            $property_type = 'string';
                            break;
                        case 'decimal' :
                            $property_type = 'string';
                            break;
                        case 'float' :
                            $property_type = 'float';
                            break;
                        case 'image' :
                            $property_type = 'string';
                            break;
                        case 'int' :
                            $property_type = 'integer';
                            break;
                        case 'text' :
                            $property_type = 'string';
                            break;
                        case 'varchar' :
                            $property_type = 'string';
                            break;
                    }

                    $property_const = 'PROPERTY_' . strtoupper($property_name);
                    $property_names[] = 'self :: ' . $property_const;

                    $this->template->assign_block_vars("CONSTS", array('PROPERTY_CONST' => $property_const,
                            'PROPERTY_NAME' => $property_name, 'PROPERTY_TYPE' => $property_type));

                    $this->template->assign_block_vars("DEFAULT_PROPERTY", array('PROPERTY_CONST' => $property_const));

                    $this->template->assign_block_vars("PROPERTY", array('PROPERTY_CONST' => $property_const,
                            'PROPERTY_NAME' => $property_name, 'PROPERTY_TYPE' => $property_type));

                    $property_constants = $dom_xpath->query('/object/properties/property[@name="' . $property_name . '"]/option');
                    if ($property_constants->length > 0)
                    {
                        $this->template->assign_block_vars("PROPERTY_CONSTS", array(
                                'NAME' => strtoupper($property_name)));
                        foreach ($property_constants as $property_constant)
                        {
                            $this->template->assign_block_vars("PROPERTY_CONSTS.OPTION", array(
                                    'OPTION' => strtoupper($property_constant->nodeValue),
                                    'VALUE' => $property_constant->getAttribute('id')));
                        }

                        $type_name = str_replace('_id', '', $property_name);

                        $this->template->assign_block_vars("TYPES", array(
                                'NAME' => strtoupper($type_name), 'FUNCTION' => $type_name));
                        foreach ($property_constants as $property_constant)
                        {
                            $this->template->assign_block_vars("TYPES.OPTIONS", array(
                                    'OPTION' => strtoupper($property_constant->nodeValue),
                            		'TRANSLATION' => Utilities::underscores_to_camelcase($type_name) . $property_constant->nodeValue));
                        }

                    }
                }

                $string = $this->template->pparse_return('dataclass');
                fwrite($file, $string);
                fclose($file);
            }
        }
    }
}

?>