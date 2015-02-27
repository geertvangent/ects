<?php
namespace Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex;

use Ehb\Application\Discovery\DiscoveryItem;

class Major extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_NAME = 'name';

    private $choices;

    private $choice_options;

    /**
     *
     * @return int
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     *
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_NAME;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function get_choices()
    {
        return $this->choices;
    }

    public function set_choices($choices)
    {
        $this->choices = $choices;
    }

    public function has_choices()
    {
        return count($this->choices) > 0;
    }

    public function add_choice($choice)
    {
        $this->choices[] = $choice;
    }

    public function get_choice_options()
    {
        return $this->choice_options;
    }

    public function set_choice_options($choice_options)
    {
        $this->choice_options = $choice_options;
    }

    public function has_choice_options()
    {
        return count($this->choice_options) > 0;
    }

    public function add_choice_option($choice_option)
    {
        $this->choice_options[] = $choice_option;
    }
}
