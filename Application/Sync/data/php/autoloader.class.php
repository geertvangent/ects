<?php
namespace application\ehb_sync\data;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'PortfolioIntroductionComponent' => '/lib/manager/component/na_portfolio_introduction.class.php',
         'PortfolioLocationComponent' => '/lib/manager/component/na_portfolio_location.class.php',
         'PersonalCalendarComponent' => '/lib/manager/component/personal_calendar.class.php',
         'PortfolioComponent' => '/lib/manager/component/portfolio.class.php',
         'RepositoryDocumentComponent' => '/lib/manager/component/repository_document.class.php',
         'RepositoryExporterComponent' => '/lib/manager/component/repository_exporter.class.php',
         'WeblcmsComponent' => '/lib/manager/component/weblcms.class.php',
         'WeblcmsDocumentComponent' => '/lib/manager/component/weblcms_document.class.php',
         'WeblcmsDocumentZipComponent' => '/lib/manager/component/weblcms_document_zip.class.php',
         'PersonalCalendarVisitProcessor' => '/lib/processor/personal_calendar_visit.class.php',
         'PortfolioIntroductionProcessor' => '/lib/processor/portfolio_introduction.class.php',
         'PortfolioLocationProcessor' => '/lib/processor/portfolio_location.class.php',
         'PortfolioVisitProcessor' => '/lib/processor/portfolio_visit.class.php',
         'RepositoryDocumentDownloadProcessor' => '/lib/processor/repository_document_download.class.php',
         'RepositoryExporterProcessor' => '/lib/processor/repository_exporter.class.php',
         'WeblcmsDocumentDownloadProcessor' => '/lib/processor/weblcms_document_download.class.php',
         'WeblcmsDocumentZipProcessor' => '/lib/processor/weblcms_document_zip.class.php',
         'WeblcmsVisitProcessor' => '/lib/processor/weblcms_visit.class.php',
         'PersonalCalendarVisit' => '/lib/storage/data_class/personal_calendar_visit.class.php',
         'PortfolioVisit' => '/lib/storage/data_class/portfolio_visit.class.php',
         'RepositoryDocumentDownload' => '/lib/storage/data_class/repository_document_download.class.php',
         'RepositoryExporter' => '/lib/storage/data_class/repository_exporter.class.php',
         'WeblcmsDocumentDownload' => '/lib/storage/data_class/weblcms_document_download.class.php',
         'WeblcmsDocumentZip' => '/lib/storage/data_class/weblcms_document_zip.class.php',
         'WeblcmsVisit' => '/lib/storage/data_class/weblcms_visit.class.php',
         'DataManager' => '/lib/storage/data_manager/data_manager.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php'
    );

    /**
     * Try to load the class
     *
     * @param string $classname
     * @return boolean
     */
    public static function load($classname)
    {
        if (isset(self :: $map[$classname]))
        {
            require_once __DIR__ . self :: $map[$classname];
            return true;
        }

        return false;
    }

    /**
     * Synchronize the autoloader
     *
     * @param boolean $update
     * @return string[]
     */
    public static function synch($update)
    {
        return \libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}