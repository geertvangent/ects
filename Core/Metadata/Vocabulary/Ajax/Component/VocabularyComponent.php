<?php
namespace Ehb\Core\Metadata\Vocabulary\Ajax\Component;

/**
 *
 * @package Chamilo\Core\User\Ajax
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class VocabularyComponent extends \Ehb\Core\Metadata\Vocabulary\Ajax\Manager
{

    public function run()
    {
        $options = array('ZOO Antwerpen', 'Planckendael', 'Pairi Daiza');

        header('Content-type: application/json');
        echo json_encode($options);
    }
}