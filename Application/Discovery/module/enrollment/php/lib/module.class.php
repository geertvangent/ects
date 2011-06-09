<?php
namespace application\discovery\module\enrollment;

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
     * @var multitype:\application\discovery\module\enrollment\Enrollment
     */
    private $enrollments;

    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->enrollments = DataManager :: get_instance($module_instance)->retrieve_enrollments($application->get_user_id());

    }

    /**
     * @return multitype:\application\discovery\module\enrollment\Enrollment
     */
    function get_enrollments()
    {
        return $this->enrollments;
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

        foreach ($this->enrollments as $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();
            $row[] = $enrollment->get_training();
            $data[] = $row;
        }

        $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/enrollment.png);">';
        $html[] = '<div class="title">';
        $html[] = Translation :: get('Enrollments');
        $html[] = '</div>';

        $html[] = '<div class="description">';

        $table = new SortableTableFromArray($data);
        $table->set_header(0, Translation :: get('Year'));
        $table->set_header(1, Translation :: get('Training'));
        $html[] = $table->toHTML();

        $html[] = '</div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }
}
?>