<?php
namespace Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo;

use Chamilo\Application\Discovery\Parameters;
use Chamilo\Libraries\Platform\Request;

class Module extends \Chamilo\Application\Discovery\Module\Elo\Module
{
    const TYPE_CONTENT_OBJECT = 'content_object';
    const TYPE_COURSE_ACCESS = 'course_access';
    const TYPE_COURSE_LIST = 'course_list';
    const TYPE_COURSE_TIME = 'course_time';
    const TYPE_DOCUMENT_LIST = 'document_list';
    const TYPE_LOGIN = 'login';
    const TYPE_PUBLICATION = 'publication';
    
    // Parameters
    const PARAM_MODULE_TYPE = 'module_type';

    public function get_module_type()
    {
        return Request :: get(self :: PARAM_MODULE_TYPE);
    }

    public static function get_module_types()
    {
        return array(
            self :: TYPE_CONTENT_OBJECT, 
            self :: TYPE_COURSE_ACCESS, 
            self :: TYPE_COURSE_LIST, 
            self :: TYPE_COURSE_TIME, 
            self :: TYPE_DOCUMENT_LIST, 
            self :: TYPE_LOGIN, 
            self :: TYPE_PUBLICATION);
    }

    public static function module_parameters()
    {
        return new Parameters();
    }
}
