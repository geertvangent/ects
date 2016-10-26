<?php
namespace Ehb\Libraries\Storage\DataManager\Administration\Factory;

use Chamilo\Configuration\Service\ConfigurationConsulter;
use Chamilo\Libraries\Storage\DataManager\Doctrine\DataSourceName;
use Chamilo\Libraries\Storage\DataManager\Interfaces\DataSourceNameFactoryInterface;

/**
 *
 * @package Chamilo\Libraries\Storage\DataManager\Doctrine\Factory
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class DataSourceNameFactory implements DataSourceNameFactoryInterface
{

    /**
     *
     * @var \Chamilo\Configuration\Service\ConfigurationConsulter
     */
    protected $configurationConsulter;

    /**
     *
     * @param \Chamilo\Configuration\Service\ConfigurationConsulter $configurationConsulter
     */
    public function __construct(ConfigurationConsulter $configurationConsulter)
    {
        $this->configurationConsulter = $configurationConsulter;
    }

    /**
     *
     * @return \Chamilo\Configuration\Service\ConfigurationConsulter
     */
    public function getConfigurationConsulter()
    {
        return $this->configurationConsulter;
    }

    /**
     *
     * @param \Chamilo\Configuration\Service\ConfigurationConsulter $configurationConsulter
     */
    public function setConfigurationConsulter(ConfigurationConsulter $configurationConsulter)
    {
        $this->configurationConsulter = $configurationConsulter;
    }

    /**
     *
     * @see \Chamilo\Libraries\Storage\DataManager\Interfaces\DataSourceNameFactoryInterface::getDataSourceName()
     */
    public function getDataSourceName()
    {
        $configurationConsulter = $this->getConfigurationConsulter();

        return new DataSourceName(
            $configurationConsulter->getSetting(array('Ehb\Libraries', 'administration_driver')),
            $configurationConsulter->getSetting(array('Ehb\Libraries', 'administration_username')),
            $configurationConsulter->getSetting(array('Ehb\Libraries', 'administration_host')),
            $configurationConsulter->getSetting(array('Ehb\Libraries', 'administration_database')),
            $configurationConsulter->getSetting(array('Ehb\Libraries', 'administration_password')),
            'utf8');
    }
}
