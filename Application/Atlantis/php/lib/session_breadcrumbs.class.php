<?php
namespace application\atlantis;

use libraries\Breadcrumb;
use libraries\Session;

/**
 * application.atlantis.
 *
 * @author GillardMagali
 */
class SessionBreadcrumbs
{
    const CLASS_NAME = __CLASS__;
    const SESSION_BREADCRUMBS = 'session_breadcrumbs';

    public static function get()
    {
        return unserialize(Session :: get(self :: SESSION_BREADCRUMBS));
    }

    /**
     *
     * @param multitype:Breadcrumb $breadcrumbs
     */
    public static function set($breadcrumbs = array())
    {
        $hashed_breadcrumbs = array();
        foreach ($breadcrumbs as $breadcrumb)
        {
            $hashed_breadcrumbs[md5(serialize($breadcrumb))] = $breadcrumb;
        }
        Session :: register(self :: SESSION_BREADCRUMBS, serialize($hashed_breadcrumbs));
    }

    public static function add(Breadcrumb $breadcrumb)
    {
        $breadcrumbs = self :: get();
        $hash = md5(serialize($breadcrumb));
        if (array_key_exists($hash, $breadcrumbs))
        {
            $keys = array_keys($breadcrumbs);
            $index = array_search($hash, $keys);
            $breadcrumbs = array_splice($breadcrumbs, 0, $index + 1);
        }
        else
        {
            $breadcrumbs[$hash] = $breadcrumb;
        }
        self :: set($breadcrumbs);
    }
}
