<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication;

use Chamilo\Application\Weblcms\Table\Publication\Table\ObjectPublicationTableColumnModel;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PublicationTableColumnModel extends ObjectPublicationTableColumnModel
{

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

        $this->add_column(new StaticTableColumn(Manager :: PROPERTY_NUMBER_OF_ENTRIES));

        $this->add_column(
            new DataClassPropertyTableColumn(
                Assignment :: class_name(),
                Assignment :: PROPERTY_ALLOW_GROUP_SUBMISSIONS,
                null,
                false),
            1);
    }
}