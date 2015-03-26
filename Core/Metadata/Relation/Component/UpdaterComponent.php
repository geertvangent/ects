<?php
namespace Ehb\Core\Metadata\Relation\Component;

use Ehb\Core\Metadata\Relation\Form\RelationTypeForm;
use Ehb\Core\Metadata\Relation\Manager;
use Ehb\Core\Metadata\Relation\Storage\DataClass\RelationType;
use Ehb\Core\Metadata\Relation\Storage\DataManager;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Core\Metadata\Relation\Storage\DataClass\RelationTypeTranslation;

/**
 * Controller to update the schema
 *
 * @package Ehb\Core\Metadata\Relation\Component
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UpdaterComponent extends Manager
{

    /**
     * Executes this controller
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        $relation_type_id = Request :: get(self :: PARAM_RELATION_TYPE_ID);
        $relationType = DataManager :: retrieve_by_id(RelationType :: class_name(), $relation_type_id);

        $form = new RelationTypeForm($relationType, $this->get_url());

        if ($form->validate())
        {
            try
            {
                $values = $form->exportValues();

                $relationType->set_name($values[RelationType :: PROPERTY_NAME]);
                $success = $relationType->update();

                if ($success)
                {
                    $translations = $relationType->getTranslations();

                    foreach ($values[self :: PROPERTY_TRANSLATION] as $isocode => $value)
                    {
                        if ($translations[$isocode] instanceof RelationTypeTranslation)
                        {
                            $translation = $translations[$isocode];
                            $translation->set_value($value);
                            $translation->update();
                        }
                        else
                        {
                            $translation = new RelationTypeTranslation();
                            $translation->set_relation_type_id($relationType->get_id());
                            $translation->set_isocode($isocode);
                            $translation->set_value($value);
                            $translation->create();
                        }
                    }
                }

                $translation = $success ? 'ObjectUpdated' : 'ObjectNotUpdated';

                $message = Translation :: get(
                    $translation,
                    array('OBJECT' => Translation :: get('RelationType')),
                    Utilities :: COMMON_LIBRARIES);
            }
            catch (\Exception $ex)
            {
                $success = false;
                $message = $ex->getMessage();
            }

            $this->redirect($message, ! $success, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }
        else
        {
            $html = array();

            $html[] = $this->render_header();
            $html[] = $form->toHtml();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }

    /**
     * Returns the additional parameters
     *
     * @return string[]
     */
    public function get_additional_parameters()
    {
        return array(self :: PARAM_RELATION_TYPE_ID);
    }
}