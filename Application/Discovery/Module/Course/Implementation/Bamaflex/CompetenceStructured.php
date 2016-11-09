<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

class CompetenceStructured extends Competence
{
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_CODE = 'code';
    const PROPERTY_SUMMARY = 'summary';
    const PROPERTY_LEVEL = 'level';

    public function get_programme_id()
    {
        return $this->get_default_property(self::PROPERTY_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self::PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function get_code()
    {
        return $this->get_default_property(self::PROPERTY_CODE);
    }

    public function set_code($code)
    {
        $this->set_default_property(self::PROPERTY_CODE, $code);
    }

    public function get_summary()
    {
        return $this->get_default_property(self::PROPERTY_SUMMARY);
    }

    public function set_summary($summary)
    {
        $this->set_default_property(self::PROPERTY_SUMMARY, $summary);
    }

    public function get_level()
    {
        return $this->get_default_property(self::PROPERTY_LEVEL);
    }

    public function set_level($level)
    {
        $this->set_default_property(self::PROPERTY_LEVEL, $level);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self::PROPERTY_CODE;
        $extended_property_names[] = self::PROPERTY_SUMMARY;
        $extended_property_names[] = self::PROPERTY_LEVEL;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
