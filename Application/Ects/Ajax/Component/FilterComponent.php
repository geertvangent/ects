<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Ects\Storage\DataManager;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FilterComponent extends \Ehb\Application\Ects\Ajax\Manager
{
    // Parameters
    const PARAM_YEAR = 'year';
    const PARAM_FACULTY = 'faculty';
    const PARAM_TYPE = 'type';
    const PARAM_TEXT = 'text';

    // Properties
    const PROPERTY_YEAR = 'year';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TYPE = 'type';

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array();
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $years = DataManager::getYears();
        $faculties = DataManager::getFacultiesForYear('2016-17');
        $types = DataManager::getTypesForYear('2016-17');

        var_dump($years, $faculties, $types);
        exit();

        $categoryId = $this->getPostDataValue(self::PARAM_PARENT_ID);

        if (true)
        {
            $jsonAjaxResult = new JsonAjaxResult();

            $jsonAjaxResult->set_properties(
                array(
                    self::PROPERTY_CONTENT_OBJECT_ID => null,
                    self::PROPERTY_VIEW_BUTTON => null,
                    self::PROPERTY_UPLOADED_MESSAGE => null));

            $jsonAjaxResult->display();
        }
        else
        {
            JsonAjaxResult::general_error(Translation::get('ObjectNotImported'));
        }
    }
}
