<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\PlatformGroup;

use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTableColumnModel;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\PlatformGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PlatformGroupTableColumnModel extends GroupTableColumnModel
{
    const PROPERTY_GROUP_MEMBERS = 'group_members';

    public function initialize_columns()
    {
        parent::initialize_columns();
        
        $this->add_column(new StaticTableColumn(self::PROPERTY_GROUP_MEMBERS), 1);
    }
}