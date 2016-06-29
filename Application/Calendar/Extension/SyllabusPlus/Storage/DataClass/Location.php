<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Location extends Zone
{

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Department
     */
    private $department;

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone
     */
    private $zone;

    /**
     *
     * @param integer $year
     * @param string $id
     * @param string $code
     * @param string $name
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Department $department
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone $zone
     */
    public function __construct($year, $id, $code, $name, Department $department = null, Zone $zone = null)
    {
        parent::__construct($year, $id, $code, $name);

        $this->department = $department;
        $this->zone = $zone;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Department $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone $zone
     */
    public function setZone(Zone $zone)
    {
        $this->zone = $zone;
    }
}