<?php
namespace Ehb\Libraries\Storage\DataManager\Administration\Factory;

use Chamilo\Libraries\Storage\DataManager\Doctrine\DataSourceName;

/**
 *
 * @package Chamilo\Libraries\Storage\DataManager\Doctrine\Factory
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class DataSourceNameFactory extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Factory\DataSourceNameFactory
{

    public function getDataSourceName()
    {
        $configurationService = $this->getConfigurationService();

        return new DataSourceName(
            $configurationService->getSetting(array('Ehb\Libraries', 'administration_driver')),
            $configurationService->getSetting(array('Ehb\Libraries', 'administration_username')),
            $configurationService->getSetting(array('Ehb\Libraries', 'administration_host')),
            $configurationService->getSetting(array('Ehb\Libraries', 'administration_database')),
            $configurationService->getSetting(array('Ehb\Libraries', 'administration_password')),
            'utf8');
    }
}
