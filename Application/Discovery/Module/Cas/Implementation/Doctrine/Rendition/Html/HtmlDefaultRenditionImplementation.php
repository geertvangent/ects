<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\Rendition\Html;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\Cas\DataManager;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\GraphRenderer;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine\Rights;
use Ehb\Application\Discovery\Module\Cas\Parameters;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, Translation::get(TypeName)));
        
        if ($this->get_module_parameters()->get_mode() == Parameters::MODE_USER && ! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $html = array();
        if (count($this->get_cas_statistics()) > 0)
        {
            $actions = DataManager::getInstance($this->get_module_instance())->retrieve_actions(
                $this->get_module_parameters());
            
            $tabs = new DynamicTabsRenderer('statistics_list');
            
            foreach ($actions as $action)
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $action->get_id(), 
                        Translation::get($action->get_name()), 
                        Theme::getInstance()->getImagesPath() . 'Action/' . $action->get_id() . '.png', 
                        $this->get_statistics_table($action)));
            }
            
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display::normal_message(Translation::get('NoData'), true);
        }
        return implode(PHP_EOL, $html);
    }

    public function get_statistics_table($action)
    {
        $action_statistics = $this->get_action_statistics($action);
        
        if (count($action_statistics) == 1 && count($action_statistics[0]) > 0)
        {
            $path = Path::getInstance()->getStoragePath() . Path::getInstance()->namespaceToFullPath(__NAMESPACE__) .
                 '/data/' . md5(serialize(array($this->get_module_parameters(), 0, $action->get_id())));
            
            if (! file_exists($path))
            {
                $data = array();
                
                foreach ($action_statistics[0] as $key => $action_statistic)
                {
                    $row = array();
                    $row[] = $action_statistic->get_date();
                    $row[] = $action_statistic->get_count();
                    $data[] = $row;
                }
                Filesystem::write_to_file($path, serialize($data));
            }
            else
            {
                $data = unserialize(file_get_contents($path));
            }
            
            $table = new SortableTable($data);
            $table->setColumnHeader(0, Translation::get('Date'), false);
            $table->setColumnHeader(1, Translation::get('CountShort'), false);
            
            return $table->toHTML();
        }
        else
        {
            $tabs = new DynamicTabsRenderer('statistics_list_' . $action->get_id());
            foreach ($action_statistics as $application_id => $application_statistics)
            {
                $path = Path::getInstance()->getStoragePath() .
                     ClassnameUtilities::getInstance()->namespaceToPath(__NAMESPACE__) . '/data/' .
                     md5(serialize(array($this->get_module_parameters(), $application_id, $action->get_id())));
                
                if (! file_exists($path))
                {
                    $data = array();
                    
                    foreach ($application_statistics as $key => $application_statistic)
                    {
                        $row = array();
                        $row[] = $application_statistic->get_date();
                        $row[] = $application_statistic->get_count();
                        $data[$application_statistic->get_date()] = $row;
                    }
                    Filesystem::write_to_file($path, serialize($data));
                }
                else
                {
                    $data = unserialize(file_get_contents($path));
                }
                
                $applications = $this->get_applications();
                
                $sub_tabs = new DynamicTabsRenderer('statistics_list_' . $action->get_id() . '_' . $application_id);
                
                $html = array();
                $graph = new GraphRenderer(
                    $this, 
                    $this->get_module_parameters()->get_user_id(), 
                    $applications[$application_id], 
                    $action, 
                    $data);
                $sub_tabs->add_tab(
                    new DynamicContentTab(
                        1, 
                        Translation::get('Chart'), 
                        Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'SubTabs/1.png', 
                        $graph->chart()));
                
                $table = new SortableTable($data);
                $table->setColumnHeader(0, Translation::get('Date'), false);
                $table->setColumnHeader(1, Translation::get('CountShort'), false);
                $sub_tabs->add_tab(
                    new DynamicContentTab(
                        3, 
                        Translation::get('DatesTable'), 
                        Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'SubTabs/3.png', 
                        $table->toHTML()));
                
                $tabs->add_tab(
                    new DynamicContentTab(
                        $application_id, 
                        $applications[$application_id]->get_title(), 
                        Theme::getInstance()->getImagesPath() . 'Application/' . $application_id . '.png', 
                        $sub_tabs->render()));
            }
            return $tabs->render();
        }
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
