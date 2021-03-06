<?php
namespace Ehb\Application\Ects;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Structure\Page;
use Chamilo\Libraries\Format\Theme;

/**
 *
 * @package Ehb\Application\Ects
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Manager extends Application
{
    // Actions
    const ACTION_TRAININGS = 'Trainings';
    
    // Default action
    const DEFAULT_ACTION = self::ACTION_TRAININGS;

    public function render_header($pageTitle = '')
    {
        $pluginPath = Path::getInstance()->getJavascriptPath('Chamilo\Libraries', true) . 'Plugin/';
        $cssPath = Theme::getInstance()->getCssPath('Chamilo\Configuration', true, false) . 'Aqua/';
        
        $html = array();
        
        // Header
        $html[] = '<!DOCTYPE html>';
        $html[] = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
        $html[] = '<head>';
        $html[] = '<meta charset="utf-8">';
        $html[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $html[] = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $html[] = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">';
        $html[] = '<link rel="stylesheet" href="' . $cssPath . 'FontAwesome/css/font-awesome.css">';
        $html[] = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>';
        $html[] = '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>';
        $html[] = '</head>';
        
        $html[] = '<body dir="ltr">';
        
        $html[] = '<style>';
        $html[] = 'body{margin-top: 15px;}';
        $html[] = '</style>';
        
        $html[] = '<div class="container-fluid">';
        
        return implode(PHP_EOL, $html);
    }

    public function render_footer()
    {
        $html = array();
        
        $html[] = '</div>';
        $html[] = '</body>';
        
        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        Page::getInstance()->setViewMode(Page::VIEW_MODE_HEADERLESS);
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $this->renderBody();
        $html[] = $this->render_footer();
        
        echo implode(PHP_EOL, $html);
    }
}
