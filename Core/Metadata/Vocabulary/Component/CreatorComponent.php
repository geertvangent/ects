<?php
namespace Ehb\Core\Metadata\Vocabulary\Component;

use Ehb\Core\Metadata\Vocabulary\Form\VocabularyForm;
use Ehb\Core\Metadata\Vocabulary\Manager;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Exceptions\NoObjectSelectedException;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\VocabularyTranslation;

/**
 * Controller to create the schema
 */
class CreatorComponent extends Manager
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

        if (is_null($this->getSelectedElementId()))
        {
            throw new NoObjectSelectedException(Translation :: get('Element', null, 'Ehb\Core\Metadata\Element'));
        }

        if (is_null($this->getSelectedUserId()))
        {
            throw new NoObjectSelectedException(Translation :: get('User', null, 'Ehb\Core\Metadata\Vocabulary'));
        }

        $vocabulary = new Vocabulary();
        $vocabulary->set_element_id($this->getSelectedElementId());
        $vocabulary->set_user_id($this->getSelectedUserId());

        $form = new VocabularyForm(
            $vocabulary,
            $this->get_url(
                array(
                    \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getSelectedElementId(),
                    self :: PARAM_USER_ID => $this->getSelectedUserId())));

        if ($form->validate())
        {
            try
            {
                $values = $form->exportValues();

                $vocabulary->set_value($values[Vocabulary :: PROPERTY_VALUE]);
                $success = $vocabulary->create();

                if ($success)
                {
                    foreach ($values[self :: PROPERTY_TRANSLATION] as $isocode => $value)
                    {
                        $translation = new VocabularyTranslation();
                        $translation->set_vocabulary_id($vocabulary->get_id());
                        $translation->set_isocode($isocode);
                        $translation->set_value($value);
                        $translation->create();
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
                    \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->getSelectedElementId(),
                    self :: PARAM_USER_ID => $this->getSelectedUserId()));
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