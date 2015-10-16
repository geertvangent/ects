<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\GalleryBrowser;

use Ehb\Application\Discovery\Module\Photo\DataManager;
use Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable\DefaultGalleryTableCellRenderer;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Architecture\Application\Application;

class GalleryBrowserTableCellRenderer extends DefaultGalleryTableCellRenderer
{

    public function get_cell_content(\Chamilo\Core\User\Storage\DataClass\User $user)
    {
        $photo = DataManager :: get_instance($this->get_component()->get_module_instance())->retrieve_photo(
            $user->get_official_code());

        $html[] = '<h4>' . $user->get_fullname() . '</h4>';

        $profile_link = $this->get_component()->get_module_link(
            'Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex',
            $user->get_id());

        $photoUrl = new Redirect(
            array(
                Application :: PARAM_CONTEXT => \Ehb\Application\Discovery\Manager :: package(),
                \Ehb\Application\Discovery\Manager :: PARAM_ACTION => \Ehb\Application\Discovery\Manager :: ACTION_PHOTO,
                \Ehb\Application\Discovery\Component\PhotoComponent :: PARAM_PHOTO => $user->get_official_code()));

        $html[] = '<a href="' . $profile_link->get_href() . '">';
        $html[] = '<img src="' . $photoUrl->getUrl() . '" style="width: 150px; border: 1px solid grey;"/>';
        $html[] = '</a>';

        return implode(PHP_EOL, $html);
    }
}
