<?php
namespace Ehb\Application\Sync\Data\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Data\Manager;
use Ehb\Application\Sync\Data\Processor\WeblcmsDocumentZipProcessor;

class WeblcmsDocumentZipComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        header('Content-Type: text/html; charset=utf-8');
        
        try
        {
            flush();
            echo '<pre>';
            
            $visit_processor = new WeblcmsDocumentZipProcessor();
            $visit_processor->log('WEBLCMS DOCUMENT ZIP SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('WEBLCMS DOCUMENT ZIP SYNC ENDED');
            
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
