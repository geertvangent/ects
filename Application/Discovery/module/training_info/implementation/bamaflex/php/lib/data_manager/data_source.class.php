<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use common\libraries\AndCondition;
use common\libraries\Utilities;
use common\libraries\EqualityCondition;
use application\discovery\data_source\bamaflex\HistoryReference;
use application\discovery\data_source\bamaflex\History;
use application\discovery\module\course\implementation\bamaflex\Course;
use application\discovery\module\training\implementation\bamaflex\Training;
use application\discovery\module\training_info\DataManagerInterface;
use application\discovery\data_source\bamaflex\DiscoveryDataManager;
use MDB2_Error;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $trainings;

    private $majors;

    private $languages;

    private $packages;

    private $package_courses;

    private $choices;

    private $choice_options;

    private $trajectories;

    private $sub_trajectories;

    private $sub_trajectory_courses;

    private $sub_trajectory_courses_children;

    private $major_choices;

    private $major_choice_options;

    private $package_courses_children;

    private $groups;

    private $courses;

    private $courses_children;

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\training_info\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_training($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();

        if (! isset($this->trainings[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_advanced WHERE id = "' . $training_id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $training = new Training();
                    $training->set_source($result->source);
                    $training->set_id($result->id);
                    $training->set_name($this->convert_to_utf8($result->name));
                    $training->set_year($this->convert_to_utf8($result->year));
                    $training->set_credits($result->credits);
                    $training->set_domain_id($result->domain_id);
                    $training->set_domain($this->convert_to_utf8($result->domain));
                    $training->set_goals(nl2br($this->convert_to_utf8($result->goals)));
                    $training->set_type_id($result->type_id);
                    $training->set_type($this->convert_to_utf8($result->type));
                    $training->set_bama_type($result->bama_type);
                    $training->set_faculty_id($result->faculty_id);
                    $training->set_faculty($this->convert_to_utf8($result->faculty));
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);
                    $training->set_languages($this->retrieve_languages($training_parameters));
                    if ($training_parameters->get_tab() == Module :: TAB_OPTIONS)
                    {
                        $training->set_choices($this->retrieve_choices($training_parameters));
                        $training->set_choice_options($this->retrieve_choice_options($training_parameters));
                        $training->set_majors($this->retrieve_majors($training_parameters));
                        $training->set_packages($this->retrieve_packages($training_parameters));
                    }

                    if ($training_parameters->get_tab() == Module :: TAB_TRAJECTORIES)
                    {
                        $training->set_trajectories($this->retrieve_trajectories($training_parameters));
                    }
                    $training->set_groups($this->retrieve_groups($training_parameters));

                    if ($training_parameters->get_tab() == Module :: TAB_COURSES)
                    {
                        $training->set_courses($this->retrieve_courses($training_parameters));
                    }

                    $conditions = array();
                    $conditions[] = new EqualityCondition(History :: PROPERTY_HISTORY_ID, $training->get_id());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_HISTORY_SOURCE, $training->get_source());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_TYPE,
                            Utilities :: get_namespace_from_object($training));
                    $condition = new AndCondition($conditions);

                    $histories = DiscoveryDataManager :: get_instance()->retrieve_history_by_conditions($condition);

                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_previous_id());
                            $reference->set_source($history->get_previous_source());
                            $training->add_previous_reference($reference);
                        }
                    }
                    else
                    {
                        if ($result->previous_id)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($result->previous_id);
                            $reference->set_source($result->previous_source);
                            $training->add_previous_reference($reference);
                        }
                    }

                    $conditions = array();
                    $conditions[] = new EqualityCondition(History :: PROPERTY_PREVIOUS_ID, $training->get_id());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_PREVIOUS_SOURCE, $training->get_source());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_TYPE,
                            Utilities :: get_namespace_from_object($training));
                    $condition = new AndCondition($conditions);

                    $histories = DiscoveryDataManager :: get_instance()->retrieve_history_by_conditions($condition);
                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_history_id());
                            $reference->set_source($history->get_history_source());
                            $training->add_next_reference($reference);
                        }
                    }
                    else
                    {
                        $next = $this->retrieve_training_next_id($training);

                        if ($next)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($next->id);
                            $reference->set_source($next->source);
                            $training->add_next_reference($reference);
                        }
                    }

                    $this->trainings[$training_id][$source] = $training;
                }
            }
        }

        return $this->trainings[$training_id][$source];
    }

    function retrieve_training_next_id($training)
    {
        $query = 'SELECT id, source FROM v_discovery_training_advanced WHERE previous_id = "' . $training->get_id() . '" AND source = "' . $training->get_source() . '"';
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        if (! $results instanceof MDB2_Error)
        {
            return $results->fetchRow(MDB2_FETCHMODE_OBJECT);
        }
        else
        {
            return false;
        }
    }

    function retrieve_majors($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();

        if (! isset($this->majors[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_major_basic ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $major = new Major();
                    $major->set_id($result->id);
                    $major->set_training_id($result->training_id);
                    $major->set_name($this->convert_to_utf8($result->name));
                    $major->set_source($result->source);
                    $major->set_choices($this->retrieve_major_choices($major->get_id(), $major->get_source()));
                    $major->set_choice_options(
                            $this->retrieve_major_choice_options($major->get_id(), $major->get_source()));

                    $this->majors[$training_id][$source][] = $major;
                }
            }
        }

        return $this->majors[$training_id][$source];
    }

    function retrieve_languages($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();

        if (! isset($this->languages[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_language_basic ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $language = new Language();
                    $language->set_id($result->id);
                    $language->set_training_id($result->training_id);
                    $language->set_name($this->convert_to_utf8($result->name));
                    $language->set_source($result->source);

                    $this->languages[$training_id][$source][] = $language;
                }
            }
        }

        return $this->languages[$training_id][$source];
    }

    function retrieve_packages($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();

        if (! isset($this->packages[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_package_advanced ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $package = new Package();
                    $package->set_id($result->id);
                    $package->set_training_id($result->training_id);
                    $package->set_name($this->convert_to_utf8($result->name));
                    $package->set_source($result->source);
                    $package->set_courses($this->retrieve_package_courses($package->get_id(), $package->get_source()));

                    $this->packages[$training_id][$source][] = $package;
                }
            }
        }

        return $this->packages[$training_id][$source];
    }

    function retrieve_courses($parameters)
    {
        $id = $parameters->get_training_id();
        $source = $parameters->get_source();

        if (! isset($this->courses[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_course_advanced ';
            $query .= 'WHERE training_id = "' . $id . '" AND source = "' . $source . '" ORDER BY name';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $course = new Course();
                    $course->set_id($result->id);
                    $course->set_parent_id($result->parent_id);
                    $course->set_training_id($result->training_id);
                    $course->set_name($this->convert_to_utf8($result->name));
                    $course->set_source($result->source);
                    $course->set_credits($result->credits);

                    $this->courses[$id][$source][$result->id] = $course;
                }
                foreach ($this->courses[$id][$source] as $course)
                {
                    if (! is_null($course->get_parent_id()))
                    {
                        $this->courses[$id][$source][$course->get_parent_id()]->add_child($course);
                    }
                }
            }
        }

        return $this->courses[$id][$source];
    }

    function retrieve_package_courses($id, $source)
    {
        if (! isset($this->package_courses[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_info_package_course_advanced ';
            $query .= 'WHERE package_id = "' . $id . '" AND source = "' . $source . '"';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $trajectory = new PackageCourse();
                    $trajectory->set_id($result->id);
                    $trajectory->set_parent_programme_id($result->parent_programme_id);
                    $trajectory->set_package_id($result->package_id);
                    $trajectory->set_name($this->convert_to_utf8($result->name));
                    $trajectory->set_source($result->source);
                    $trajectory->set_trajectory_part($result->trajectory_part);
                    $trajectory->set_credits($result->credits);
                    $trajectory->set_programme_id($result->programme_id);

                    $this->package_courses[$id][$source][$result->programme_id] = $trajectory;
                }
                foreach ($this->package_courses[$id][$source] as $package_course)
                {
                    if (! is_null($package_course->get_parent_programme_id()))
                    {
                        $this->package_courses[$id][$source][$package_course->get_parent_programme_id()]->add_child(
                                $package_course);
                    }
                }
            }
        }
        return $this->package_courses[$id][$source];
    }

    function retrieve_choices($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        if (! isset($this->choices[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_choice_advanced ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $choice = new Choice();
                    $choice->set_id($result->id);
                    $choice->set_training_id($result->training_id);
                    $choice->set_name($this->convert_to_utf8($result->name));
                    $choice->set_source($result->source);

                    $this->choices[$training_id][$source][] = $choice;
                }
            }
        }
        return $this->choices[$training_id][$source];
    }

    function retrieve_choice_options($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        if (! isset($this->choice_options[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_choice_option_advanced ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $choice_option = new ChoiceOption();
                    $choice_option->set_id($result->id);
                    $choice_option->set_training_id($result->training_id);
                    $choice_option->set_name($this->convert_to_utf8($result->name));
                    $choice_option->set_source($result->source);

                    $this->choice_options[$training_id][$source][] = $choice_option;
                }
            }
        }
        return $this->choice_options[$training_id][$source];
    }

    function retrieve_trajectories($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        if (! isset($this->trajectories[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_trajectory_advanced ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $trajectory = new Trajectory();
                    $trajectory->set_id($result->id);
                    $trajectory->set_training_id($result->training_id);
                    $trajectory->set_name($this->convert_to_utf8($result->name));
                    $trajectory->set_source($result->source);
                    $trajectory->set_trajectories(
                            $this->retrieve_sub_trajectories($trajectory->get_id(), $trajectory->get_source()));

                    $this->trajectories[$training_id][$source][] = $trajectory;
                }
            }
        }
        return $this->trajectories[$training_id][$source];
    }

    function retrieve_groups($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        if (! isset($this->groups[$training_id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_group_advanced ';
            $query .= 'WHERE training_id = "' . $training_id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $group = new Group();
                    $group->set_training_id($result->training_id);
                    $group->set_group_id($result->group_id);
                    $group->set_group($this->convert_to_utf8($result->group));
                    $group->set_source($result->source);

                    $this->groups[$training_id][$source][] = $group;
                }
            }
        }
        return $this->groups[$training_id][$source];
    }

    function retrieve_sub_trajectories($id, $source)
    {
        if (! isset($this->sub_trajectories[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_sub_trajectory_advanced ';
            $query .= 'WHERE trajectory_id = "' . $id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $trajectory = new SubTrajectory();
                    $trajectory->set_id($result->id);
                    $trajectory->set_trajectory_id($result->trajectory_id);
                    $trajectory->set_name($this->convert_to_utf8($result->name));
                    $trajectory->set_source($result->source);
                    $trajectory->set_courses(
                            $this->retrieve_sub_trajectory_courses($trajectory->get_id(), $trajectory->get_source()));

                    $this->sub_trajectories[$id][$source][] = $trajectory;
                }
            }
        }
        return $this->sub_trajectories[$id][$source];
    }

    function retrieve_sub_trajectory_courses($id, $source)
    {
        if (! isset($this->sub_trajectory_courses[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_info_sub_trajectory_course_advanced ';
            $query .= 'WHERE sub_trajectory_id = "' . $id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $trajectory = new SubTrajectoryCourse();
                    $trajectory->set_id($result->id);
                    $trajectory->set_parent_programme_id($result->parent_programme_id);
                    $trajectory->set_sub_trajectory_id($result->sub_trajectory_id);
                    $trajectory->set_name($this->convert_to_utf8($result->name));
                    $trajectory->set_source($result->source);
                    $trajectory->set_trajectory_part($result->trajectory_part);
                    $trajectory->set_credits($result->credits);
                    $trajectory->set_programme_id($result->programme_id);

                    $this->sub_trajectory_courses[$id][$source][$result->programme_id] = $trajectory;
                }
                foreach ($this->sub_trajectory_courses[$id][$source] as $sub_trajectory_course)
                {
                    if (! is_null($sub_trajectory_course->get_parent_programme_id()))
                    {
                        $this->sub_trajectory_courses[$id][$source][$sub_trajectory_course->get_parent_programme_id()]->add_child(
                                $sub_trajectory_course);
                    }
                }
            }
        }
        return $this->sub_trajectory_courses[$id][$source];
    }

    function retrieve_major_choices($id, $source)
    {
        if (! isset($this->major_choices[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_major_choice_basic ';
            $query .= 'WHERE major_id = "' . $id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $major_choice = new MajorChoice();
                    $major_choice->set_id($result->id);
                    $major_choice->set_major_id($result->major_id);
                    $major_choice->set_name($this->convert_to_utf8($result->name));
                    $major_choice->set_source($result->source);

                    $this->major_choices[$id][$source][] = $major_choice;
                }
            }
        }
        return $this->major_choices[$id][$source];
    }

    function retrieve_major_choice_options($id, $source)
    {
        if (! isset($this->major_choice_options[$id][$source]))
        {
            $query = 'SELECT * FROM v_discovery_training_major_choice_option_basic ';
            $query .= 'WHERE major_id = "' . $id . '" AND source = "' . $source . '"';
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $major_choice_option = new MajorChoiceOption();
                    $major_choice_option->set_id($result->id);
                    $major_choice_option->set_major_id($result->major_id);
                    $major_choice_option->set_name($this->convert_to_utf8($result->name));
                    $major_choice_option->set_source($result->source);

                    $this->major_choice_options[$id][$source][] = $major_choice_option;
                }
            }
        }
        return $this->major_choice_options[$id][$source];
    }
}
