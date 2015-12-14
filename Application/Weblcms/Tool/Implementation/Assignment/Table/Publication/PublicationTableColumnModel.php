<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication;

use Chamilo\Application\Weblcms\Table\Publication\Table\ObjectPublicationTableColumnModel;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PublicationTableColumnModel extends ObjectPublicationTableColumnModel
{
    const PROPERTY_ENTRY_COUNT = 'entry_count';

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        parent :: initialize_columns();

        $this->add_column(
            new DataClassPropertyTableColumn(Assignment :: class_name(), Assignment :: PROPERTY_END_TIME, null, false));

        $this->add_column(new StaticTableColumn(self :: PROPERTY_ENTRY_COUNT));

        $this->add_column(
            new DataClassPropertyTableColumn(
                Assignment :: class_name(),
                Assignment :: PROPERTY_ALLOW_GROUP_SUBMISSIONS,
                null,
                false),
            1);
    }
}