<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\GalleryBrowser;

use Ehb\Application\Discovery\Module\Photo\DataManager;
use Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable\DefaultGalleryTableCellRenderer;

class GalleryBrowserTableCellRenderer extends DefaultGalleryTableCellRenderer
{

    private $browser;

    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    public function get_cell_content(\Chamilo\Core\User\Storage\DataClass\User $user)
    {
        $photo = DataManager :: get_instance($this->browser->get_module_instance())->retrieve_photo(
            $user->get_official_code());
        
        $html[] = '<h4>' . $user->get_fullname() . '</h4>';
        
        $profile_link = $this->browser->get_module_link(
            'application\discovery\module\profile\implementation\bamaflex', 
            $user->get_id());
        
        $html[] = '<a href="' . $profile_link->get_href() . '">';
        $html[] = '<img src="' . $photo . '" style="width: 150px; border: 1px solid grey;"/>';
        $html[] = '</a>';
        
        return implode("\n", $html);
    }
}
