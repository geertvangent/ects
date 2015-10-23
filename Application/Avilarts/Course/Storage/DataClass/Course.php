<?php
namespace Ehb\Application\Avilarts\Course\Storage\DataClass;

use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\CourseType\Storage\DataClass\CourseType;
use Ehb\Application\Avilarts\Course\Storage\DataManager;
use Ehb\Application\Avilarts\Rights\CourseManagementRights;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublicationCategory;
use Ehb\Application\Avilarts\Storage\DataClass\CourseModuleLastAccess;
use Ehb\Application\Avilarts\Storage\DataClass\CourseRequest;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSection;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSetting;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTool;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTypeUserCategoryRelCourse;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 * This class represents a course in the weblcms.
 * 
 * @package application\Avilarts\course;
 * @author Previously Author Unknown
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class Course extends DataClass
{
    /**
     * **************************************************************************************************************
     * Table Properties *
     * **************************************************************************************************************
     */
    const PROPERTY_COURSE_TYPE_ID = 'course_type_id';
    const PROPERTY_TITULAR_ID = 'titular_id';
    const PROPERTY_VISUAL_CODE = 'visual_code';
    const PROPERTY_TITLE = 'title';
    const PROPERTY_LAST_VISIT = 'last_visit';
    const PROPERTY_LAST_EDIT = 'last_edit';
    const PROPERTY_CREATION_DATE = 'creation_date';
    const PROPERTY_EXPIRATION_DATE = 'expiration_date';
    const PROPERTY_LANGUAGE = 'language';
    const PROPERTY_CATEGORY_ID = 'category_id';
    
    /**
     * **************************************************************************************************************
     * Foreign properties *
     * **************************************************************************************************************
     */
    const FOREIGN_PROPERTY_COURSE_TYPE = 'course_type';
    
    /**
     * **************************************************************************************************************
     * Additional properties *
     * **************************************************************************************************************
     */
    
    /**
     * Property only used when joining with the course type table
     */
    const PROPERTY_COURSE_TYPE_TITLE = 'course_type_title';

    /**
     * **************************************************************************************************************
     * Cache properties *
     * **************************************************************************************************************
     */
    
    /**
     * Caches the course admin checks
     * 
     * @var boolean[]
     */
    private $is_course_admin_cache;

    /**
     * Caches the users that are course admins in this course
     * 
     * @var \application\Avilarts\course\CourseUserRelation[]
     */
    private $course_admin_users_cache;

    /**
     * Caches the groups that are course admins in this course
     * 
     * @var \application\Avilarts\course\CourseGroupRelation[]
     */
    private $course_admin_groups_cache;

    /**
     * **************************************************************************************************************
     * Old properties *
     * **************************************************************************************************************
     */
    
    /**
     * Keeps track of the previous course type id before updating so the location can be moved if a different course
     * type is selected
     * 
     * @var int
     */
    private $old_course_type_id;

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the default properties of this dataclass
     * 
     * @return String[] - The property names.
     */
    public static function get_default_property_names($extended_properties = array())
    {
        $extended_properties[] = self :: PROPERTY_COURSE_TYPE_ID;
        $extended_properties[] = self :: PROPERTY_TITULAR_ID;
        $extended_properties[] = self :: PROPERTY_TITLE;
        $extended_properties[] = self :: PROPERTY_VISUAL_CODE;
        $extended_properties[] = self :: PROPERTY_CREATION_DATE;
        $extended_properties[] = self :: PROPERTY_EXPIRATION_DATE;
        $extended_properties[] = self :: PROPERTY_LAST_EDIT;
        $extended_properties[] = self :: PROPERTY_LAST_VISIT;
        $extended_properties[] = self :: PROPERTY_CATEGORY_ID;
        $extended_properties[] = self :: PROPERTY_LANGUAGE;
        
        return parent :: get_default_property_names($extended_properties);
    }

    /**
     * Creates this course object
     * 
     * @return boolean
     */
    public function create($automated_values = true, $create_in_batch = false)
    {
        if ($automated_values)
        {
            $now = time();
            $this->set_last_visit($now);
            $this->set_last_edit($now);
            $this->set_creation_date($now);
            $this->set_expiration_date($now);
        }
        
        if (! parent :: create())
        {
            return false;
        }
        
        if (! $this->initialize_course_sections())
        {
            return false;
        }
        
        if (! $this->create_locations($create_in_batch))
        {
            return false;
        }
        
        if (! $this->create_root_course_group())
        {
            return false;
        }
        
        return true;
    }

    /**
     * Updates this course object If the name of the course changes, the name of the root chamilo course group needs to
     * change
     * 
     * @return boolean
     */
    public function update()
    {
        $course_group = \Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataManager :: retrieve_course_group_root(
            $this->get_id());
        
        if ($course_group)
        {
            if ($course_group->get_name() != $this->get_title())
            {
                $course_group->set_name($this->get_title());
                $course_group->update();
            }
        }
        
        $old_course_type_id = $this->old_course_type_id;
        
        if (! is_null($old_course_type_id))
        {
            $location = $this->get_rights_location();
            
            if (! $location->move($this->get_parent_rights_location()->get_id()))
            {
                return false;
            }
        }
        
        return parent :: update();
    }

    /**
     * Returns the dependencies for this dataclass
     * 
     * @return string[string]
     *
     */
    protected function get_dependencies()
    {
        $id = $this->get_id();
        
        return array(
            ContentObjectPublicationCategory :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    ContentObjectPublicationCategory :: class_name(), 
                    ContentObjectPublicationCategory :: PROPERTY_COURSE), 
                new StaticConditionVariable($id)), 
            ContentObjectPublication :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(), 
                    ContentObjectPublication :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseSection :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(CourseSection :: class_name(), CourseSection :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseModuleLastAccess :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    CourseModuleLastAccess :: class_name(), 
                    CourseModuleLastAccess :: PROPERTY_COURSE_CODE), 
                new StaticConditionVariable($id)), 
            CourseGroupRelation :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    CourseGroupRelation :: class_name(), 
                    CourseGroupRelation :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseUserRelation :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    CourseUserRelation :: class_name(), 
                    CourseUserRelation :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseRequest :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(CourseRequest :: class_name(), CourseRequest :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseGroup :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_COURSE_CODE), 
                new StaticConditionVariable($id)), 
            CourseRelCourseSetting :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    CourseRelCourseSetting :: class_name(), 
                    CourseRelCourseSetting :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)), 
            CourseTypeUserCategoryRelCourse :: class_name() => new EqualityCondition(
                new PropertyConditionVariable(
                    CourseTypeUserCategoryRelCourse :: class_name(), 
                    CourseTypeUserCategoryRelCourse :: PROPERTY_COURSE_ID), 
                new StaticConditionVariable($id)));
    }

    /**
     * Deletes the dependencies for this course
     * 
     * @return boolean
     */
    protected function delete_dependencies()
    {
        parent :: delete_dependencies();
        
        $id = $this->get_id();
        
        // Remove subtree location
        $location = CourseManagementRights :: get_instance()->get_courses_subtree_root($id);
        if ($location)
        {
            if (! $location->delete())
            {
                return false;
            }
        }
        
        // Remove location in root tree
        $location = $this->get_rights_location();
        
        if ($location)
        {
            if (! $location->delete())
            {
                return false;
            }
        }
        
        return true;
    }

    /**
     * **************************************************************************************************************
     * Rights Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the rights location for this object
     * 
     * @return RightsLocation
     */
    public function get_rights_location()
    {
        return CourseManagementRights :: get_instance()->get_weblcms_location_by_identifier_from_courses_subtree(
            CourseManagementRights :: TYPE_COURSE, 
            $this->get_id(), 
            0);
    }

    /**
     * Returns the (possible) parent rights location depending on this objects properties
     * 
     * @return RightsLocation
     */
    public function get_parent_rights_location()
    {
        $parent = CourseManagementRights :: get_instance()->get_weblcms_location_by_identifier_from_courses_subtree(
            CourseManagementRights :: TYPE_COURSE_TYPE, 
            $this->get_course_type_id(), 
            0);
        
        if (! $parent)
        {
            $parent = CourseManagementRights :: get_courses_subtree_root(0);
        }
        
        return $parent;
    }

    /**
     * Returns the available rights for the course management
     * 
     * @return int[string]
     */
    public function get_available_course_management_rights()
    {
        return CourseManagementRights :: get_instance()->get_base_course_management_rights();
    }

    /**
     * Checks whether or not this object can change the given course management right
     * 
     * @param int $right_id
     *
     * @return boolean
     */
    public function can_change_course_management_right($right_id)
    {
        $available_rights = $this->get_available_course_management_rights();
        if (! in_array($right_id, $available_rights))
        {
            return false;
        }
        
        $course_type = $this->get_course_type();
        if (! $course_type)
        {
            return true;
        }
        
        return ! CourseManagementRights :: get_instance()->is_right_locked_for_base_object($course_type, $right_id);
    }

    /**
     * **************************************************************************************************************
     * Course Settings Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Retrieves course setting values for the given setting name and tool id
     * 
     * @param string $setting_name
     * @param int $tool_id
     *
     * @return string[]
     */
    public function get_course_setting($setting_name, $tool_id = 0)
    {
        return CourseSettingsController :: get_instance()->get_course_setting($this->get_id(), $setting_name, $tool_id);
    }

    /**
     * Retrieves the default values for a given course setting
     * 
     * @param string $setting_name
     * @param int $tool_id
     *
     * @return string[]
     */
    public function get_default_course_setting($setting_name, $tool_id)
    {
        return CourseSettingsController :: get_instance()->get_course_type_setting(
            $this->get_course_type_id(), 
            $setting_name, 
            $tool_id);
    }

    /**
     * Delegation function to create course settings from given values
     * 
     * @param string[] $values
     * @param boolean $force [OPTIONAL] - default false - Sets the values even if the base object is not allowed to.
     * @return boolean
     */
    public function create_course_settings_from_values($values, $force = false)
    {
        return CourseSettingsController :: get_instance()->handle_settings_for_object_with_given_values(
            $this, 
            $values, 
            CourseSettingsController :: SETTING_ACTION_CREATE, 
            $force);
    }

    /**
     * Delegation function to update course settings from given values
     * 
     * @param string $values
     *
     * @return boolean
     */
    public function update_course_settings_from_values($values)
    {
        return CourseSettingsController :: get_instance()->handle_settings_for_object_with_given_values(
            $this, 
            $values, 
            CourseSettingsController :: SETTING_ACTION_UPDATE);
    }

    /**
     * Creates a course setting relation for the given course setting object
     * 
     * @param mixed[string] CourseSetting
     * @param boolean $locked
     *
     * @throws \Exception
     *
     * @return CourseRelCourseSetting
     */
    public function create_course_setting_relation($course_setting, $locked)
    {
        $course_rel_setting = new CourseRelCourseSetting();
        $course_rel_setting->set_course_setting_id($course_setting[CourseSetting :: PROPERTY_ID]);
        $course_rel_setting->set_course($this);
        
        if (! $course_rel_setting->create())
        {
            throw new \Exception(Translation :: get('CouldNotCreateCourseRelCourseSetting'));
        }
        
        return $course_rel_setting;
    }

    /**
     * Updates a course setting relation for the given course setting object
     * 
     * @param mixed[string] CourseSetting
     * @param boolean $locked
     *
     * @return CourseTypeRelCourseSetting
     */
    public function update_course_setting_relation($course_setting, $locked)
    {
        return $this->retrieve_course_setting_relation($course_setting);
    }

    /**
     * Retrieves a course setting relation object for the given course setting object
     * 
     * @param mixed[string] CourseSetting
     * @return CourseRelCourseSetting
     */
    public function retrieve_course_setting_relation($course_setting)
    {
        $conditions = array();
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseRelCourseSetting :: class_name(), 
                CourseRelCourseSetting :: PROPERTY_COURSE_SETTING_ID), 
            new StaticConditionVariable($course_setting[CourseSetting :: PROPERTY_ID]));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseRelCourseSetting :: class_name(), 
                CourseRelCourseSetting :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable($this->get_id()));
        
        $condition = new AndCondition($conditions);
        
        return DataManager :: retrieve(
            CourseRelCourseSetting :: class_name(), 
            new DataClassRetrieveParameters($condition));
    }

    /**
     * Returns whether or not a given course setting is locked for this object.
     * Since courses do not support locking of
     * course settings the course settings are never locked
     * 
     * @param mixed[string] CourseSetting
     * @return boolean
     */
    public function is_course_setting_locked($course_setting)
    {
        return false;
    }

    /**
     * Returns whether or not a given course setting can be changed by this object
     * 
     * @param mixed[string] CourseSetting
     * @return boolean
     */
    public function can_change_course_setting($course_setting)
    {
        $course_type = $this->get_course_type();
        if (! $course_type)
        {
            return true;
        }
        
        return ! $course_type->is_course_setting_locked($course_setting);
    }

    /**
     * **************************************************************************************************************
     * Helper Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Creates the necessary locations for this dataclass Creates a location for the given course in the courses tree
     * Creates a root location for the course subtree Set view right for everyone on root location Creates a location
     * for each tool in the course subtree
     * 
     * @param boolean $create_in_batch - Whether or not the left and right values should be calculated
     * @return boolean
     */
    private function create_locations($create_in_batch = false)
    {
        // Create location in the course subtree
        $parent_id = $this->get_parent_rights_location()->get_id();
        
        if (! CourseManagementRights :: get_instance()->create_location_in_courses_subtree(
            CourseManagementRights :: TYPE_COURSE, 
            $this->get_id(), 
            $parent_id, 
            0, 
            $create_in_batch, 
            0))
        {
            return false;
        }
        
        // Create course subtree root location
        $course_subtree_root_location = CourseManagementRights :: get_instance()->create_subtree_root_location(
            $this->get_id(), 
            CourseManagementRights :: TREE_TYPE_COURSE, 
            true);
        
        if (! $course_subtree_root_location)
        {
            return false;
        }
        
        $course_subtree_root_location_id = $course_subtree_root_location->get_id();
        
        // Set view right for everyone on root location
        if (! CourseManagementRights :: get_instance()->invert_location_entity_right(
            CourseManagementRights :: VIEW_RIGHT, 
            0, 
            0, 
            CourseManagementRights :: get_instance()->get_courses_subtree_root_id($this->get_id())))
        {
            return false;
        }
        
        // Create a location for each tool
        $tools = DataManager :: retrieves(CourseTool :: class_name());
        while ($tool = $tools->next_result())
        {
            if (! CourseManagementRights :: get_instance()->create_location_in_courses_subtree(
                CourseManagementRights :: TYPE_COURSE_MODULE, 
                $tool->get_id(), 
                $course_subtree_root_location_id, 
                $this->get_id(), 
                $create_in_batch))
            {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Initializes the course sections for this course
     * 
     * @return boolean
     */
    private function initialize_course_sections()
    {
        $sections = array();
        // translationm :: get will be called on display, depending on the course language setting
        // these strings go to the database
        $sections[] = array('name' => 'SectionTools', 'type' => 1, 'order' => 1);
        $sections[] = array('name' => 'SectionLinks', 'type' => 2, 'order' => 2);
        $sections[] = array('name' => 'SectionDisabled', 'type' => 0, 'order' => 3);
        $sections[] = array('name' => 'SectionCourseAdministration', 'type' => 3, 'order' => 4);
        
        foreach ($sections as $section)
        {
            $course_section = new CourseSection();
            $course_section->set_course_id($this->get_id());
            $course_section->set_name($section['name']);
            $course_section->set_type($section['type']);
            $course_section->set_visible(true);
            if (! $course_section->create())
            {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Creates a course group in the course with the same name as the course
     * 
     * @return boolean
     */
    private function create_root_course_group()
    {
        $group = new CourseGroup();
        $group->set_course_code($this->get_id());
        $group->set_name($this->get_title());
        $group->set_parent_id(0);
        return $group->create();
    }

    /**
     * **************************************************************************************************************
     * Delegation Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Checks whether the given user is a course admin in this course
     * 
     * @param User $user
     *
     * @return boolean
     */
    public function is_course_admin($user)
    {
        // fix for view as
        $va_id = Session :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_ID);
        $course_id = Session :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID);
        $id = $user->get_id();
        if (isset($va_id) && isset($course_id))
        {
            if ($course_id == $this->get_id())
            {
                $id = $va_id;
            }
            else
            {
                if ($user->is_platform_admin())
                {
                    return true;
                }
            }
        }
        else
        {
            if ($user->is_platform_admin())
            {
                return true;
            }
        }
        
        if (is_null($this->is_course_admin_cache[$id]))
        {
            $studentview = Session :: retrieve('studentview');
            
            if ($studentview)
            {
                return false;
            }
            
            $this->is_course_admin_cache[$id] = $this->is_subscribed_as_course_admin($user);
        }
        
        return $this->is_course_admin_cache[$id];
    }

    /**
     * Checks whether the current user is subscribed as course admin of the given course
     * 
     * @param User $user
     *
     * @return boolean
     */
    public function is_subscribed_as_course_admin($user)
    {
        return DataManager :: is_teacher_by_direct_subscription($this->get_id(), $user->get_id()) ||
             DataManager :: is_teacher_by_platform_group_subscription($this->get_id(), $user);
    }

    /**
     * Retrieves the course admin users for this course
     * 
     * @return \core\user\User[]
     */
    public function get_course_admin_users()
    {
        if (! $this->course_admin_users_cache)
        {
            $this->course_admin_users_cache = DataManager :: retrieve_teachers_directly_subscribed_to_course(
                $this->get_id());
        }
        return $this->course_admin_users_cache;
    }

    /**
     * Retrieves the course admin groups for this course
     * 
     * @return \group\Group[]
     */
    public function get_course_admin_groups()
    {
        if (! $this->course_admin_groups_cache)
        {
            $this->course_admin_groups_cache = DataManager :: retrieve_groups_subscribed_as_teacher($this->get_id());
        }
        return $this->course_admin_groups_cache;
    }

    /**
     * Returns if this course has subscribed users
     * 
     * @return boolean
     */
    public function has_subscribed_users()
    {
        $relation_condition = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable($this->get_id()));
        return DataManager :: count(CourseUserRelation :: class_name(), $relation_condition) > 0;
    }

    /**
     * Gets the subscribed users of this course
     * 
     * @return \application\Avilarts\course\CourseUserRelation[]
     */
    public function get_subscribed_users()
    {
        $relation_condition = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable($this->get_id()));
        return DataManager :: retrieves(CourseUserRelation :: class_name(), $relation_condition)->as_array();
    }

    /**
     * Returns if this course has subscribed groups
     * 
     * @return boolean
     */
    public function has_subscribed_groups()
    {
        $relation_condition = new EqualityCondition(
            new PropertyConditionVariable(CourseGroupRelation :: class_name(), CourseGroupRelation :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable($this->get_id()));
        return DataManager :: count(CourseGroupRelation :: class_name(), $relation_condition) > 0;
    }

    /**
     * Gets the subscribed groups of this course
     * 
     * @return \application\Avilarts\course\CourseGroupRelation[]
     */
    public function get_subscribed_groups()
    {
        $relation_condition = new EqualityCondition(
            new PropertyConditionVariable(CourseGroupRelation :: class_name(), CourseGroupRelation :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable($this->get_id()));
        return DataManager :: retrieves(CourseGroupRelation :: class_name(), $relation_condition)->as_array();
    }

    /**
     * Gets the course groups of this course
     * 
     * @return \application\Avilarts\CourseGroup[]
     */
    public function get_course_groups($as_array = true)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_COURSE_CODE), 
            new StaticConditionVariable($this->get_id()));
        $result = DataManager :: retrieves(
            CourseGroup :: class_name(), 
            new DataClassRetrievesParameters(
                $condition, 
                null, 
                null, 
                array(
                    new OrderBy(
                        new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_TITLE)))));
        
        return ($as_array ? $result->as_array() : $result);
    }

    /**
     * **************************************************************************************************************
     * Getters and Setters *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the course type id of this course object
     * 
     * @return int
     */
    public function get_course_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE_TYPE_ID);
    }

    /**
     * Returns the titular id of this course object
     * 
     * @return int
     */
    public function get_titular_id()
    {
        return $this->get_default_property(self :: PROPERTY_TITULAR_ID);
    }

    /**
     * Returns the visual code of this course object
     * 
     * @return String
     */
    public function get_visual_code()
    {
        return $this->get_default_property(self :: PROPERTY_VISUAL_CODE);
    }

    /**
     * Returns the title of this course object
     * 
     * @return String
     */
    public function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    /**
     * Returns the creation date of this course object
     * 
     * @return int
     */
    public function get_creation_date()
    {
        return $this->get_default_property(self :: PROPERTY_CREATION_DATE);
    }

    /**
     * Returns the expiration date of this course
     * 
     * @return int
     */
    public function get_expiration_date()
    {
        return $this->get_default_property(self :: PROPERTY_EXPIRATION_DATE);
    }

    /**
     * Returns the last modification date of this course
     * 
     * @return int
     */
    public function get_last_edit()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_EDIT);
    }

    /**
     * Returns the last visit date of this course
     * 
     * @return int
     */
    public function get_last_visit()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_VISIT);
    }

    /**
     * Sets the course type id of this course object
     * 
     * @param int $course_type_id
     */
    public function set_course_type_id($course_type_id)
    {
        $old_course_type_id = $this->get_course_type_id();
        
        if ($course_type_id == $old_course_type_id)
        {
            return;
        }
        
        $this->old_course_type_id = $old_course_type_id;
        
        $this->set_default_property(self :: PROPERTY_COURSE_TYPE_ID, $course_type_id);
    }

    /**
     * Sets the titular id of this course object
     * 
     * @param int $titular_id
     */
    public function set_titular_id($titular_id)
    {
        $this->set_default_property(self :: PROPERTY_TITULAR_ID, $titular_id);
    }

    /**
     * Sets the visual code of this course object
     * 
     * @param String $visual_code
     */
    public function set_visual_code($visual_code)
    {
        $this->set_default_property(self :: PROPERTY_VISUAL_CODE, $visual_code);
    }

    /**
     * Sets the course title of this course object
     * 
     * @param String $title
     */
    public function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    /**
     * Sets the creation date of this course
     * 
     * @param int $creation_date
     */
    public function set_creation_date($creation_date)
    {
        $this->set_default_property(self :: PROPERTY_CREATION_DATE, $creation_date);
    }

    /**
     * Sets the expiration date of this course
     * 
     * @param int $expiration_date
     */
    public function set_expiration_date($expiration_date)
    {
        $this->set_default_property(self :: PROPERTY_EXPIRATION_DATE, $expiration_date);
    }

    /**
     * Sets the last edit date of this course
     * 
     * @param int $last_edit
     */
    public function set_last_edit($last_edit)
    {
        $this->set_default_property(self :: PROPERTY_LAST_EDIT, $last_edit);
    }

    /**
     * Sets the last visit date of this course
     * 
     * @param int $last_visit
     */
    public function set_last_visit($last_visit)
    {
        $this->set_default_property(self :: PROPERTY_LAST_VISIT, $last_visit);
    }

    /**
     * Sets the language of this course object
     * 
     * @param String $language
     */
    public function set_language($language)
    {
        $this->set_default_property(self :: PROPERTY_LANGUAGE, $language);
    }

    /**
     * gets the language of this course object
     * 
     * @return String $language
     */
    public function get_language()
    {
        return $this->get_default_property(self :: PROPERTY_LANGUAGE);
    }

    /**
     * Sets the category_id of this course object
     * 
     * @param int $category_id
     */
    public function set_category_id($category_id)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY_ID, $category_id);
    }

    /**
     * gets the category_id of this course object
     * 
     * @return String $language
     */
    public function get_category_id()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY_ID);
    }

    /**
     * **************************************************************************************************************
     * Additional Properties Setters / Getters *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the title for the course type foreign object
     * 
     * @return String
     */
    public function get_course_type_title()
    {
        $course_type_title = $this->get_optional_property(self :: PROPERTY_COURSE_TYPE_TITLE);
        if (! $course_type_title)
        {
            $course_type = $this->get_course_type();
            return ($course_type ? $course_type->get_title() : Translation :: get('NoCourseType'));
        }
        
        return $course_type_title;
    }

    /**
     * **************************************************************************************************************
     * Foreign Properties Setters / Getters *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the course type of this course object (lazy loading)
     * 
     * @return \application\Avilarts\course_type\CourseType
     */
    public function get_course_type()
    {
        if ($this->get_course_type_id() == 0)
        {
            return null;
        }
        
        return DataManager :: retrieve_by_id(CourseType :: class_name(), $this->get_course_type_id());
    }

    /**
     * Sets the course type of this course object
     * 
     * @param \application\Avilarts\course_type\CourseType $course_type
     */
    public function set_course_type(\Ehb\Application\Avilarts\CourseType\Storage\DataClass\CourseType $course_type)
    {
        if (! is_null($course_type))
        {
            $old_course_type_id = $this->get_course_type_id();
            
            if ($course_type->get_id() != $old_course_type_id)
            {
                $this->old_course_type_id = $old_course_type_id;
            }
        }
        
        $this->set_foreign_property(self :: FOREIGN_PROPERTY_COURSE_TYPE, $course_type);
    }
}
