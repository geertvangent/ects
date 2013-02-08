<?php
namespace application\discovery\module\course\implementation\bamaflex;

use common\libraries\StringUtilities;
use application\discovery\SortableTable;
use common\libraries\PropertiesTable;
use common\libraries\ToolbarItem;
use application\discovery\LegendTable;
use common\libraries\Theme;
use common\libraries\DynamicContentTab;
use common\libraries\Translation;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Display;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    function render()
    {
        $html = array();
        $course = $this->get_course();

        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_year()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_faculty()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_training()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_name()));

        if (! $course instanceof Course)
        {
            return Display :: error_message(Translation :: get('NoSuchCourse'), true);
        }

        $tabs = new DynamicTabsRenderer('course');
        $tabs->add_tab(
                new DynamicContentTab(Module :: TAB_GENERAL, Translation :: get('General'),
                        Theme :: get_image_path() . 'tabs/' . Module :: TAB_GENERAL . '.png', $this->get_general()));
        if ($course->has_materials())
        {
            $tabs->add_tab(
                    new DynamicContentTab(Module :: TAB_MATERIALS, Translation :: get('Materials'),
                            Theme :: get_image_path() . 'tabs/' . Module :: TAB_MATERIALS . '.png',
                            $this->get_materials()));
        }
        if ($course->has_activities())
        {
            $tabs->add_tab(
                    new DynamicContentTab(Module :: TAB_ACTIVITIES, Translation :: get('Activities'),
                            Theme :: get_image_path() . 'tabs/' . Module :: TAB_ACTIVITIES . '.png',
                            $this->get_activities()));
        }
        if ($course->has_competences())
        {
            $tabs->add_tab(
                    new DynamicContentTab(Module :: TAB_COMPETENCES, Translation :: get('Competences'),
                            Theme :: get_image_path() . 'tabs/' . Module :: TAB_COMPETENCES . '.png',
                            $this->get_competences()));
        }
        if ($course->has_content())
        {
            $tabs->add_tab(
                    new DynamicContentTab(Module :: TAB_CONTENT, Translation :: get('Content'),
                            Theme :: get_image_path() . 'tabs/' . Module :: TAB_CONTENT . '.png', $this->get_content()));
        }

        $tabs->add_tab(
                new DynamicContentTab(Module :: TAB_EVALUATIONS, Translation :: get('Evaluations'),
                        Theme :: get_image_path() . 'tabs/' . Module :: TAB_EVALUATIONS . '.png',
                        $this->get_evaluations()));

        $html[] = $tabs->render();
        return implode("\n", $html);
    }

    function get_general()
    {
        $data_source = $this->get_module_instance()->get_setting('data_source');

        $faculty_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\faculty_info\implementation\bamaflex',
                array('data_source' => $data_source));

        $training_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\training_info\implementation\bamaflex',
                array('data_source' => $data_source));

        $teaching_assignment_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\teaching_assignment\implementation\bamaflex',
                array('data_source' => $data_source));

        $course = $this->get_course();
        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $course->get_year();

        $history = array();
        $courses = $course->get_all($this->get_module_instance());

        foreach ($courses as $course_history)
        {
            $parameters = new Parameters($course_history->get_id(), $course_history->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $history[] = '<a href="' . $link . '">' . $course_history->get_year() . '</a>';
        }
        $properties[Translation :: get('History')] = implode('  |  ', $history);

        if ($training_info_module_instance)
        {
            $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters(
                    $course->get_training_id(), $course->get_source());
            $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Training')] = '<a href="' . $url . '">' . $course->get_training() . '</a>';
        }
        else
        {
            $properties[Translation :: get('Training')] = $course->get_training();
        }

        if ($faculty_info_module_instance)
        {
            $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                    $course->get_faculty_id(), $course->get_source());
            $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Faculty')] = '<a href="' . $url . '">' . $course->get_faculty() . '</a>';
        }
        else
        {
            $properties[Translation :: get('Faculty')] = $course->get_faculty();
        }

        $properties[Translation :: get('Credits')] = $course->get_credits();
        $properties[Translation :: get('Weight')] = $course->get_weight();
        $properties[Translation :: get('TrajectoryPart')] = $course->get_trajectory_part();
        $properties[Translation :: get('ProgrammeType')] = Translation :: get($course->get_programme_type_string());

        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $programme_parts = array();

            foreach ($course->get_children() as $child)
            {
                $parameters = new Parameters($child->get_id(), $child->get_source());
                $child_url = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                $link = '<a href="' . $child_url . '">' . $child->get_name() . '</a>';
                $programme_parts[] = Translation :: get('CreditAmount',
                        array('COURSE' => $link, 'CREDITS' => $child->get_credits()));
            }
            $properties[Translation :: get('ProgrammeParts')] = implode('<br/>', $programme_parts);
        }

        if ($course->get_programme_type() != Course :: PROGRAMME_TYPE_COMPLEX)
        {
            if (! is_null($course->get_timeframe_visual_id()))
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/timeframe/' . $course->get_timeframe_visual_id() . '.png" alt="' . Translation :: get(
                        $course->get_timeframe()) . '" title="' . Translation :: get($course->get_timeframe()) . '"/>';
                $properties[Translation :: get('Timeframe')] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get($course->get_timeframe()),
                        Translation :: get('Timeframe'));

                $properties[Translation :: get('TimeframeParts')] = $course->get_timeframe_parts_string();
            }
        }

        if ($course->get_level())
        {
            $properties[Translation :: get('Level')] = $course->get_level();
        }
        if ($course->get_kind())
        {
            $properties[Translation :: get('Kind')] = $course->get_kind();
        }
        if ($course->has_languages())
        {
            $properties[Translation :: get('Languages')] = $course->get_languages_string();
        }

        if ($course->has_coordinators())
        {
            if ($teaching_assignment_module_instance)
            {
                $coordinators = array();
                foreach ($course->get_teachers() as $coordinator)
                {

                    if ($coordinator->is_coordinator())
                    {
                        $user = \user\DataManager :: retrieve_user_by_official_code($coordinator->get_person_id());
                        if ($user)
                        {
                            $parameters = new \application\discovery\module\teaching_assignment\Parameters(
                                    $user->get_id());
                            $url = $this->get_instance_url($teaching_assignment_module_instance->get_id(), $parameters);

                            $coordinators[] = '<a href="' . $url . '">' . $coordinator . '</a>';
                        }
                        else
                        {
                            $coordinators[] = $coordinator;
                        }
                    }
                }
                $properties[Translation :: get('Coordinators')] = implode(', ', $coordinators);
            }
            else
            {
                $properties[Translation :: get('Coordinators')] = $course->get_coordinators_string();
            }
        }

        if ($course->has_teachers())
        {
            if ($teaching_assignment_module_instance)
            {
                $teachers = array();
                foreach ($course->get_teachers() as $teacher)
                {
                    if (! $teacher->is_coordinator())
                    {
                        $user = \user\DataManager :: retrieve_user_by_official_code($teacher->get_person_id());
                        if ($user)
                        {
                            $parameters = new \application\discovery\module\teaching_assignment\Parameters(
                                    $user->get_id());
                            $url = $this->get_instance_url($teaching_assignment_module_instance->get_id(), $parameters);

                            $teachers[] = '<a href="' . $url . '">' . $teacher . '</a>';
                        }
                        else
                        {
                            $teachers[] = $teacher;
                        }
                    }
                }
                $properties[Translation :: get('Teachers')] = implode(', ', $teachers);
            }
            else
            {
                $properties[Translation :: get('Teachers')] = $course->get_teachers_string();
            }
        }

        foreach ($course->get_costs() as $cost)
        {
            $properties[Translation :: get($cost->get_type_string())] = $cost->get_price_string();
        }

        $images = array();
        $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/degree.png" alt="' . Translation :: get(
                'DegreePossible') . '" title="' . Translation :: get('DegreePossible') . '"/>';
        LegendTable :: get_instance()->add_symbol($image, Translation :: get('DegreePossible'),
                Translation :: get('FollowingPossible'));
        $images[] = $image;
        if (! is_null($course->get_following_impossible()->get_credit()))
        {
            if ($course->get_following_impossible()->get_credit())
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/credit_impossible.png" alt="' . Translation :: get(
                        'CreditImpossible') . '" title="' . Translation :: get('CreditImpossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('CreditImpossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/credit.png" alt="' . Translation :: get(
                        'CreditPossible') . '" title="' . Translation :: get('CreditPossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('CreditPossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
        }

        if (! is_null($course->get_following_impossible()->get_exam_degree()))
        {
            if ($course->get_following_impossible()->get_exam_degree())
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_degree_impossible.png" alt="' . Translation :: get(
                        'ExamDegreeImpossible') . '" title="' . Translation :: get('ExamDegreeImpossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamDegreeImpossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_degree.png" alt="' . Translation :: get(
                        'ExamDegreePossible') . '" title="' . Translation :: get('ExamDegreePossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamDegreePossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
        }

        if (! is_null($course->get_following_impossible()->get_exam_credit()))
        {
            if ($course->get_following_impossible()->get_exam_credit())
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_credit_impossible.png" alt="' . Translation :: get(
                        'ExamCreditImpossible') . '" title="' . Translation :: get('ExamCreditImpossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamCreditImpossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme :: get_image_path() . 'general/following_impossible/exam_credit.png" alt="' . Translation :: get(
                        'ExamCreditPossible') . '" title="' . Translation :: get('ExamCreditPossible') . '"/>';
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('ExamCreditPossible'),
                        Translation :: get('FollowingPossible'));
                $images[] = $image;
            }
        }

        $properties[Translation :: get('FollowingPossible')] = implode(' ', $images);

        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\photo\implementation\bamaflex', array('data_source' => $data_source));

        if ($photo_module_instance)
        {
            $buttons = array();
            // students
            $parameters = new \application\discovery\module\photo\Parameters();
            $parameters->set_programme_id($course->get_id());
            $parameters->set_type(\application\discovery\module\photo\Module :: TYPE_STUDENT);

            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
            $image = Theme :: get_image('type/2', 'png',
                    Translation :: get('Students', null, 'application\discovery\module\photo'), $url,
                    ToolbarItem :: DISPLAY_ICON, false, 'application\discovery\module\photo');
            $buttons[] = $image;
            LegendTable :: get_instance()->add_symbol($image,
                    Translation :: get('Students', null, 'application\discovery\module\photo
                    '),
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));

            // teachers
            $parameters = new \application\discovery\module\photo\Parameters();
            $parameters->set_programme_id($course->get_id());
            $parameters->set_type(\application\discovery\module\photo\Module :: TYPE_TEACHER);

            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);

            $image = Theme :: get_image('type/1', 'png',
                    Translation :: get('Teachers', null, 'application\discovery\module\photo
                    '), $url,
                    ToolbarItem :: DISPLAY_ICON, false, 'application\discovery\module\photo');
            $buttons[] = $image;
            LegendTable :: get_instance()->add_symbol($image,
                    Translation :: get('Teachers', null, 'application\discovery\module\photo
                    '),
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));

            $properties[Translation :: get('Photos')] = implode("\n", $buttons);
        }

        $course_result_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\course_results\implementation\bamaflex',
                array('data_source' => $data_source));
        if ($course_result_module_instance)
        {
            $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                    $course->get_id(), $course->get_source());
            $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
            $properties[Translation :: get('TypeName', null,
                    'application\discovery\module\course_results\implementation\bamaflex')] = Theme :: get_image(
                    'logo/16', 'png',
                    Translation :: get('TypeName', null,
                            'application\discovery\module\course_results\implementation\bamaflex'), $url,
                    ToolbarItem :: DISPLAY_ICON, false,
                    'application\discovery\module\course_results\implementation\bamaflex');
        }

        $table = new PropertiesTable($properties);

        $html[] = $table->toHtml();
        return implode("\n", $html);
    }

    function get_materials()
    {
        $course = $this->get_course();
        $tabs = new DynamicTabsRenderer('course_materials');

        if ($course->has_materials(Material :: TYPE_REQUIRED))
        {
            $tabs->add_tab(
                    new DynamicContentTab(Material :: TYPE_REQUIRED, Translation :: get('Required'), null,
                            $this->get_materials_by_type(Material :: TYPE_REQUIRED)));
        }
        if ($course->has_materials(Material :: TYPE_OPTIONAL))
        {
            $tabs->add_tab(
                    new DynamicContentTab(Material :: TYPE_OPTIONAL, Translation :: get('Optional'), null,
                            $this->get_materials_by_type(Material :: TYPE_OPTIONAL)));
        }

        return $tabs->render();
    }

    function get_materials_by_type($type)
    {
        $course = $this->get_course();

        $html = array();
        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_materials_' . $type);
            if ($course->has_materials($type, false))
            {
                $tabs->add_tab(
                        new DynamicContentTab($course->get_id(), Translation :: get('General'), null,
                                $this->get_course_materials_by_type($course, $type)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_materials($type, false))
                {
                    $tabs->add_tab(
                            new DynamicContentTab($child->get_id(), $child->get_name(), null,
                                    $this->get_course_materials_by_type($child, $type)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_materials_by_type($course, $type);
        }
        return implode("\n", $html);
    }

    function get_course_materials_by_type($course, $type)
    {
        $html = array();
        $table_data = array();

        // if (count($course->get_materials_by_type($type)) > 0)
        // {
        // $var = ($type == Material :: TYPE_REQUIRED ? 'Required' :
        // 'Optional');
        // $html[] = '<h3>' . Translation :: get($var) . '</h3>';
        // }
        foreach ($course->get_materials_by_type($type) as $material)
        {
            if ($material instanceof MaterialDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $material->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $material->get_group();
                $table_row[] = $material->get_title();
                $table_row[] = $material->get_edition();
                $table_row[] = $material->get_author();
                $table_row[] = $material->get_editor();
                $table_row[] = $material->get_isbn();
                $table_row[] = $material->get_medium();
                $table_row[] = $material->get_description();
                if ($material->get_price())
                {
                    $table_row[] = $material->get_price_string();
                }
                else
                {
                    $table_row[] = '';
                }

                if ($material->get_for_sale())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/for_sale.png" alt="' . Translation :: get(
                            'IsForSale') . '" title="' . Translation :: get('IsForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsForSale'),
                            Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/not_for_sale.png" alt="' . Translation :: get(
                            'IsNotForSale') . '" title="' . Translation :: get('IsNotForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotForSale'),
                            Translation :: get('ForSale'));
                    $table_row[] = $image;
                }

                $table_data[] = $table_row;
            }
        }

        $cost = $course->get_costs_by_type(Cost :: TYPE_MATERIAL);

        if (count($table_data) > 0 || ($cost && $type == Material :: TYPE_REQUIRED && $cost->get_price()))
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Group'), false);
            $table->set_header(1, Translation :: get('Title'), false);
            $table->set_header(2, Translation :: get('Edition'), false);
            $table->set_header(3, Translation :: get('Author'), false);
            $table->set_header(4, Translation :: get('Editor'), false);
            $table->set_header(5, Translation :: get('Isbn'), false);
            $table->set_header(6, Translation :: get('Medium'), false);
            $table->set_header(7, Translation :: get('Remarks'), false);
            $table->set_header(8, Translation :: get('Price'), false);
            $table->set_header(9, '', false);

            $html[] = $table->as_html($cost->get_price_string(), 8);
        }

        return implode("\n", $html);
    }

    function get_activities()
    {
        $course = $this->get_course();

        $html = array();

        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_activities');
            if ($course->has_activities(false))
            {
                $tabs->add_tab(
                        new DynamicContentTab($course->get_id(), Translation :: get('General'), null,
                                $this->get_course_activities($course)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_activities(false))
                {
                    $tabs->add_tab(
                            new DynamicContentTab($child->get_id(), $child->get_name(), null,
                                    $this->get_course_activities($child)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_activities($course);
        }

        return implode("\n", $html);
    }

    function get_course_activities($course)
    {
        $html = array();
        $table_data = array();

        foreach ($course->get_activities() as $activity)
        {
            if ($activity instanceof ActivityDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $activity->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            elseif ($activity instanceof ActivityStructured)
            {

                $table_row = array();
                $table_row[] = $activity->get_group();
                $table_row[] = $activity->get_name();
                $table_row[] = Translation :: get('ActivityTime', array('TIME' => $activity->get_time()));
                // $table_row[] = $activity->get_remarks();
                // $table_row[] = $activity->get_description();

                $table_data[] = $table_row;
            }
        }
        $total = $course->get_activities_by_type(ActivityTotal :: CLASS_NAME);

        if (count($table_data) > 0 || $total)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Group'), false);
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Time'), false);
            // $table->set_header(3, Translation :: get('Remarks'), false);
            // $table->set_header(4, Translation :: get('Description'), false);

            $total = $course->get_activities_by_type(ActivityTotal :: CLASS_NAME);
            if ($total)
            {
                $html[] = $table->as_html(Translation :: get('ActivityTime', array('TIME' => $total->get_time())), 2);
            }
            else
            {
                $html[] = $table->as_html();
            }
        }

        return implode("\n", $html);
    }

    function get_competences()
    {
        $course = $this->get_course();
        $tabs = new DynamicTabsRenderer('competences');
        if (count($course->get_competences_by_type(Competence :: TYPE_BEGIN)) > 0)
        {
            $tabs->add_tab(
                    new DynamicContentTab(Competence :: TYPE_BEGIN, Translation :: get('BeginCompetence'),
                            Theme :: get_image_path() . 'competence/tabs/' . Competence :: TYPE_BEGIN . '.png',
                            $this->get_competences_by_type(Competence :: TYPE_BEGIN)));
        }
        if (count($course->get_competences_by_type(Competence :: TYPE_END)) > 0)
        {
            $tabs->add_tab(
                    new DynamicContentTab(Competence :: TYPE_END, Translation :: get('EndCompetence'),
                            Theme :: get_image_path() . 'competence/tabs/' . Competence :: TYPE_END . '.png',
                            $this->get_competences_by_type(Competence :: TYPE_END)));
        }
        return $tabs->render();
    }

    function get_competences_by_type($type)
    {
        $course = $this->get_course();
        $html = array();

        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_competences_' . $type);
            if ($course->has_competences($type, false))
            {
                $tabs->add_tab(
                        new DynamicContentTab($course->get_id(), Translation :: get('General'), null,
                                $this->get_course_competences_by_type($course, $type)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_competences($type, false))
                {
                    $tabs->add_tab(
                            new DynamicContentTab($child->get_id(), $child->get_name(), null,
                                    $this->get_course_competences_by_type($child, $type)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_competences_by_type($course, $type);
        }

        return implode("\n", $html);
    }

    function get_course_competences_by_type($course, $type)
    {
        $table_data = array();

        foreach ($course->get_competences_by_type($type) as $competence)
        {
            if ($competence instanceof CompetenceDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $competence->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $competence->get_code();
                // $table_row[] = $competence->get_summary();
                $table_row[] = $competence->get_description();
                $table_row[] = $competence->get_level();

                $table_data[] = $table_row;
            }
        }

        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Code'), false);
            // $table->set_header(1, Translation :: get('Summary'), false);
            $table->set_header(1, Translation :: get('Description'), false);
            $table->set_header(2, Translation :: get('Level'), false);
            $html[] = $table->as_html();
        }

        return implode("\n", $html);
    }

    function get_content()
    {
        $html = array();
        $course = $this->get_course();

        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_contents');
            if ($course->has_content(false))
            {
                $tabs->add_tab(
                        new DynamicContentTab($course->get_id(), Translation :: get('General'), null,
                                $this->get_course_contents($course)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_content(false))
                {
                    $tabs->add_tab(
                            new DynamicContentTab($child->get_id(), $child->get_name(), null,
                                    $this->get_course_contents($child)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_contents($course);
        }

        return implode("\n", $html);
    }

    function get_course_contents($course)
    {
        $html = array();

        if (! StringUtilities :: is_null_or_empty($course->get_goals(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(
                    __NAMESPACE__) . 'content/goals.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Goals');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_goals();
            $html[] = '</div>';
            $html[] = '</div>';
        }

        if (! StringUtilities :: is_null_or_empty($course->get_contents(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(
                    __NAMESPACE__) . 'content/contents.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Contents');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_contents();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities :: is_null_or_empty($course->get_coaching(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(
                    __NAMESPACE__) . 'content/coaching.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Coaching');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_coaching();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities :: is_null_or_empty($course->get_succession(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(
                    __NAMESPACE__) . 'content/succession.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Succession');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_succession();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        return implode("\n", $html);
    }

    function get_evaluations()
    {
        $html = array();
        $course = $this->get_course();

        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_evaluations');

            $tabs->add_tab(
                    new DynamicContentTab($course->get_id(), Translation :: get('General'), null,
                            $this->get_course_evaluations($course)));

            foreach ($course->get_children() as $child)
            {
                $tabs->add_tab(
                        new DynamicContentTab($child->get_id(), $child->get_name(), null,
                                $this->get_course_evaluations($child)));
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_evaluations($course);
        }

        return implode("\n", $html);
    }

    function get_course_evaluations($course)
    {
        $html = array();
        $table_data = array();

        $properties = array();
        $properties[Translation :: get('Credits')] = $course->get_credits();
        $properties[Translation :: get('Weight')] = $course->get_weight();
        if (($course->get_programme_type() == Course :: PROGRAMME_TYPE_SIMPLE || $course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX) && ! is_null(
                $course->get_deliberation()))
        {
            $properties[Translation :: get('Deliberation')] = Translation :: get('DeliberationInfo',
                    array('DELIBERATION' => $course->get_deliberation()));
        }
        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_SIMPLE || $course->get_programme_type() == Course :: PROGRAMME_TYPE_PART)
        {
            $properties[Translation :: get('Quotation')] = Translation :: get($course->get_result_scale_string());
            $second_chance = array();
            if ($course->get_second_chance()->get_exam())
            {
                $exam_image = '<img src="' . Theme :: get_image_path() . 'evaluation/second_chance/exam_allowed.png" alt="' . Translation :: get(
                        'SecondChanceExamAllowed') . '" title="' . Translation :: get('SecondChanceExamAllowed') . '"/>';
                LegendTable :: get_instance()->add_symbol($exam_image, Translation :: get('SecondChanceExamAllowed'),
                        Translation :: get('SecondChanceExam'));
            }
            else
            {
                $exam_image = '<img src="' . Theme :: get_image_path() . 'evaluation/second_chance/exam_not_allowed.png" alt="' . Translation :: get(
                        'SecondChanceExamNotAllowed') . '" title="' . Translation :: get('SecondChanceExamNotAllowed') . '"/>';
                LegendTable :: get_instance()->add_symbol($exam_image, Translation :: get('SecondChanceExamNotAllowed'),
                        Translation :: get('SecondChanceExam'));
            }
            $second_chance[] = $exam_image;

            if (! is_null($course->get_second_chance()->get_enrollment()))
            {
                if ($course->get_second_chance()->get_enrollment())
                {
                    $enrollment_image = '<img src="' . Theme :: get_image_path() . 'evaluation/second_chance/enrollment_allowed.png" alt="' . Translation :: get(
                            'SecondChanceEnrollmentAllowed') . '" title="' . Translation :: get(
                            'SecondChanceEnrollmentAllowed') . '"/>';
                    LegendTable :: get_instance()->add_symbol($enrollment_image,
                            Translation :: get('SecondChanceEnrollmentAllowed'),
                            Translation :: get('SecondChanceEnrollment'));
                }
                else
                {
                    $enrollment_image = '<img src="' . Theme :: get_image_path() . 'evaluation/second_chance/enrollment_not_allowed.png" alt="' . Translation :: get(
                            'SecondChanceEnrollmentNotAllowed') . '" title="' . Translation :: get(
                            'SecondChanceEnrollmentNotAllowed') . '"/>';
                    LegendTable :: get_instance()->add_symbol($enrollment_image,
                            Translation :: get('SecondChanceEnrollmentNotAllowed'),
                            Translation :: get('SecondChanceEnrollment'));
                }
                $second_chance[] = $enrollment_image;
            }

            $properties[Translation :: get('SecondChance')] = implode(' ', $second_chance);
        }
        elseif ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            if (! is_null($course->get_score_calculation()))
            {
                $properties[Translation :: get('ScoreCalculation')] = Translation :: get(
                        $course->get_score_calculation_string());
            }
            $quotation_parts = array();

            foreach ($course->get_children() as $child)
            {
                $parameters = new Parameters($child->get_id(), $child->get_source());
                $child_url = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                $link = '<a href="' . $child_url . '">' . $child->get_name() . '</a>';

                $quotation_parts[] = Translation :: get('QuotationParts',
                        array('COURSE' => $link,
                                'RESULT_SCALE' => Translation :: get($child->get_result_scale_string())));
            }
            $properties[Translation :: get('Quotation')] = implode('<br/>', $quotation_parts);

            if (! is_null($course->get_second_chance()->get_exam_parts()))
            {
                $exam_parts_image = '<img src="' . Theme :: get_image_path() . 'evaluation/second_chance/exam_parts/' . $course->get_second_chance()->get_exam_parts() . '.png" alt="' . Translation :: get(
                        $course->get_second_chance()->get_exam_parts_string()) . '" title="' . Translation :: get(
                        $course->get_second_chance()->get_exam_parts_string()) . '"/>';
                LegendTable :: get_instance()->add_symbol($exam_parts_image,
                        Translation :: get($course->get_second_chance()->get_exam_parts_string()),
                        Translation :: get('SecondChance'));

                $properties[Translation :: get('SecondChance')] = $exam_parts_image;
            }
        }

        $table = new PropertiesTable($properties);

        $html[] = $table->toHtml();
        if ($course->has_evaluations())
        {
            $html[] = '<br/>';
        }
        foreach ($course->get_evaluations() as $evaluation)
        {
            if ($evaluation instanceof EvaluationDescription)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $evaluation->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            else
            {
                $table_row = array();
                $table_row[] = $evaluation->get_try();
                $table_row[] = $evaluation->get_moment();
                $table_row[] = $evaluation->get_type();
                $table_row[] = $evaluation->get_percentage();
                $table_row[] = $evaluation->get_description();

                if ($evaluation->get_permanent())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'evaluation/permanent.png" alt="' . Translation :: get(
                            'IsPermanent') . '" title="' . Translation :: get('IsPermanent') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsPermanent'),
                            Translation :: get('Permanent'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'evaluation/not_permanent.png" alt="' . Translation :: get(
                            'IsNotPermanent') . '" title="' . Translation :: get('IsNotPermanent') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotPermanent'),
                            Translation :: get('Permanent'));
                    $table_row[] = $image;
                }

                $table_data[] = $table_row;
            }
        }

        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Try'), false);
            $table->set_header(1, Translation :: get('Moment'), false);
            $table->set_header(2, Translation :: get('Type'), false);
            $table->set_header(3, Translation :: get('Percentage'), false);
            $table->set_header(4, Translation :: get('Remarks'), false);
            $table->set_header(5, Translation :: get('Permanent'), false);
            $html[] = $table->as_html();
        }

        return implode("\n", $html);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
