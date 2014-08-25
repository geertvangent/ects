<?php
namespace application\ehb_sync\data;

use libraries\DelegateComponent;

class PortfolioLocationComponent extends Manager implements DelegateComponent
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

            $visit_processor = new PortfolioLocationProcessor();
            $visit_processor->log('PORTFOLIO CONVERT SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('PORTFOLIO CONVERT SYNC ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
