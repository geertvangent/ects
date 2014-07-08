<?php
namespace application\ehb_sync\data;

use common\libraries\DelegateComponent;

class WeblcmsComponent extends Manager implements DelegateComponent
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

            $visit_processor = new WeblcmsVisitProcessor();
            $visit_processor->log('WEBLCMS VISIT SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('WEBLCMS VISIT SYNC ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
