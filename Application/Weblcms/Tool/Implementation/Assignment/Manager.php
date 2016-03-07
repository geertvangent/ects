<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment;

use Chamilo\Application\Weblcms\Renderer\PublicationList\ContentObjectPublicationListRenderer;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Manager extends \Chamilo\Application\Weblcms\Tool\Manager
{

    /**
     *
     * @see \Chamilo\Application\Weblcms\Tool\Manager::get_available_browser_types()
     */
    public function get_available_browser_types()
    {
        $browserTypes = array();

        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_TABLE;
        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_LIST;

        return $browserTypes;
    }

    /**
     *
     * @return string[]
     */
    public static function get_allowed_types()
    {
        return array(Assignment :: class_name());
    }

    /**
     * Adds extra actions to the toolbar in different components
     *
     * @param \Chamilo\Libraries\Format\Structure\Toolbar $toolbar
     * @param string[] $publication
     * @return \Chamilo\Libraries\Format\Structure\Toolbar
     */
    public function add_content_object_publication_actions($toolbar, $publication)
    {
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('BrowseSubmitters'),
                Theme :: getInstance()->getCommonImagePath('Action/Browser'),
                $this->get_url(
                    array(
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => self :: ACTION_DISPLAY_COMPLEX_CONTENT_OBJECT,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication[ContentObjectPublication :: PROPERTY_ID])),
                ToolbarItem :: DISPLAY_ICON));

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('SubmissionSubmit'),
                Theme :: getInstance()->getCommonImagePath('Action/Add'),
                $this->get_url(
                    array(
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => self :: ACTION_DISPLAY_COMPLEX_CONTENT_OBJECT,
                        \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager :: PARAM_ACTION => \Chamilo\Core\Repository\ContentObject\Assignment\Display\Manager :: ACTION_SUBMIT,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication[ContentObjectPublication :: PROPERTY_ID])),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar;
    }
}
