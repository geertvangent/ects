<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment;

use Chamilo\Application\Weblcms\Renderer\PublicationList\ContentObjectPublicationListRenderer;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Manager extends \Chamilo\Application\Weblcms\Tool\Manager
{
    // Actions
    const ACTION_ENTRY = 'entry';

    // Parameters
    const PARAM_TARGET_ID = 'target_id';
    const PARAM_ENTITY_TYPE = 'entity_type';

    // Properties
    const PROPERTY_NUMBER_OF_ENTRIES = 'NumberOfEntries';

    /**
     *
     * @see \Chamilo\Application\Weblcms\Tool\Manager::get_available_browser_types()
     */
    public function get_available_browser_types()
    {
        $browserTypes = array();

        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_TABLE;
        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_LIST;
        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_CALENDAR;

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
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => self :: ACTION_ENTRY,
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: PARAM_ACTION => \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: ACTION_ENTITIES,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication[ContentObjectPublication :: PROPERTY_ID])),
                ToolbarItem :: DISPLAY_ICON));

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('SubmissionSubmit'),
                Theme :: getInstance()->getCommonImagePath('Action/Add'),
                $this->get_url(
                    array(
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => self :: ACTION_ENTRY,
                        \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: PARAM_ACTION => \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: ACTION_CREATE,
                        \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication[ContentObjectPublication :: PROPERTY_ID],
                        self :: PARAM_TARGET_ID => $this->get_user_id(),
                        self :: PARAM_ENTITY_TYPE => Entry :: ENTITY_TYPE_USER)),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar;
    }
}
