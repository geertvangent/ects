<?php
namespace Ehb\Core\Metadata\Vocabulary\Component;

use Ehb\Core\Metadata\Vocabulary\Form\VocabularyForm;
use Ehb\Core\Metadata\Vocabulary\Manager;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Ehb\Core\Metadata\Vocabulary\Storage\DataManager;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\VocabularyTranslation;

/**
 * Controller to update the controlled vocabulary
 *
 * @package core\metadata
 * @author Sven Vanpoucke - Hogeschool Gent
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

        $vocabulary_id = Request :: get(self :: PARAM_VOCABULARY_ID);
        $vocabulary = DataManager :: retrieve_by_id(Vocabulary :: class_name(), $vocabulary_id);

        $form = new VocabularyForm($vocabulary, $this->get_url(array(self :: PARAM_VOCABULARY_ID => $vocabulary_id)));

        if ($form->validate())
        {
            try
            {
                $values = $form->exportValues();

                $vocabulary->set_value($values[Vocabulary :: PROPERTY_VALUE]);

                $success = $vocabulary->update();

                if ($success)
                {
                    $translations = $vocabulary->getTranslations();

                    foreach ($values[self :: PROPERTY_TRANSLATION] as $isocode => $value)
                    {
                        if ($translations[$isocode] instanceof VocabularyTranslation)
                        {
                            $translation = $translations[$isocode];
                            $translation->set_value($value);
                            $translation->update();
                        }
                        else
                        {
                            $translation = new VocabularyTranslation();
                            $translation->set_vocabulary_id($vocabulary->get_id());
                            $translation->set_isocode($isocode);
                            $translation->set_value($value);
                            $translation->create();
                        }
                    }
                }

                $translation = $success ? 'ObjectCreated' : 'ObjectNotCreated';

                $message = Translation :: get(
                    $translation,
                    array('OBJECT' => Translation :: get('Vocabulary')),
                    Utilities :: COMMON_LIBRARIES);
            }
            catch (\Exception $ex)
            {
                $success = false;
                $message = $ex->getMessage();
            }

            $this->redirect(
                $message,
                ! $success,
                array(
                    self :: PARAM_ACTION => self :: ACTION_BROWSE,
                    \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $vocabulary->get_element_id(),
                    self :: PARAM_USER_ID => $vocabulary->get_user_id()));
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
}