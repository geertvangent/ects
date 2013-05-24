<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\Theme;
use application\discovery\module\photo\DataManager;
use user\User;

class GalleryBrowserTableCellRenderer extends DefaultGalleryTableCellRenderer
{

    private $browser;

    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    public function get_cell_content(User $user)
    {
        $photo = DataManager :: get_instance($this->browser->get_module_instance())->retrieve_photo(
            $user->get_official_code());
        
        $html[] = '<h4>' . $user->get_fullname() . '</h4>';
        $profile_link = $this->browser->get_module_link(
            'application\discovery\module\profile\implementation\bamaflex', 
            $user->get_id());
        $html[] = '<a href="' . $profile_link->get_href() . '">';
        
        // if ($photo->has_data())
        // {
        $html[] = '<img src="' . $photo . '" style="width: 150px; border: 1px solid grey;"/>';
        
        // }
        // else
        // {
        // $html[] = Theme::get_common_image('unknown', 'jpg');
        // }
        $html[] = '</a>';
        return implode("\n", $html);
    }
}
