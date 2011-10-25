<?php
namespace application\discovery\module\training\implementation\bamaflex;

use common\libraries\Theme;

use application\discovery\LegendTable;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\training\Module
{

    function get_trainings_table($year = 0)
    {
        $trainings = $this->get_trainings_data($year);
        
        $data = array();
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $training->get_year();
            }
            $row[] = $training->get_name();
            $row[] = $training->get_domain();
            $row[] = $training->get_credits();
            
            $bama_type_image = '<img src="' . Theme :: get_image_path() . 'bama_type/' . $training->get_bama_type() . '.png" alt="' . Translation :: get($training->get_bama_type_string()) . '" title="' . Translation :: get($training->get_bama_type_string()) . '" />';
            $row[] = $bama_type_image;
            LegendTable:: get_instance()->add_symbol($bama_type_image, Translation :: get($training->get_bama_type_string()), Translation :: get('BamaType'));
                        
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        if (! $year)
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Domain'), false);
            $table->set_header(3, Translation :: get('Credits'), false);
            $table->set_header(4, Translation :: get(''), false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
            $table->set_header(1, Translation :: get('Domain'), false);
            $table->set_header(2, Translation :: get('Credits'), false);
            $table->set_header(3, Translation :: get(''), false);
        }
        return $table;
    }
}
?>