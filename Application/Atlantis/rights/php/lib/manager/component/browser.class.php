<?php
namespace application\atlantis\rights;

use libraries\architecture\NotAllowedException;
use libraries\format\TableSupport;
use application\atlantis\SessionBreadcrumbs;
use libraries\format\Breadcrumb;
use libraries\platform\Translation;
use libraries\utilities\Utilities;

class BrowserComponent extends Manager implements TableSupport
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        $table = new EntityTable($this);

        $this->display_header();
        echo $this->get_tabs(self :: ACTION_BROWSE, $table->as_html())->render();
        $this->display_footer();
    }

    /*
     * (non-PHPdoc) @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        // TODO Auto-generated method stub
    }
	/* (non-PHPdoc)
     * @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
