<?php
namespace application\ehb_sync\bamaflex;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'DataManagerInterface' => '/lib/data_manager_interface.class.php',
         'BamaflexConnection' => '/lib/data_connector/bamaflex/connection.class.php',
         'BamaflexDataConnector' => '/lib/data_connector/bamaflex/connector.class.php',
         'BamaflexDatabase' => '/lib/data_connector/bamaflex/database.class.php',
         'BamaflexResultSet' => '/lib/data_connector/bamaflex/result_set.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'AllUsersComponent' => '/lib/manager/component/all_users.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CoursesComponent' => '/lib/manager/component/courses.class.php',
         'CourseCategoriesComponent' => '/lib/manager/component/course_categories.class.php',
         'GroupsComponent' => '/lib/manager/component/groups.class.php',
         'NewUsersComponent' => '/lib/manager/component/new_users.class.php',
         'UpdateUsersComponent' => '/lib/manager/component/update_users.class.php',
         'Synchronization' => '/lib/synchronization/synchronization.class.php',
         'CourseSynchronization' => '/lib/synchronization/type/course.class.php',
         'CourseCategorySynchronization' => '/lib/synchronization/type/course_category.class.php',
         'GroupSynchronization' => '/lib/synchronization/type/group.class.php',
         'UserSynchronization' => '/lib/synchronization/type/user.class.php',
         'AcademicYearCourseCategorySynchronization' => '/lib/synchronization/type/course_category/academic_year.class.php',
         'DepartmentCourseCategorySynchronization' => '/lib/synchronization/type/course_category/department.class.php',
         'DummyCourseCategorySynchronization' => '/lib/synchronization/type/course_category/dummy.class.php',
         'TrainingCourseCategorySynchronization' => '/lib/synchronization/type/course_category/training.class.php',
         'AcademicYearGroupSynchronization' => '/lib/synchronization/type/group/academic_year.class.php',
         'AcademicYearExtraGroupSynchronization' => '/lib/synchronization/type/group/academic_year_extra.class.php',
         'AcademicYearExtraGenerationGroupSynchronization' => '/lib/synchronization/type/group/academic_year_extra_generation.class.php',
         'AcademicYearExtraIntakeGroupSynchronization' => '/lib/synchronization/type/group/academic_year_extra_intake.class.php',
         'CentralAdministrationGroupSynchronization' => '/lib/synchronization/type/group/central_administration.class.php',
         'CourseGroupSynchronization' => '/lib/synchronization/type/group/course.class.php',
         'DepartmentGroupSynchronization' => '/lib/synchronization/type/group/department.class.php',
         'DummyGroupSynchronization' => '/lib/synchronization/type/group/dummy.class.php',
         'StudentCourseGroupSynchronization' => '/lib/synchronization/type/group/student_course.class.php',
         'StudentTrainingGroupSynchronization' => '/lib/synchronization/type/group/student_training.class.php',
         'StudentTrainingChoicesGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices.class.php',
         'StudentTrainingChoicesCombinationGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_combination.class.php',
         'StudentTrainingChoicesCombinationsGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_combinations.class.php',
         'StudentTrainingChoicesGraduationGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_graduation.class.php',
         'StudentTrainingChoicesGraduationsGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_graduations.class.php',
         'StudentTrainingChoicesOptionGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_option.class.php',
         'StudentTrainingChoicesOptionsGroupSynchronization' => '/lib/synchronization/type/group/student_training_choices_options.class.php',
         'StudentTrainingCoursesGroupSynchronization' => '/lib/synchronization/type/group/student_training_courses.class.php',
         'StudentTrainingTrajectoriesGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories.class.php',
         'StudentTrainingTrajectoriesIndividualGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_individual.class.php',
         'StudentTrainingTrajectoriesPersonalGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_personal.class.php',
         'StudentTrainingTrajectoriesTemplateGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_template.class.php',
         'StudentTrainingTrajectoriesTemplateMainGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_template_main.class.php',
         'StudentTrainingTrajectoriesTemplateSubGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_template_sub.class.php',
         'StudentTrainingTrajectoriesUnknownGroupSynchronization' => '/lib/synchronization/type/group/student_training_trajectories_unknown.class.php',
         'TeacherCourseGroupSynchronization' => '/lib/synchronization/type/group/teacher_course.class.php',
         'TeacherTrainingGroupSynchronization' => '/lib/synchronization/type/group/teacher_training.class.php',
         'TrainingGroupSynchronization' => '/lib/synchronization/type/group/training.class.php',
         'UserTypeEmployeeGroupSynchronization' => '/lib/synchronization/type/group/user_type_employee.class.php',
         'UserTypeGuestTeacherGroupSynchronization' => '/lib/synchronization/type/group/user_type_guest_teacher.class.php',
         'UserTypeStudentGroupSynchronization' => '/lib/synchronization/type/group/user_type_student.class.php',
         'UserTypeTeacherGroupSynchronization' => '/lib/synchronization/type/group/user_type_teacher.class.php',
         'AllUserSynchronization' => '/lib/synchronization/type/user/all.class.php',
         'CreateUserSynchronization' => '/lib/synchronization/type/user/create.class.php',
         'UpdateUserSynchronization' => '/lib/synchronization/type/user/update.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php'
    );

    /**
     * Try to load the class
     *
     * @param $classname string
     * @return boolean
     */
    static function load($classname)
    {
        if (isset(self :: $map[$classname]))
        {
            require_once __DIR__ . self :: $map[$classname];
            return true;
        }

        return false;
    }

    /**
     * Synchronize the autoloader
     *
     * @param $update boolean
     * @return multitype:string
     */
    static function synch($update)
    {
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>