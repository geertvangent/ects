<?php
namespace application\atlantis\rights;

use common\libraries\NotAllowedException;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        
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
}
