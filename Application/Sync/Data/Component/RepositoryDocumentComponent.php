<?php
namespace Chamilo\Application\EhbSync\Data\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Application\EhbSync\Data\Processor\RepositoryDocumentDownloadProcessor;
use Chamilo\Application\EhbSync\Data\Manager;

class RepositoryDocumentComponent extends Manager implements DelegateComponent
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

            $visit_processor = new RepositoryDocumentDownloadProcessor();
            $visit_processor->log('REPOSITORY DOCUMENT DOWNLOAD SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('REPOSITORY DOCUMENT DOWNLOAD SYNC ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
