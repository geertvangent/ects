<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Table\Password;

use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Platform\Translation;

class PasswordTableCellRenderer extends DataClassTableCellRenderer
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case Translation::get('User') :
                return $object->get_user()->get_fullname();
        }
        
        return parent::render_cell($column, $object);
    }
}
