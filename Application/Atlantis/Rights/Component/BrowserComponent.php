<?php
namespace Ehb\Application\Atlantis\Rights\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Rights\Manager;
use Ehb\Application\Atlantis\Rights\Table\Entity\EntityTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class BrowserComponent extends Manager implements TableSupport
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(ClassnameUtilities :: getInstance()->getClassnameFromNamespace(self :: class_name()))));

        $table = new EntityTable($this);

        $html = array();

        $html[] = $this->render_header();
        $html[] = $this->get_tabs(self :: ACTION_BROWSE, $table->as_html())->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    /*
     * (non-PHPdoc) @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        // TODO Auto-generated method stub
    }
    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
