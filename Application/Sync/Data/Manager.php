<?php
namespace Ehb\Application\Sync\Data;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'Browser';
    const ACTION_WEBLCMS = 'Weblcms';
    const ACTION_PORTFOLIO = 'Portfolio';
    const ACTION_PORTFOLIO_INTRODUCTION = 'PortfolioIntroduction';
    const ACTION_PORTFOLIO_LOCATION = 'PortfolioLocation';
    const ACTION_PERSONAL_CALENDAR = 'PersonalCalendar';
    const ACTION_DOCUMENT_WEBLCMS = 'WeblcmsDocument';
    const ACTION_DOCUMENT_ZIP_WEBLCMS = 'WeblcmsDocumentZip';
    const ACTION_DOCUMENT_REPOSITORY = 'RepositoryDocument';
    const ACTION_EXPORT_REPOSITORY = 'RepositoryExporter';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'data_action';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $user
     * @param string $application
     */
    public function __construct(\Symfony\Component\HttpFoundation\Request $request, $user = null, $application = null)
    {
        // Make sure we don't get any timeouts
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        parent :: __construct($request, $user, $application);
    }
}
