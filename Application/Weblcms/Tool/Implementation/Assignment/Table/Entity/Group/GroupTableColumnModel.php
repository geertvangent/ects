<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group;

use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class GroupTableColumnModel extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableColumnModel
{
    const PROPERTY_GROUP_MEMBERS = 'group_members';

    public function initialize_columns()
    {
        parent :: initialize_columns();

        $this->add_column(new StaticTableColumn(self :: PROPERTY_GROUP_MEMBERS), 1);
    }
}