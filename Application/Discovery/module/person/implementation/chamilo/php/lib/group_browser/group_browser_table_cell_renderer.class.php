<?php
namespace application\discovery\module\person\implementation\chamilo;

use group\Group;
use group\DefaultGroupTableCellRenderer;
use common\libraries\Utilities;
use common\libraries\Translation;

class GroupBrowserTableCellRenderer extends DefaultGroupTableCellRenderer
{

    /**
     * The repository browser component
     */
    private $browser;

    /**
     * Constructor
     *
     * @param RepositoryManagerBrowserComponent $browser
     */
    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    // Inherited
    public function render_cell($column, $group)
    {
        // Add special features here
        switch ($column->get_name())
        {
            // Exceptions that need post-processing go here ...
            case Group :: PROPERTY_NAME :
                $title = parent :: render_cell($column, $group);
                $title_short = $title;
                if (strlen($title_short) > 53)
                {
                    $title_short = mb_substr($title_short, 0, 50) . '&hellip;';
                }

                $show_url = false;

                if (! $this->browser->get_context()->get_user()->is_platform_admin())
                {
                    foreach ($this->browser->get_allowed_groups() as $allowed_group_id)
                    {
                        if ($group->is_child_of($allowed_group_id) || $group->is_parent_of($allowed_group_id) ||
                             $allowed_group_id == $group->get_id())
                        {
                            $show_url = true;
                            break;
                        }
                    }
                }
                else
                {
                    $show_url = true;
                }

                if ($show_url)
                {

                    return '<a href="' . htmlentities($this->browser->get_group_viewing_url($group)) . '" title="' .
                         $title . '">' . $title_short . '</a>';
                }
                else
                {
                    return $title_short;
                }
            case Group :: PROPERTY_DESCRIPTION :
                $description = strip_tags(parent :: render_cell($column, $group));
                // if(strlen($description) > 175)
                // {
                // $description = mb_substr($description,0,170).'&hellip;';
                // }
                return Utilities :: truncate_string($description);
            case Translation :: get('Users', null, 'user') :
                return $group->count_users(true, true);
            case Translation :: get('Subgroups') :
                return $group->count_subgroups(true, true);
        }

        return parent :: render_cell($column, $group);
    }
}
