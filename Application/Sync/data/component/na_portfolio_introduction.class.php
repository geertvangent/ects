<?php
namespace application\ehb_sync\data;

use libraries\architecture\DelegateComponent;

class PortfolioIntroductionComponent extends Manager implements DelegateComponent
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

            $visit_processor = new PortfolioIntroductionProcessor();
            $visit_processor->log('PORTFOLIO INTRODUCTION CONVERSION STARTED');
            $visit_processor->run();
            $visit_processor->log('PORTFOLIO INTRODUCTION CONVERSION ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
