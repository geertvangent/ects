<?php
namespace Application\EhbSync\data\component;

use libraries\architecture\DelegateComponent;

class PersonalCalendarComponent extends Manager implements DelegateComponent
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

            $visit_processor = new PersonalCalendarVisitProcessor();
            $visit_processor->log('PERSONAL CALENDAR VISIT SYNC STARTED');
            $visit_processor->run();
            $visit_processor->log('PERSONAL CALENDAR VISIT SYNC ENDED');

            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
