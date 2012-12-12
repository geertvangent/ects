<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

use common\libraries\InCondition;
use common\libraries\NotCondition;
use common\libraries\AndCondition;
use common\libraries\Translation;
use common\libraries\AdvancedElementFinderElementType;
use common\libraries\AdvancedElementFinderElement;
use user\UserDataManager;
use user\User;
use rights\UserEntity;

/**
 * Extension on the user entity specific for the course to limit the users
 * 
 * @author Sven Vanpoucke
 */
class RightsUserEntity extends UserEntity
{

    /**
     * Limits the users by id
     * 
     * @var Array<int>
     */
    private $limited_users;

    /**
     * Excludes the users by id
     * 
     * @var Array<int>
     */
    private $excluded_users;

    private $publication_id;

    /**
     * Singleton
     */
    private static $instance;

    static function get_instance($publication_id)
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self($publication_id);
        }
        return self :: $instance;
    }

    function __construct($publication_id, $limited_users = array(), $excluded_users = array())
    {
        $this->limited_users = $limited_users;
        $this->excluded_users = $excluded_users;
        $this->publication_id = $publication_id;
    }

    public function limit_users($limited_users)
    {
        $this->limited_users = $limited_users;
    }

    public function exclude_users($excluded_users)
    {
        $this->excluded_users = $excluded_users;
    }

    /**
     * Builds the condition with the limited and excluded users
     * 
     * @param Condition $condition
     * @return Condition
     */
    public function get_condition(Condition $condition)
    {
        $conditions = array();
        
        if ($this->limited_users)
        {
            $conditions[] = new InCondition(User :: PROPERTY_ID, $this->limited_users);
        }
        
        if ($this->excluded_users)
        {
            $conditions[] = new NotCondition(new InCondition(User :: PROPERTY_ID, $this->excluded_users));
        }
        
        if ($condition)
        {
            $conditions[] = $condition;
        }
        
        $count = count($conditions);
        if ($count > 1)
        {
            return new AndCondition($conditions);
        }
        
        if ($count == 1)
        {
            return $conditions[0];
        }
    }

    /**
     * Retrieves the type for the advanced element finder for the simple rights editor
     */
    function get_element_finder_type()
    {
        return new AdvancedElementFinderElementType('users', Translation :: get('Users'), __NAMESPACE__, 
                'publication_users_feed', array('publication_id' => $this->publication_id));
    }
}

?>
