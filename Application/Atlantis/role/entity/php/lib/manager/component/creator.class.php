<?php
namespace application\atlantis\role\entity;

class CreatorComponent extends Manager
{

    function run()
    {
        $form = new EntityForm($this, $this->get_url());
        if ($form->validate())
        {
        
        }
        else
        {
            $this->display_header();
            echo $form->toHtml();
            $this->display_footer();
        }
    }
}
?>