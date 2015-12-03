<?php
namespace Ehb\Application\Sync\Data\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 * Tracks the visits of a user to the personal calendar
 *
 * @package application\ehb_sync\data
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class PortfolioVisit extends DataClass
{

    // Properties
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_PORTFOLIO_ID = 'portfolio_id';
    const PROPERTY_PUBLICATION_ID = 'publication_id';
    const PROPERTY_ITEM_ID = 'item_id';
    const PROPERTY_ACCESS_DATE = 'access_date';
    const PROPERTY_TIME = 'time';

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Returns the default property names of this dataclass
     *
     * @return \string[]
     */
    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_USER_ID,
                self :: PROPERTY_PORTFOLIO_ID,
                self :: PROPERTY_PUBLICATION_ID,
                self :: PROPERTY_ITEM_ID,
                self :: PROPERTY_ACCESS_DATE,
                self :: PROPERTY_TIME));
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters Functionality *
     * **************************************************************************************************************
     */

    /**
     * Returns the user_id
     *
     * @return int
     */
    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    /**
     * Sets the user_id
     *
     * @param int $user_id
     */
    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    /**
     * Returns the portfolio_id
     *
     * @return int
     */
    public function get_portfolio_id()
    {
        return $this->get_default_property(self :: PROPERTY_PORTFOLIO_ID);
    }

    /**
     * Sets the portfolio_id
     *
     * @param int $portfolio_id
     */
    public function set_portfolio_id($portfolio_id)
    {
        $this->set_default_property(self :: PROPERTY_PORTFOLIO_ID, $portfolio_id);
    }

    /**
     * Returns the publication_id
     *
     * @return int
     */
    public function get_publication_id()
    {
        return $this->get_default_property(self :: PROPERTY_PUBLICATION_ID);
    }

    /**
     * Sets the publication_id
     *
     * @param int $publication_id
     */
    public function set_publication_id($publication_id)
    {
        $this->set_default_property(self :: PROPERTY_PUBLICATION_ID, $publication_id);
    }

    /**
     * Returns the item_id
     *
     * @return int
     */
    public function get_item_id()
    {
        return $this->get_default_property(self :: PROPERTY_ITEM_ID);
    }

    /**
     * Sets the item_id
     *
     * @param int $item_id
     */
    public function set_item_id($item_id)
    {
        $this->set_default_property(self :: PROPERTY_ITEM_ID, $item_id);
    }

    /**
     * Returns the access_date
     *
     * @return int
     */
    public function get_access_date()
    {
        return $this->get_default_property(self :: PROPERTY_ACCESS_DATE);
    }

    /**
     * Sets the access_date
     *
     * @param int $access_date
     */
    public function set_access_date($access_date)
    {
        $this->set_default_property(self :: PROPERTY_ACCESS_DATE, $access_date);
    }

    /**
     * Returns the time
     *
     * @return int
     */
    public function get_time()
    {
        return $this->get_default_property(self :: PROPERTY_TIME);
    }

    /**
     * Sets the time
     *
     * @param int $time
     */
    public function set_time($time)
    {
        $this->set_default_property(self :: PROPERTY_TIME, $time);
    }
}
