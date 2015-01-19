<?php
namespace Ehb\Application\Sync\Data\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Data\Processor\PortfolioIntroductionProcessor;
use Ehb\Application\Sync\Data\Manager;

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
