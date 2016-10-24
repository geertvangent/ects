<?php
namespace Ehb\Libraries\Storage\DataManager\Administration\Processor;

/**
 *
 * @package Ehb\Libraries\Storage\DataManager\Administration\Processor
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class RecordProcessor extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Processor\RecordProcessor
{

    /**
     *
     * @param mixed $field
     * @return mixed
     */
    protected function processField($field, $fieldType = null)
    {
        $field = parent::processField($field, $fieldType);
        $field = $this->processEncoding($field);

        return $field;
    }

    /**
     *
     * @param mixed $field
     * @return string
     */
    protected function processEncoding($field)
    {
        if (is_string($field) && ! is_numeric($field) && ! mb_check_encoding($field, 'UTF-8'))
        {
            $field = trim(mb_convert_encoding($field, 'UTF-8', 'Windows-1252'));
        }

        return $field;
    }
}