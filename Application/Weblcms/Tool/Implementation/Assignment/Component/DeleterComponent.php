<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Component$DeleterComponent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class DeleterComponent extends Manager
{

    public function run()
    {
        $publicationIdentifiers = $this->getRequest()->get(
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);

        if (! is_array($publicationIdentifiers))
        {
            $publicationIdentifiers = array($publicationIdentifiers);
        }

        $failures = 0;

        foreach ($publicationIdentifiers as $publicationIdentifier)
        {
            $publication = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieve_by_id(
                ContentObjectPublication :: class_name(),
                $publicationIdentifier);

            if ($this->is_allowed(WeblcmsRights :: DELETE_RIGHT, $publication))
            {
                if ($publication->delete())
                {
                    $condition = new EqualityCondition(
                        new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
                        new StaticConditionVariable($publicationIdentifier));

                    $entries = DataManager :: retrieves(
                        Entry :: class_name(),
                        new DataClassRetrievesParameters($condition));

                    while ($entry = $entries->next_result())
                    {
                        $entry->delete();
                    }
                }
                else
                {
                    $failures ++;
                }
            }
            else
            {
                $failures ++;
            }
        }

        if ($failures == 0)
        {
            if (count($publicationIdentifiers) > 1)
            {
                $message = htmlentities(Translation :: get('ContentObjectPublicationsDeleted'));
            }
            else
            {
                $message = htmlentities(Translation :: get('ContentObjectPublicationDeleted'));
            }
        }
        else
        {
            $message = htmlentities(Translation :: get('ContentObjectPublicationsNotDeleted'));
        }

        $this->redirect(
            $message,
            $failures > 0,
            array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID => null, 'tool_action' => null));
    }
}
