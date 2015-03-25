<?php
namespace Ehb\Core\Metadata\Vocabulary\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Core\Metadata\Schema\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class VocabularyTranslation extends DataClass
{
    /**
     * **************************************************************************************************************
     * Properties *
     * **************************************************************************************************************
     */
    const PROPERTY_VOCABULARY_ID = 'vocabulary_id';
    const PROPERTY_ISOCODE = 'isocode';
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
        $extended_property_names[] = self :: PROPERTY_VOCABULARY_ID;
        $extended_property_names[] = self :: PROPERTY_ISOCODE;
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
    public function get_vocabulary_id()
    {
        return $this->get_default_property(self :: PROPERTY_VOCABULARY_ID);
    }

    /**
     *
     * @param integer
     */
    public function set_vocabulary_id($vocabulary_id)
    {
        $this->set_default_property(self :: PROPERTY_VOCABULARY_ID, $vocabulary_id);
    }

    /**
     * Get the ISO 639-1 code of the language
     *
     * @return string
     */
    public function get_isocode()
    {
        return $this->get_default_property(self :: PROPERTY_ISOCODE);
    }

    /**
     * Set the ISO 639-1 code of the language
     *
     * @param string $isocode
     */
    public function set_isocode($isocode)
    {
        $this->set_default_property(self :: PROPERTY_ISOCODE, $isocode);
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
}