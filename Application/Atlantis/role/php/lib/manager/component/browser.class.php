<?php
namespace application\atlantis\role;

use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport 
{

    /* (non-PHPdoc)
     * @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        // TODO Auto-generated method stub
        
    }

	function run()
    {
        $this->display_header();
        $table = new RoleTable($this);
        echo ($table->as_html());
        $this->display_footer();
    }

}
?>