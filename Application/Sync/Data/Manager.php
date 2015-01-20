<?php
namespace Ehb\Application\Sync\Data;

use Chamilo\Libraries\Architecture\Application\Application;

class Manager extends Application
{
    const ACTION_BROWSE = 'browser';
    const ACTION_WEBLCMS = 'weblcms';
    const ACTION_PORTFOLIO = 'portfolio';
    const ACTION_PORTFOLIO_INTRODUCTION = 'portfolio_introduction';
    const ACTION_PORTFOLIO_LOCATION = 'portfolio_location';
    const ACTION_PERSONAL_CALENDAR = 'personal_calendar';
    const ACTION_DOCUMENT_WEBLCMS = 'weblcms_document';
    const ACTION_DOCUMENT_ZIP_WEBLCMS = 'weblcms_document_zip';
    const ACTION_DOCUMENT_REPOSITORY = 'repository_document';
    const ACTION_EXPORT_REPOSITORY = 'repository_exporter';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'data_action';

    public function __construct($user = null, $application = null)
    {
        // Make sure we don't get any timeouts
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        parent :: __construct($user, $application);
    }

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
