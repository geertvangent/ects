<?php
namespace Chamilo\Application\EhbSync\Data\Component;

use Chamilo\Libraries\Architecture\DelegateComponent;

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
        catch (\Chamilo\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
