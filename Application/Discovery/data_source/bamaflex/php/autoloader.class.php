<?php
namespace application\discovery\data_source\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Connection' => '/lib/connection.class.php',
         'DataSource' => '/lib/data_source.class.php',
         'DiscoveryDataManager' => '/lib/discovery_data_manager.class.php',
         'History' => '/lib/history.class.php',
         'HistoryReference' => '/lib/history_reference.class.php',
         'MyTemplate' => '/lib/data_class_generator/my_template.class.php',
         'DataClassGenerator' => '/lib/data_class_generator/data_class_generator/data_class_generator.class.php',
         'application\discovery\module\advice\implementation\bamaflex\Advice' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/advice/implementation/bamaflex/php/lib/advice.class.php',
         'application\discovery\module\employment\implementation\bamaflex\Employment' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/employment/implementation/bamaflex/php/lib/employment.class.php',
         'application\discovery\module\employment\implementation\bamaflex\EmploymentParts' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/employment/implementation/bamaflex/php/lib/employment_parts.class.php',
         'application\discovery\module\exemption\implementation\bamaflex\Exemption' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/exemption/implementation/bamaflex/php/lib/exemption.class.php',
         'application\discovery\module\group\implementation\bamaflex\Group' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/group/implementation/bamaflex/php/lib/group.class.php',
         'application\discovery\module\group_user\implementation\bamaflex\GroupUser' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/group_user/implementation/bamaflex/php/lib/group_user.class.php',
         'application\discovery\module\profile\implementation\bamaflex\LearningCredit' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/profile/implementation/bamaflex/php/lib/learning_credit.class.php',
         'application\discovery\module\student_year\implementation\bamaflex\StudentYear' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/student_year/implementation/bamaflex/php/lib/student_year.class.php',
         'application\discovery\module\training\implementation\bamaflex\Group' => '/lib/data_class_generator/xml_schemas/php/application/discovery/module/training/implementation/bamaflex/php/lib/group.class.php',
         'Mdb2DiscoveryDataManager' => '/lib/data_manager/mdb2.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php',
    );

    static function load($classname)
    {
        if (isset(self::$map[$classname]))
        {
            require_once __DIR__ . self::$map[$classname];
            return true;
        }

        return false;
   }

   static function synch($update){
        return \common\libraries\AutoloaderUtilities::synch(__DIR__, __DIR__, $update);
   }

}
?>