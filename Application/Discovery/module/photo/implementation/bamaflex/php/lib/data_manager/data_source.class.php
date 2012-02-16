<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use application\discovery\module\photo\DataManagerInterface;

use application\discovery\module\profile\Photo;
use \MDB2_Error;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    function retrieve_photo($id)
    {
        $query = 'SELECT * FROM v_discovery_profile_photo WHERE id = "' . $id . '"';
        
        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();
        
        if (! $result instanceof MDB2_Error)
        {
            $object = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
            
            $photo = new Photo();
            $photo->set_mime_type('image/jpeg');
            $photo->set_data(base64_encode($object->photo));
            
            return $photo;
        }
        else
        {
            return false;
        }
    }
}
?>