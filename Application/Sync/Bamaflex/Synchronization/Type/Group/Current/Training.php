<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Training extends GroupSynchronization
{
    CONST IDENTIFIER = 'TRA';
    const RESULT_PROPERTY_CODE = 'code';
    const RESULT_PROPERTY_NAME = 'name';

    /**
     *
     * @return string
     */
    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER . '_' .
             $this->get_parameter(self::RESULT_PROPERTY_CODE);
    }

    /**
     *
     * @return string
     */
    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_NAME);
    }

    public function get_description()
    {
        return 'Opgelet! De gebruikers in deze groep worden over alle academiejaren heen geactualiseerd!';
    }
}
