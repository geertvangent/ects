<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication;

use Chamilo\Application\Weblcms\Table\Publication\Table\ObjectPublicationTable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Publication
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PublicationTable extends ObjectPublicationTable
{

    /**
     * Returns the implemented form actions
     * 
     * @return TableFormActions
     */
    public function get_implemented_form_actions()
    {
        $actions = $this->get_component()->get_actions();
        $actions->set_namespace(__NAMESPACE__);
        
        return $actions;
    }
}