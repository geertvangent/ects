<?php
namespace Ehb\Core\Metadata\Service;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Core\Metadata\Storage\DataClass\EntityTranslation;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;

/**
 *
 * @package Ehb\Core\Metadata\Service
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class EntityTranslationService
{

    /**
     *
     * @var \Chamilo\Libraries\Storage\DataClass\DataClass
     */
    private $entity;

    /**
     *
     * @param \Chamilo\Libraries\Storage\DataClass\DataClass $entity
     */
    public function __construct(DataClass $entity)
    {
        $this->entity = $entity;
    }

    /**
     *
     * @return \Chamilo\Libraries\Storage\DataClass\DataClass
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @param \Chamilo\Libraries\Storage\DataClass\DataClass $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntityTranslationsIndexedByIsocode()
    {
        $conditions = array();
        $translationsIndexedByIsocode = array();

        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(EntityTranslation :: class_name(), EntityTranslation :: PROPERTY_ENTITY_TYPE),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($this->getEntity()->class_name()));
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(EntityTranslation :: class_name(), EntityTranslation :: PROPERTY_ENTITY_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($this->getEntity()->get_id()));

        $translations = \Chamilo\Libraries\Storage\DataManager\DataManager :: retrieves(
            EntityTranslation :: class_name(),
            new DataClassRetrievesParameters(new AndCondition($conditions)));

        while ($translation = $translations->next_result())
        {
            $translationsIndexedByIsocode[$translation->get_isocode()] = $translation;
        }

        return $translationsIndexedByIsocode;
    }
}
