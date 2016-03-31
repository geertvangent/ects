<?php
namespace Ehb\Application\Avilarts\Ajax\Component;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Storage\DataClass\CourseModule;
use Ehb\Application\Avilarts\Storage\DataManager;

/**
 *
 * @package Ehb\Application\Avilarts\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BlockSortComponent extends \Ehb\Application\Avilarts\Ajax\Manager
{

    function unserialize_jquery($jquery)
    {
        $block_data = explode('&', $jquery);
        $blocks = array();

        foreach ($block_data as $block)
        {
            $block_split = explode('=', $block);
            $blocks[] = $block_split[1];
        }

        return $blocks;
    }

    public function run()
    {
        $section_id = explode($_POST['id']);
        $blocks = $this->unserialize_jquery($_POST['order']);

        $i = 1;
        foreach ($blocks as $block_id)
        {
            $block = DataManager :: retrieve_by_id(CourseModule :: class_name(), $block_id);
            $block->set_sort($i);
            $block->update();
            $i ++;
        }

        $json_result['success'] = '1';
        $json_result['message'] = Translation :: get(
            'ObjectAdded',
            array('OBJECT' => Translation :: get('Block')),
            Utilities :: COMMON_LIBRARIES);

        // Return a JSON object
        echo json_encode($json_result);
    }
}