<?php
namespace Ehb\Core\Metadata\Vocabulary\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Core\Metadata\Vocabulary\Storage\DataManager;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 * This class describes a metadata vocabulary
 *
 * @package Ehb\Core\Metadata\Schema\Storage\DataClass
 * @author Jens Vanderheyden
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Vocabulary extends DataClass
{

    /**
     *
     * @var VocabularyTranslation[]
     */
    private $translations;

    /**
     *
     * @var boolean
     */
    private $isDefault;
    /**
     * **************************************************************************************************************
     * Properties *
     * **************************************************************************************************************
     */
    const PROPERTY_ELEMENT_ID = 'element_id';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_DEFAULT_VALUE = 'default_value';
    const PROPERTY_VALUE = 'value';

    /**
     * **************************************************************************************************************
     * Extended functionality *
     * **************************************************************************************************************
     */

    /**
     * Get the default properties
     *
     * @param string[] $extended_property_names
     *
     * @return string[] The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_ELEMENT_ID;
        $extended_property_names[] = self :: PROPERTY_USER_ID;
        $extended_property_names[] = self :: PROPERTY_DEFAULT_VALUE;
        $extended_property_names[] = self :: PROPERTY_VALUE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters *
     * **************************************************************************************************************
     */

    /**
     *
     * @return integer
     */
    public function get_element_id()
    {
        return $this->get_default_property(self :: PROPERTY_ELEMENT_ID);
    }

    /**
     *
     * @param integer
     */
    public function set_element_id($element_id)
    {
        $this->set_default_property(self :: PROPERTY_ELEMENT_ID, $element_id);
    }

    /**
     *
     * @return integer
     */
    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    /**
     *
     * @param integer
     */
    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    public function isForEveryone()
    {
        return $this->get_user_id() == 0;
    }

    public function getUser()
    {
        if ($this->isForEveryone())
        {
            return null;
        }

        return DataManager :: retrieve_by_id(User :: class_name(), $this->get_user_id());
    }

    /**
     *
     * @return integer
     */
    public function get_default_value()
    {
        return $this->get_default_property(self :: PROPERTY_DEFAULT_VALUE);
    }

    /**
     *
     * @param integer
     */
    public function set_default_value($default_value)
    {
        $this->set_default_property(self :: PROPERTY_DEFAULT_VALUE, $default_value);
    }

    public function isDefault()
    {
        return (bool) $this->get_default_value();
    }

    /**
     *
     * @return string
     */
    public function get_value()
    {
        return $this->get_default_property(self :: PROPERTY_VALUE);
    }

    /**
     *
     * @param string
     */
    public function set_value($value)
    {
        $this->set_default_property(self :: PROPERTY_VALUE, $value);
    }

    public function getTranslations()
    {
        if (! isset($this->translations))
        {
            $translations = DataManager :: retrieves(
                VocabularyTranslation :: class_name(),
                new DataClassRetrievesParameters(
                    new ComparisonCondition(
                        new PropertyConditionVariable(
                            VocabularyTranslation :: class_name(),
                            VocabularyTranslation :: PROPERTY_VOCABULARY_ID),
                        ComparisonCondition :: EQUAL,
                        new StaticConditionVariable($this->get_id()))));

            while ($translation = $translations->next_result())
            {
                $this->translations[$translation->get_isocode()] = $translation;
            }
        }

        return $this->translations;
    }
}