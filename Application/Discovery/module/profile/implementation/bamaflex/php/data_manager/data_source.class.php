<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\module\profile\DataManagerInterface;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    function retrieve_profile($id)
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_profile_basic] WHERE p_persoon = 41618';

        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();

        $employees = array();

        return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
    }
}
?>