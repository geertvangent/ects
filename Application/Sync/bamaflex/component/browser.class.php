<?php
namespace Application\EhbSync\bamaflex\component;

use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\architecture\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->display_header();

        $types = array(
            self :: ACTION_ALL_USERS,
            self :: ACTION_GROUPS,
            self :: ACTION_COURSE_CATEGORIES,
            self :: ACTION_COURSES,
            self :: ACTION_ARCHIVE_GROUPS);

        $html = array();
        foreach ($types as $type)
        {

            $html[] = '<a href="' . $this->get_url(array(self :: PARAM_ACTION => $type)) . '">';
            $html[] = '<div class="create_block" style="background-image: url(' . Theme :: get_image_path() .
                 'component/' . $type . '.png);">';
            $html[] = Translation :: get(Utilities :: underscores_to_camelcase($type) . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }
        echo implode("\n", $html);

        $this->display_footer();
    }
}
