<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Application\Weblcms\Table\Publication\Table\ObjectPublicationTableCellRenderer;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\StringUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service\AssignmentService;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PublicationTableCellRenderer extends ObjectPublicationTableCellRenderer
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Renders a cell for a given object
     *
     * @param $column \libraries\ObjectTableColumn
     *
     * @param mixed $publication
     *
     * @return String
     */
    public function render_cell($column, $publication)
    {
        $content_object = $this->get_component()->get_content_object_from_publication($publication);

        switch ($column->get_name())
        {
            case ContentObject :: PROPERTY_TITLE :
                return $this->renderTitleLink($publication);
            case Assignment :: PROPERTY_END_TIME :
                $time = $content_object->get_end_time();
                $date_format = Translation :: get('DateTimeFormatLong', null, Utilities :: COMMON_LIBRARIES);
                $time = DatetimeUtilities :: format_locale_date($date_format, $time);
                if ($publication[ContentObjectPublication :: PROPERTY_HIDDEN])
                {
                    return '<span style="color: gray">' . $time . '</span>';
                }
                return $time;
            case PublicationTableColumnModel :: PROPERTY_ENTRY_COUNT :
                $assignmentService = new AssignmentService(new AssignmentRepository());
                return $assignmentService->countEntriesForPublicationIdentifier(
                    $publication[ContentObjectPublication :: PROPERTY_ID]);
            case Assignment :: PROPERTY_ALLOW_GROUP_SUBMISSIONS :

                if ($content_object->get_allow_group_submissions())
                {
                    return '<img src="' . Theme :: getInstance()->getImagePath(Manager :: package(), 'Type/Group') .
                         '" alt="' . Translation :: get('GroupAssignment') . '" title="' .
                         Translation :: get('GroupAssignment') . '"/>';
                }

                return '<img src="' . Theme :: getInstance()->getImagePath(Manager :: package(), 'Type/Individual') .
                     '" alt="' . Translation :: get('IndividualAssignment') . '" title="' .
                     Translation :: get('IndividualAssignment') . '"/>';
        }

        return parent :: render_cell($column, $publication);
    }

    /**
     * Generated the HTML for the title column, including link, depending on the status of the current browsing user.
     *
     * @param $publication type The publication for which the title link is to be generated.
     * @return string The HTML for the link in the title column.
     */
    private function renderTitleLink($publication)
    {
        if ($this->get_component()->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            $url = $this->get_component()->get_url(
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager :: ACTION_DISPLAY_COMPLEX_CONTENT_OBJECT,
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => $publication[ContentObjectPublication :: PROPERTY_ID]));
        }
        else
        {
            // $url = $this->get_component()->get_url(
            // array(
            // \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION => Manager :: ACTION_ENTRY,
            // \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: PARAM_ACTION =>
            // \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Manager :: ACTION_STUDENT,
            // \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID =>
            // $publication[ContentObjectPublication :: PROPERTY_ID]));
        }

        return '<a href="' . $url . '">' .
             StringUtilities :: getInstance()->truncate($publication[ContentObject :: PROPERTY_TITLE], 50) . '</a>';
    }
}