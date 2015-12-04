<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass;

use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Storage\DataClass\DataClass;
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
class Entry extends DataClass
{
    // Properties
    const PROPERTY_PUBLICATION_ID = 'publication_id';
    const PROPERTY_CONTENT_OBJECT_ID = 'content_object_id';
    const PROPERTY_SUBMITTED = 'submitted';
    const PROPERTY_ENTITY_ID = 'entity_id';
    const PROPERTY_ENTITY_TYPE = 'entity_type';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_IP_ADDRESS = 'ip_address';

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
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_PUBLICATION_ID,
                self :: PROPERTY_CONTENT_OBJECT_ID,
                self :: PROPERTY_SUBMITTED,
                self :: PROPERTY_ENTITY_TYPE,
                self :: PROPERTY_ENTITY_ID,
                self :: PROPERTY_USER_ID,
                self :: PROPERTY_IP_ADDRESS));
    }

    /**
     *
     * @return integer
     */
    public function getPublicationId()
    {
        return $this->get_default_property(self :: PROPERTY_PUBLICATION_ID);
    }

    /**
     *
     * @param integer $publicationId
     */
    public function setPublicationId($publicationId)
    {
        $this->set_default_property(self :: PROPERTY_PUBLICATION_ID, $publicationId);
    }

    /**
     *
     * @return integer
     */
    public function getContentObjectId()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENT_OBJECT_ID);
    }

    /**
     *
     * @param integer $contentObjectId
     */
    public function setContentObjectId($contentObjectId)
    {
        $this->set_default_property(self :: PROPERTY_CONTENT_OBJECT_ID, $contentObjectId);
    }

    /**
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_ID);
    }

    /**
     *
     * @param integer $entityId
     */
    public function setEntityId($entityId)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_ID, $entityId);
    }

    /**
     *
     * @return integer
     */
    public function getSubmitted()
    {
        return $this->get_default_property(self :: PROPERTY_SUBMITTED);
    }

    /**
     *
     * @param integer $submitted
     */
    public function setSubmitted($submitted)
    {
        $this->set_default_property(self :: PROPERTY_SUBMITTED, $submitted);
    }

    /**
     *
     * @return integer
     */
    public function getEntityType()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_TYPE);
    }

    /**
     *
     * @param integer $entityType
     */
    public function setEntityType($entityType)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_TYPE, $entityType);
    }

    /**
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    /**
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $userId);
    }

    /**
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->get_default_property(self :: PROPERTY_IP_ADDRESS);
    }

    /**
     *
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->set_default_property(self :: PROPERTY_IP_ADDRESS, $ipAddress);
    }

    /**
     *
     * @return \Chamilo\Core\Repository\Storage\DataClass\ContentObject
     */
    public function getContentObject()
    {
        try
        {
            return \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
                ContentObject :: class_name(),
                $this->getContentObjectId());
        }
        catch (\Exception $ex)
        {
            return null;
        }
    }

    /**
     * Returns the dependencies for this dataclass
     *
     * @return \libraries\storage\Condition[string]
     */
    protected function get_dependencies()
    {
        return array(
            Feedback :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(Feedback :: class_name(), Feedback :: PROPERTY_ENTRY_ID),
                new StaticConditionVariable($this->getId())),
            Note :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(Note :: class_name(), Note :: PROPERTY_ENTRY_ID),
                new StaticConditionVariable($this->getId())),
            Score :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(Score :: class_name(), Score :: PROPERTY_ENTRY_ID),
                new StaticConditionVariable($this->getId())));
    }
}
