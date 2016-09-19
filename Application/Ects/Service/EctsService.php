<?php
namespace Ehb\Application\Ects\Service;

use Ehb\Application\Ects\Repository\EctsRepository;

/**
 *
 * @package Ehb\Application\Ects\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class EctsService
{

    /**
     *
     * @var \Ehb\Application\Ects\Repository\EctsRepository
     */
    private $ectsRepository;

    /**
     *
     * @param \Ehb\Application\Ects\Repository\EctsRepository $ectsRepository
     */
    public function __construct(EctsRepository $ectsRepository)
    {
        $this->ectsRepository = $ectsRepository;
    }

    /**
     *
     * @return \Ehb\Application\Ects\Repository\EctsRepository
     */
    public function getEctsRepository()
    {
        return $this->ectsRepository;
    }

    /**
     *
     * @param \Ehb\Application\Ects\Repository\EctsRepository $ectsRepository
     */
    public function setEctsRepository(EctsRepository $ectsRepository)
    {
        $this->ectsRepository = $ectsRepository;
    }

    /**
     *
     * @return string[]
     */
    public function getYears()
    {
        return $this->getEctsRepository()->findYears();
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultiesForYear($year)
    {
        return $this->getEctsRepository()->findFacultiesForYear($year);
    }

    /**
     *
     * @return string[]
     */
    public function getTypesForYear($year)
    {
        return $this->getEctsRepository()->findTypesForYear($year);
    }
}