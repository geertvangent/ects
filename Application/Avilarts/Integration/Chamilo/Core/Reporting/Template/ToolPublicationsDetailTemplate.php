<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Publication\ToolPublicationsBlock;
use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: tool_publications_detail_reporting_template.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.reporting.templates
 */
/**
 *
 * @author Michael Kyndt
 */
class ToolPublicationsDetailTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);
        
        $this->add_reporting_block(new ToolPublicationsBlock($this));
        
        $this->add_breadcrumbs();
    }

    /**
     * Adds the breadcrumbs to the breadcrumbtrail
     */
    protected function add_breadcrumbs()
    {
        $trail = BreadcrumbTrail :: get_instance();
        
        $trail->add(
            new Breadcrumb(
                $this->get_url(
                    array(\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_BLOCK_ID => 4), 
                    array(\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID)), 
                Translation :: get('LastAccessToToolsBlock')));
        
        $trail->add(
            new Breadcrumb(
                $this->get_url(), 
                Translation :: get(
                    'TypeName', 
                    null, 
                    \Ehb\Application\Avilarts\Tool\Manager :: get_tool_type_namespace($this->tool))));
    }
}
