<?php
namespace Ehb\Core\Metadata\Package;

use Ehb\Core\Metadata\Service\EntityTranslationService;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Core\Metadata\Package
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Installer extends \Chamilo\Configuration\Package\Action\Installer
{

    public function extra()
    {
        $relation = new \Ehb\Core\Metadata\Relation\Storage\DataClass\Relation();
        $relation->set_name('isAvailableFor');
        if ($relation->create())
        {
            $entityTranslations = array();
            $entityTranslations[Translation :: get_language()] = Translation :: get('IsAvailableFor');

            $entityTranslationService = new EntityTranslationService($relation);
            if (! $entityTranslationService->createEntityTranslations($entityTranslations))
            {
                return false;
            }
        }

        return true;
    }
}