<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\User;

use Chamilo\Core\User\Storage\DataClass\User;

/**
 *
 * @package Chamilo\Core\Repository\ContentObject\Assignment\Display\Preview\Table\Entity
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserTableCellRenderer extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableCellRenderer
{

    public function render_cell($column, $entity)
    {
        switch ($column->get_name())
        {
            case UserTableColumnModel :: PROPERTY_NAME :
                return $entity[User :: PROPERTY_LASTNAME] . ', ' . $entity[User :: PROPERTY_FIRSTNAME];
                break;
        }

        return parent :: render_cell($column, $entity);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableCellRenderer::isEntity()
     */
    protected function isEntity($entityId, $userId)
    {
        return $entityId == $userId;
    }
}