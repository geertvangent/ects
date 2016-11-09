<?php
namespace Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        return \Ehb\Application\Discovery\Module\Training\Rendition\Rendition :: launch($this);
    }

    public function get_trainings_table($year)
    {
        $trainings = $this->get_trainings_data($year);
        BreadcrumbTrail :: getInstance()->add(new Breadcrumb(null, $year));
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');

        $training_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex',
            array('data_source' => $data_source));
        $group_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex',
            array('data_source' => $data_source));
        $photo_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex',
            array('data_source' => $data_source));
        $training_results_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex',
            array('data_source' => $data_source));

        foreach ($trainings as $key => $training)
        {
            $row = array();

            if ($training_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                    $training->get_id(),
                    $training->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $training_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
                }
                else
                {
                    $row[] = $training->get_name();
                }
            }
            else
            {
                $row[] = $training->get_name();
            }

            $row[] = $training->get_domain();
            $row[] = $training->get_credits();

            $bama_type_image = '<img src="' .
                 Theme :: getInstance()->getImagesPath(
                    'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex') . 'BamaType/' .
                 $training->get_bama_type() . '.png" alt="' . Translation :: get($training->get_bama_type_string()) .
                 '" title="' . Translation :: get($training->get_bama_type_string()) . '" />';
            $row[] = $bama_type_image;
            LegendTable :: getInstance()->addSymbol(
                $bama_type_image,
                Translation :: get($training->get_bama_type_string()),
                Translation :: get('BamaType'));

            if ($group_module_instance || $photo_module_instance || $training_results_module_instance)
            {
                $buttons = array();

                if ($group_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Parameters(
                        $training->get_id(),
                        $training->get_source());

                    $is_allowed = \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $group_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($group_module_instance->get_id(), $parameters);
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('Groups'),
                            Theme :: getInstance()->getImagesPath(
                                'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex') . 'Logo/16.png',
                            $url,
                            ToolbarItem :: DISPLAY_ICON);
                    }
                    else
                    {
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('GroupsNotAvailable'),
                            Theme :: getInstance()->getImagesPath(
                                'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex') . 'Logo/16_na.png',
                            null,
                            ToolbarItem :: DISPLAY_ICON);
                    }

                    $buttons[] = $toolbar_item->as_html();
                }

                if ($photo_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                    $parameters->set_training_id($training->get_id());

                    $is_allowed = \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $photo_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex'),
                            $url,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16_na',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex'),
                            null,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex');
                    }
                }

                if ($training_results_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Parameters();
                    $parameters->set_training_id($training->get_id());
                    $parameters->set_source($training->get_source());

                    $is_allowed = \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $training_results_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex'),
                            $url,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16_na',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex'),
                            null,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex');
                    }
                }

                $row[] = implode(PHP_EOL, $buttons);
            }

            $data[] = $row;
        }

        $table = new SortableTable($data);
        $table->setColumnHeader(0, Translation :: get('Name'), false);
        $table->setColumnHeader(1, Translation :: get('Domain'), false);
        $table->setColumnHeader(2, Translation :: get('Credits'), false);
        $table->setColumnHeader(3, '', false);
        $table->setColumnHeader(4, '', false);

        return $table;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
