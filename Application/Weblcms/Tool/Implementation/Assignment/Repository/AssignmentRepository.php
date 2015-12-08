<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository;

use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class AssignmentRepository
{

    /**
     *
     * @param integer $publicationIdentifier
     * @return integer
     */
    public function countEntriesForPublicationIdentifier($publicationIdentifier)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($publicationIdentifier));

        return DataManager :: count(Entry :: class_name(), new DataClassCountParameters($condition));
    }
}