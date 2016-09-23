<?php
namespace Ehb\Libraries\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Libraries\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class AdministrationDataClass extends DataClass
{

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataClass\DataClass::create()
     */
    public function create()
    {
        throw new \Exception('Only reading is allowed in the administration database');
    }

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataClass\DataClass::delete_dependencies()
     */
    protected function delete_dependencies()
    {
        throw new \Exception('Only reading is allowed in the administration database');
    }

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataClass\DataClass::delete()
     */
    public function delete()
    {
        throw new \Exception('Only reading is allowed in the administration database');
    }

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataClass\DataClass::save()
     */
    public function save()
    {
        throw new \Exception('Only reading is allowed in the administration database');
    }

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataClass\DataClass::update()
     */
    public function update()
    {
        throw new \Exception('Only reading is allowed in the administration database');
    }
}