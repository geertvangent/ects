<?php
namespace Ehb\Core\Metadata\Relation\Storage\DataClass;

use Ehb\Core\Metadata\Relation\Storage\DataManager;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 * This class describes a metadata schema
 *
 * @package Ehb\Core\Metadata\Schema\Storage\DataClass
 * @author Jens Vanderheyden
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class RelationType extends DataClass
{

    /**
     *
     * @var VocabularyTranslation[]
     */
    private $translations;

    /**
     * **************************************************************************************************************
     * Properties *
     * **************************************************************************************************************
     */
    const PROPERTY_NAME = 'name';
    const PROPERTY_DISPLAY_NAME = 'display_name';

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
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_DISPLAY_NAME;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters *
     * **************************************************************************************************************
     */

    /**
     * Returns the name
     *
     * @return string
     */
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * Returns the display_name
     *
     * @return string
     */
    public function get_display_name()
    {
        return $this->get_default_property(self :: PROPERTY_DISPLAY_NAME);
    }

    /**
     * Sets the display_name
     *
     * @param string $display_name
     */
    public function set_display_name($display_name)
    {
        $this->set_default_property(self :: PROPERTY_DISPLAY_NAME, $display_name);
    }

    public function getTranslations()
    {
        if (! isset($this->translations))
        {
            $translations = DataManager :: retrieves(
                RelationTypeTranslation :: class_name(),
                new DataClassRetrievesParameters(
                    new ComparisonCondition(
                        new PropertyConditionVariable(
                            RelationTypeTranslation :: class_name(),
                            RelationTypeTranslation :: PROPERTY_RELATION_TYPE_ID),
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