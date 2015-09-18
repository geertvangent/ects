<?php
namespace Ehb\Application\Discovery\Instance\Component;

use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Form\InstanceForm;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Module;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

class CreatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $type = Request :: get(self :: PARAM_TYPE);
        if ($type)
        {
            $instance = new Instance();
            $instance->set_type($type);

            $form = new InstanceForm(
                InstanceForm :: TYPE_CREATE,
                $instance,
                $this->get_url(array(self :: PARAM_TYPE => $type)));

            if ($form->validate())
            {
                $success = $form->create_instance();

                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectAdded' : 'ObjectNotAdded',
                        array('OBJECT' => Translation :: get('Instance')),
                        Utilities :: COMMON_LIBRARIES),
                    ($success ? false : true),
                    array(self :: PARAM_ACTION => self :: ACTION_BROWSE_INSTANCES));
            }
            else
            {
                $html = array();

                $html[] = $this->render_header();
                $html[] = $form->toHtml();
                $html[] = $this->render_footer();

                return implode(PHP_EOL, $html);
            }
        }
        else
        {
            $available_types = Module :: get_available_types();
            $table_data = array();

            foreach ($available_types as $available_type)
            {
                $name = htmlentities(Translation :: get('TypeName', null, $available_type));
                $row = array();
                $row[] = '<img src="' . Theme :: getInstance()->getImagesPath($available_type) . 'Logo/22.png" alt="' .
                     $name . '" title="' . $name . '"/>';
                $row[] = $name;
                $row[] = htmlentities(Translation :: get('TypeDescription', null, $available_type));
                $row[] = Theme :: getInstance()->getCommonImage(
                    'Action/Add',
                    'png',
                    Translation :: get('AddInstance'),
                    $this->get_url(array(self :: PARAM_TYPE => $available_type)),
                    ToolbarItem :: DISPLAY_ICON);

                $table_data[] = $row;
            }

            $table = new SortableTableFromArray($table_data);
            $parameters = $this->get_parameters();
            $table->set_additional_parameters($parameters);
            $table->set_header(0, '');
            $table->set_header(1, 'Type');
            $table->set_header(2, 'Description');
            $table->set_header(3, '');

            $html = array();

            $html[] = $this->render_header();
            $html[] = $table->as_html();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }
}
