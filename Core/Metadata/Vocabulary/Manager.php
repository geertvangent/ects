<?php
namespace Ehb\Core\Metadata\Vocabulary;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Ehb\Core\Metadata\Vocabulary
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class Manager extends Application
{
    // Parameters
    const PARAM_ACTION = 'vocabulary_action';
    const PARAM_VOCABULARY_ID = 'vocabulary_id';
    const PARAM_ENTITY_TYPE = 'entity_type';
    const PARAM_ENTITY_ID = 'entity_id';

    // Actions
    const ACTION_BROWSE = 'Browser';
    const ACTION_DELETE = 'Deleter';
    const ACTION_UPDATE = 'Updater';
    const ACTION_CREATE = 'Creator';

    // Default action
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

    /**
     *
     * @return integer
     */
    public function getElementId()
    {
        return $this->getRequest()->query->get(\Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID);
    }
}
