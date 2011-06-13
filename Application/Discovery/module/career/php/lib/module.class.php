<?php
namespace application\discovery\module\career;

use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\DiscoveryModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    /**
     * @var multitype:\application\discovery\module\career\Career
     */
    private $careers;

    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->careers = DataManager :: get_instance($module_instance)->retrieve_careers($application->get_user_id());

    }

    /**
     * @return multitype:\application\discovery\module\career\Career
     */
    function get_careers()
    {
        return $this->careers;
    }

    //    /**
    //     * @return multitype:string
    //     */
    //    function get_general_properties()
    //    {
    //        $properties = array();
    //        $properties[Translation :: get('FirstName')] = $this->profile->get_name()->get_first_names();
    //        $properties[Translation :: get('LastName')] = $this->profile->get_name()->get_last_name();
    //        $properties[Translation :: get('Language')] = $this->profile->get_language();
    //
    //        foreach ($this->profile->get_identification_code() as $identification_code)
    //        {
    //            $properties[Translation :: get($identification_code->get_type_string())] = $identification_code->get_code();
    //        }
    //
    //        if (count($this->profile->get_communication()) == 1)
    //        {
    //            $communication = $this->profile->get_communication();
    //            $communication = $communication[0];
    //            $properties[Translation :: get('CommunicationNumber')] = $communication->get_number() . ' (' . $communication->get_device_string() . ')';
    //        }
    //
    //        if (count($this->profile->get_email()) == 1)
    //        {
    //            $email = $this->profile->get_email();
    //            $email = $email[0];
    //            $properties[Translation :: get('Email')] = $email->get_address() . ' (' . $email->get_type_string() . ')';
    //        }
    //
    //        return $properties;
    //    }


    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();

        $data = array();

        foreach ($this->careers as $career)
        {
            $row = array();
            $row[] = $career->get_year();
            $row[] = $career->get_faculty();
            $row[] = $career->get_training();
            $row[] = $career->get_unified_option();
            $row[] = $career->get_unified_trajectory();
            $row[] = $career->get_contract_type_string();
            $data[] = $row;
        }

        $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/career.png);">';
        $html[] = '<div class="title">';
        $html[] = Translation :: get('Careers');
        $html[] = '</div>';

        $html[] = '<div class="description">';

        $table = new SortableTableFromArray($data);
        $table->set_header(0, Translation :: get('Year'));
        $table->set_header(1, Translation :: get('Faculty'));
        $table->set_header(2, Translation :: get('Training'));
        $table->set_header(3, Translation :: get('Option'));
        $table->set_header(4, Translation :: get('Trajectory'));
        $table->set_header(5, Translation :: get('Contract'));
        $html[] = $table->toHTML();

        $html[] = '</div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }
}
?>