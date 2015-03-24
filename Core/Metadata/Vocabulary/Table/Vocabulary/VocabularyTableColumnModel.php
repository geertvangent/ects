<?php
namespace Ehb\Core\Metadata\Vocabulary\Table\Vocabulary;

use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\StringUtilities;

/**
 * Table column model for the schema
 *
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class VocabularyTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{
    const COLUMN_PREFIX = 'prefix';

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(
                Vocabulary :: class_name(),
                Vocabulary :: PROPERTY_VALUE,
                Translation :: get(
                    (string) StringUtilities :: getInstance()->createString(Vocabulary :: PROPERTY_VALUE)->upperCamelize(),
                    null,
                    'core\metadata')));
    }
}