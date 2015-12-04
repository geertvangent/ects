<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage;

use Chamilo\Libraries\Storage\DataClass\Property\DataClassProperties;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\GroupBy;
use Chamilo\Libraries\Storage\Query\Variable\FunctionConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'weblcms_assignment_';

    public static function retrieveEntriesByType($publicationIdentifier, $entityType)
    {
        $properties = new DataClassProperties();

        $properties->add(new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_ID));

        $dateSubmittedVariable = new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_SUBMITTED);

        $properties->add(
            new FunctionConditionVariable(FunctionConditionVariable :: MIN, $dateSubmittedVariable, 'first_date'));

        $properties->add(
            new FunctionConditionVariable(FunctionConditionVariable :: MAX, $dateSubmittedVariable, 'last_date'));

        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: COUNT,
                new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ID),
                'count'));

        $properties->add(new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_TYPE));

        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($publicationIdentifier));

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable($entityType));

        $condition = new AndCondition($conditions);

        $groupBy = new GroupBy();
        $groupBy->add(new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_ID));

        $parameters = new RecordRetrievesParameters($properties, $condition, null, null, array(), null, $groupBy);

        return self :: records(Entry :: class_name(), $parameters);
    }
}
