<?php
namespace Chamilo\Application\EhbSync\Data\Component;

use Chamilo\Libraries\Architecture\DelegateComponent;

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
        catch (\Chamilo\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
