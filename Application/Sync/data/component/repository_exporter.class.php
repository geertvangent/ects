<?php
namespace Application\EhbSync\data\component;

use libraries\architecture\DelegateComponent;

class RepositoryExporterComponent extends Manager implements DelegateComponent
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

            $visit_processor = new RepositoryExporterProcessor();
            $visit_processor->log('REPOSITORY EXPORTER SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('REPOSITORY EXPORTER SYNC ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
