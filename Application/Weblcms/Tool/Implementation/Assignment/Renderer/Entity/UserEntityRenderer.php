<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Renderer\Entity;

use Chamilo\Core\Repository\ContentObject\Assignment\Display\Renderer\EntityRenderer;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\DataManager\DataManager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Renderer
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserEntityRenderer extends EntityRenderer
{

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Renderer\EntityRenderer::findEntity()
     */
    public function findEntity()
    {
        return DataManager :: retrieve_by_id(User :: class_name(), $this->getEntityId());
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Renderer\EntityRenderer::renderProperties()
     */
    public function renderProperties(\Chamilo\Libraries\Storage\DataClass\DataClass $user)
    {
        $properties = array();
        $properties[Translation :: get('SubmittedBy')] = $this->getEntityName();
        return $properties;
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Renderer\EntityRenderer::getEntityName()
     */
    public function getEntityName()
    {
        $entity = $this->getEntity();
        return $entity->get_fullname();
    }
}