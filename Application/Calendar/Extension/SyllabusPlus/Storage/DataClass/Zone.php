<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Zone
{

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var string
     */
    private $code;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @param string $year
     * @param string $id
     * @param string $code
     * @param string $name
     */
    public function __construct($year, $id, $code, $name)
    {
        $this->year = $year;
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}