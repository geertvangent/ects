<?php
namespace Ehb\Configuration\Service;

use Chamilo\Libraries\Architecture\Exceptions\UserException;

/**
 *
 * @package Chamilo\Configuration\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FileConfigurationLoader extends \Chamilo\Configuration\Service\FileConfigurationLoader
{

    /**
     *
     * @return string
     */
    protected function getSettingsContext()
    {
        return 'Ehb\Configuration';
    }

    /**
     *
     * @return string
     */
    protected function getConfigurationFileName()
    {
        return 'configuration.ehb.ini';
    }

    /**
     *
     * @return string[]
     */
    public function getData()
    {
        if ($this->isAvailable())
        {
            $settings = $this->getSettingsFromFile();
        }
        else
        {
            throw new UserException('Ehb configuration file not found');
        }
        
        return $settings;
    }

    /**
     *
     * @return string
     */
    public function getIdentifier()
    {
        return md5(__CLASS__);
    }
}
