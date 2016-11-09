<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass;

use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Entry extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Storage\DataClass\Entry
{
    // Properties
    const PROPERTY_PUBLICATION_ID = 'publication_id';
    
    // Entity types
    const ENTITY_TYPE_USER = 0;
    const ENTITY_TYPE_COURSE_GROUP = 1;
    const ENTITY_TYPE_PLATFORM_GROUP = 2;

    /**
     *
     * @param string[] $extendedPropertyNames
     * @return string[]
     */
    public static function get_default_property_names($extendedPropertyNames = array())
    {
        return parent::get_default_property_names(array(self::PROPERTY_PUBLICATION_ID));
    }

    /**
     *
     * @return integer
     */
    public function getPublicationId()
    {
        return $this->get_default_property(self::PROPERTY_PUBLICATION_ID);
    }

    /**
     *
     * @param integer $publicationId
     */
    public function setPublicationId($publicationId)
    {
        $this->set_default_property(self::PROPERTY_PUBLICATION_ID, $publicationId);
    }

    /**
     * Returns the dependencies for this dataclass
     * 
     * @return \libraries\storage\Condition[string]
     */
    protected function get_dependencies()
    {
        return array(
            Feedback::class_name() => new EqualityCondition(
                new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID), 
                new StaticConditionVariable($this->getId())), 
            Note::class_name() => new EqualityCondition(
                new PropertyConditionVariable(Note::class_name(), Note::PROPERTY_ENTRY_ID), 
                new StaticConditionVariable($this->getId())), 
            Score::class_name() => new EqualityCondition(
                new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_ENTRY_ID), 
                new StaticConditionVariable($this->getId())));
    }
}
