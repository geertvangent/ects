<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Discovery\AccessAllowedInterface;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\ActivityDescription;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\ActivityStructured;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\ActivityTotal;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Competence;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\CompetenceDescription;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\CompetenceStructured;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Cost;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Course;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\EvaluationDescription;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Material;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\MaterialDescription;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        $application_is_allowed = $this->get_application() instanceof AccessAllowedInterface;
        
        if (! $application_is_allowed && ! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $html = array();
        $course = $this->get_course();
        
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $course->get_year()));
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $course->get_faculty()));
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $course->get_training()));
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $course->get_name()));
        
        if (! $course instanceof Course)
        {
            return Display::error_message(Translation::get('NoSuchCourse'), true);
        }
        
        $tabs = new DynamicTabsRenderer('course');
        $tabs->add_tab(
            new DynamicContentTab(
                Module::TAB_GENERAL, 
                Translation::get('General'), 
                Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_GENERAL . '.png', 
                $this->get_general()));
        if ($course->has_materials())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module::TAB_MATERIALS, 
                    Translation::get('Materials'), 
                    Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_MATERIALS . '.png', 
                    $this->get_materials()));
        }
        if ($course->has_activities())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module::TAB_ACTIVITIES, 
                    Translation::get('Activities'), 
                    Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_ACTIVITIES . '.png', 
                    $this->get_activities()));
        }
        if ($course->has_competences())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module::TAB_COMPETENCES, 
                    Translation::get('Competences'), 
                    Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_COMPETENCES . '.png', 
                    $this->get_competences()));
        }
        if ($course->has_content())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module::TAB_CONTENT, 
                    Translation::get('Content'), 
                    Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_CONTENT . '.png', 
                    $this->get_content()));
        }
        
        $tabs->add_tab(
            new DynamicContentTab(
                Module::TAB_EVALUATIONS, 
                Translation::get('Evaluations'), 
                Theme::getInstance()->getImagesPath() . 'Tabs/' . Module::TAB_EVALUATIONS . '.png', 
                $this->get_evaluations()));
        
        $html[] = $tabs->render();
        return implode(PHP_EOL, $html);
    }

    public function get_general()
    {
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $course_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $faculty_info_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $training_info_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $teaching_assignment_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $course = $this->get_course();
        $html = array();
        $properties = array();
        $properties[Translation::get('Year')] = $course->get_year();
        
        $history = array();
        $courses = $course->get_all($this->get_module_instance());
        
        foreach ($courses as $course_history)
        {
            $parameters = new Parameters($course_history->get_id(), $course_history->get_source());
            
            $is_allowed = \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $course_module_instance->get_id(), 
                $parameters);
            
            if ($is_allowed)
            {
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                $history[] = '<a href="' . $link . '">' . $course_history->get_year() . '</a>';
            }
            else
            {
                $history[] = $course_history->get_year();
            }
        }
        
        $properties[Translation::get('History')] = implode('  |  ', $history);
        
        if ($training_info_module_instance)
        {
            $parameters = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                $course->get_training_id(), 
                $course->get_source());
            
            $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $training_info_module_instance->get_id(), 
                $parameters);
            
            if ($is_allowed)
            {
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $properties[Translation::get('Training')] = '<a href="' . $url . '">' . $course->get_training() . '</a>';
            }
            else
            {
                $properties[Translation::get('Training')] = $course->get_training();
            }
        }
        else
        {
            $properties[Translation::get('Training')] = $course->get_training();
        }
        
        if ($faculty_info_module_instance)
        {
            $parameters = new \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters(
                $course->get_faculty_id(), 
                $course->get_source());
            
            $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $faculty_info_module_instance->get_id(), 
                $parameters);
            
            if ($is_allowed)
            {
                $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                $properties[Translation::get('Faculty')] = '<a href="' . $url . '">' . $course->get_faculty() . '</a>';
            }
            else
            {
                $properties[Translation::get('Faculty')] = $course->get_faculty();
            }
        }
        else
        {
            $properties[Translation::get('Faculty')] = $course->get_faculty();
        }
        
        $properties[Translation::get('Credits')] = $course->get_credits();
        $properties[Translation::get('Weight')] = $course->get_weight();
        $properties[Translation::get('TrajectoryPart')] = $course->get_trajectory_part();
        $properties[Translation::get('ProgrammeType')] = Translation::get($course->get_programme_type_string());
        
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $programme_parts = array();
            
            foreach ($course->get_children() as $child)
            {
                $parameters = new Parameters($child->get_id(), $child->get_source());
                $child_url = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                
                $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::is_allowed(
                    \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                    $course_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $link = '<a href="' . $child_url . '">' . $child->get_name() . '</a>';
                }
                else
                {
                    $link = $child->get_name();
                }
                $programme_parts[] = Translation::get(
                    'CreditAmount', 
                    array('COURSE' => $link, 'CREDITS' => $child->get_credits()));
            }
            $properties[Translation::get('ProgrammeParts')] = implode('<br/>', $programme_parts);
        }
        
        if ($course->get_programme_type() != Course::PROGRAMME_TYPE_COMPLEX)
        {
            if (! is_null($course->get_timeframe_visual_id()))
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'General/Timeframe/' .
                     $course->get_timeframe_visual_id() . '.png" alt="' . Translation::get($course->get_timeframe()) .
                     '" title="' . Translation::get($course->get_timeframe()) . '"/>';
                $properties[Translation::get('Timeframe')] = $image;
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get($course->get_timeframe()), 
                    Translation::get('Timeframe'));
                
                $properties[Translation::get('TimeframeParts')] = $course->get_timeframe_parts_string();
            }
        }
        
        if ($course->get_level())
        {
            $properties[Translation::get('Level')] = $course->get_level();
        }
        if ($course->get_kind())
        {
            $properties[Translation::get('Kind')] = $course->get_kind();
        }
        if ($course->has_languages())
        {
            $properties[Translation::get('Languages')] = $course->get_languages_string();
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
                        $user = \Chamilo\Core\User\Storage\DataManager::retrieve_user_by_official_code(
                            $coordinator->get_person_id());
                        if ($user)
                        {
                            $parameters = new \Ehb\Application\Discovery\Module\TeachingAssignment\Parameters(
                                $user->get_id());
                            
                            $is_allowed = \Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights::is_allowed(
                                \Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                                $teaching_assignment_module_instance->get_id(), 
                                $parameters);
                            
                            if ($is_allowed)
                            {
                                $url = $this->get_instance_url(
                                    $teaching_assignment_module_instance->get_id(), 
                                    $parameters);
                                
                                $coordinators[] = '<a href="' . $url . '">' . $coordinator . '</a>';
                            }
                            else
                            {
                                $coordinators[] = $coordinator;
                            }
                        }
                        else
                        {
                            $coordinators[] = $coordinator;
                        }
                    }
                }
                $properties[Translation::get('Coordinators')] = implode(', ', $coordinators);
            }
            else
            {
                $properties[Translation::get('Coordinators')] = $course->get_coordinators_string();
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
                        $user = \Chamilo\Core\User\Storage\DataManager::retrieve_user_by_official_code(
                            $teacher->get_person_id());
                        if ($user)
                        {
                            $parameters = new \Ehb\Application\Discovery\Module\TeachingAssignment\Parameters(
                                $user->get_id());
                            
                            $is_allowed = \Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights::is_allowed(
                                \Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                                $teaching_assignment_module_instance->get_id(), 
                                $parameters);
                            
                            if ($is_allowed)
                            {
                                $url = $this->get_instance_url(
                                    $teaching_assignment_module_instance->get_id(), 
                                    $parameters);
                                
                                $teachers[] = '<a href="' . $url . '">' . $teacher . '</a>';
                            }
                            else
                            {
                                $teachers[] = $teacher;
                            }
                        }
                        else
                        {
                            $teachers[] = $teacher;
                        }
                    }
                }
                $properties[Translation::get('Teachers')] = implode(', ', $teachers);
            }
            else
            {
                $properties[Translation::get('Teachers')] = $course->get_teachers_string();
            }
        }
        
        foreach ($course->get_costs() as $cost)
        {
            $properties[Translation::get($cost->get_type_string())] = $cost->get_price_string();
        }
        
        $images = array();
        $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'General/FollowingImpossible/degree.png" alt="' .
             Translation::get('DegreePossible') . '" title="' . Translation::get('DegreePossible') . '"/>';
        LegendTable::getInstance()->addSymbol(
            $image, 
            Translation::get('DegreePossible'), 
            Translation::get('FollowingPossible'));
        $images[] = $image;
        if (! is_null($course->get_following_impossible()->get_credit()))
        {
            if ($course->get_following_impossible()->get_credit())
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/credit_impossible.png" alt="' . Translation::get('CreditImpossible') .
                     '" title="' . Translation::get('CreditImpossible') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('CreditImpossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/credit.png" alt="' . Translation::get('CreditPossible') . '" title="' .
                     Translation::get('CreditPossible') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('CreditPossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
        }
        
        if (! is_null($course->get_following_impossible()->get_exam_degree()))
        {
            if ($course->get_following_impossible()->get_exam_degree())
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/exam_degree_impossible.png" alt="' .
                     Translation::get('ExamDegreeImpossible') . '" title="' . Translation::get('ExamDegreeImpossible') .
                     '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('ExamDegreeImpossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/exam_degree.png" alt="' . Translation::get('ExamDegreePossible') .
                     '" title="' . Translation::get('ExamDegreePossible') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('ExamDegreePossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
        }
        
        if (! is_null($course->get_following_impossible()->get_exam_credit()))
        {
            if ($course->get_following_impossible()->get_exam_credit())
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/exam_credit_impossible.png" alt="' .
                     Translation::get('ExamCreditImpossible') . '" title="' . Translation::get('ExamCreditImpossible') .
                     '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('ExamCreditImpossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
            else
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'General/FollowingImpossible/exam_credit.png" alt="' . Translation::get('ExamCreditPossible') .
                     '" title="' . Translation::get('ExamCreditPossible') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('ExamCreditPossible'), 
                    Translation::get('FollowingPossible'));
                $images[] = $image;
            }
        }
        
        $properties[Translation::get('FollowingPossible')] = implode(' ', $images);
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        if ($photo_module_instance)
        {
            $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
            $parameters->set_programme_id($course->get_id());
            $parameters->set_type(\Ehb\Application\Discovery\Module\Photo\Module::TYPE_STUDENT);
            
            $is_allowed = \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $photo_module_instance->get_id(), 
                $parameters);
            
            $buttons = array();
            
            if ($is_allowed)
            {
                // students
                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $image = Theme::getInstance()->getImage(
                    'Type/2', 
                    'png', 
                    Translation::get('Students', null, 'Ehb\Application\Discovery\Module\Photo'), 
                    $url, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get('Students', null, 'Ehb\Application\Discovery\Module\Photo
                    '), 
                    Translation::get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
                
                // teachers
                $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                $parameters->set_programme_id($course->get_id());
                $parameters->set_type(\Ehb\Application\Discovery\Module\Photo\Module::TYPE_TEACHER);
                
                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                
                $image = Theme::getInstance()->getImage(
                    'Type/1', 
                    'png', 
                    Translation::get('Teachers', null, 'Ehb\Application\Discovery\Module\Photo
                    '), 
                    $url, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get(
                        'TeachersNotAvailable', 
                        null, 
                        'Ehb\Application\Discovery\Module\Photo
                    '), 
                    Translation::get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
            }
            else
            {
                $image = Theme::getInstance()->getImage(
                    'Type/2_na', 
                    'png', 
                    Translation::get('StudentsNotAvailable', null, 'Ehb\Application\Discovery\Module\Photo'), 
                    null, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get(
                        'StudentsNotAvailable', 
                        null, 
                        'Ehb\Application\Discovery\Module\Photo
                    '), 
                    Translation::get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
                
                $image = Theme::getInstance()->getImage(
                    'Type/1_na', 
                    'png', 
                    Translation::get(
                        'TeachersNotAvailable', 
                        null, 
                        'Ehb\Application\Discovery\Module\Photo
                    '), 
                    null, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get(
                        'TeachersNotAvailable', 
                        null, 
                        'Ehb\Application\Discovery\Module\Photo
                    '), 
                    Translation::get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
            }
            
            $properties[Translation::get('Photos')] = implode(PHP_EOL, $buttons);
        }
        
        $course_result_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        if ($course_result_module_instance)
        {
            $parameters = new \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Parameters(
                $course->get_id(), 
                $course->get_source());
            
            $is_allowed = \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $course_result_module_instance->get_id(), 
                $parameters);
            
            if ($is_allowed)
            {
                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                $properties[Translation::get(
                    'TypeName', 
                    null, 
                    'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex')] = Theme::getInstance()->getImage(
                    'Logo/16', 
                    'png', 
                    Translation::get(
                        'TypeName', 
                        null, 
                        'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex'), 
                    $url, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex');
            }
            else
            {
                $properties[Translation::get(
                    'TypeName', 
                    null, 
                    'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex')] = Theme::getInstance()->getImage(
                    'Logo/16_na', 
                    'png', 
                    Translation::get(
                        'TypeName', 
                        null, 
                        'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex'), 
                    null, 
                    ToolbarItem::DISPLAY_ICON, 
                    false, 
                    'Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex');
            }
        }
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode(PHP_EOL, $html);
    }

    public function get_materials()
    {
        $course = $this->get_course();
        $tabs = new DynamicTabsRenderer('course_materials');
        
        if ($course->has_materials(Material::TYPE_REQUIRED))
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Material::TYPE_REQUIRED, 
                    Translation::get('Required'), 
                    null, 
                    $this->get_materials_by_type(Material::TYPE_REQUIRED)));
        }
        if ($course->has_materials(Material::TYPE_OPTIONAL))
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Material::TYPE_OPTIONAL, 
                    Translation::get('Optional'), 
                    null, 
                    $this->get_materials_by_type(Material::TYPE_OPTIONAL)));
        }
        
        return $tabs->render();
    }

    public function get_materials_by_type($type)
    {
        $course = $this->get_course();
        
        $html = array();
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_materials_' . $type);
            if ($course->has_materials($type, false))
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $course->get_id(), 
                        Translation::get('General'), 
                        null, 
                        $this->get_course_materials_by_type($course, $type)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_materials($type, false))
                {
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $child->get_id(), 
                            $child->get_name(), 
                            null, 
                            $this->get_course_materials_by_type($child, $type)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_materials_by_type($course, $type);
        }
        return implode(PHP_EOL, $html);
    }

    public function get_course_materials_by_type($course, $type)
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
                    $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'Material/for_sale.png" alt="' .
                         Translation::get('IsForSale') . '" title="' . Translation::get('IsForSale') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $image, 
                        Translation::get('IsForSale'), 
                        Translation::get('ForSale'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'Material/not_for_sale.png" alt="' .
                         Translation::get('IsNotForSale') . '" title="' . Translation::get('IsNotForSale') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $image, 
                        Translation::get('IsNotForSale'), 
                        Translation::get('ForSale'));
                    $table_row[] = $image;
                }
                
                $table_data[] = $table_row;
            }
        }
        
        $cost = $course->get_costs_by_type(Cost::TYPE_MATERIAL);
        
        if (count($table_data) > 0 || ($cost && $type == Material::TYPE_REQUIRED && $cost->get_price()))
        {
            $table = new SortableTable($table_data);
            $table->setColumnHeader(0, Translation::get('Group'), false);
            $table->setColumnHeader(1, Translation::get('Title'), false);
            $table->setColumnHeader(2, Translation::get('Edition'), false);
            $table->setColumnHeader(3, Translation::get('Author'), false);
            $table->setColumnHeader(4, Translation::get('Editor'), false);
            $table->setColumnHeader(5, Translation::get('Isbn'), false);
            $table->setColumnHeader(6, Translation::get('Medium'), false);
            $table->setColumnHeader(7, Translation::get('Remarks'), false);
            $table->setColumnHeader(8, Translation::get('Price'), false);
            $table->setColumnHeader(9, '', false);
            
            $html[] = $table->as_html($cost->get_price_string(), 8);
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_activities()
    {
        $course = $this->get_course();
        
        $html = array();
        
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_activities');
            if ($course->has_activities(false))
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $course->get_id(), 
                        Translation::get('General'), 
                        null, 
                        $this->get_course_activities($course)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_activities(false))
                {
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $child->get_id(), 
                            $child->get_name(), 
                            null, 
                            $this->get_course_activities($child)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_activities($course);
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_course_activities($course)
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
                $table_row[] = Translation::get('ActivityTime', array('TIME' => $activity->get_time()));
                // $table_row[] = $activity->get_remarks();
                // $table_row[] = $activity->get_description();
                
                $table_data[] = $table_row;
            }
        }
        $total = $course->get_activities_by_type(ActivityTotal::class_name());
        
        if (count($table_data) > 0 || $total)
        {
            $table = new SortableTable($table_data);
            $table->setColumnHeader(0, Translation::get('Group'), false);
            $table->setColumnHeader(1, Translation::get('Name'), false);
            $table->setColumnHeader(2, Translation::get('Time'), false);
            // $table->setColumnHeader(3, Translation :: get('Remarks'), false);
            // $table->setColumnHeader(4, Translation :: get('Description'), false);
            
            $total = $course->get_activities_by_type(ActivityTotal::class_name());
            if ($total)
            {
                $html[] = $table->as_html(Translation::get('ActivityTime', array('TIME' => $total->get_time())), 2);
            }
            else
            {
                $html[] = $table->as_html();
            }
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_competences()
    {
        $course = $this->get_course();
        $tabs = new DynamicTabsRenderer('competences');
        if (count($course->get_competences_by_type(Competence::TYPE_BEGIN)) > 0)
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Competence::TYPE_BEGIN, 
                    Translation::get('BeginCompetence'), 
                    Theme::getInstance()->getImagesPath() . 'Competence/Tabs/' . Competence::TYPE_BEGIN . '.png', 
                    $this->get_competences_by_type(Competence::TYPE_BEGIN)));
        }
        if (count($course->get_competences_by_type(Competence::TYPE_END)) > 0)
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Competence::TYPE_END, 
                    Translation::get('EndCompetence'), 
                    Theme::getInstance()->getImagesPath() . 'Competence/Tabs/' . Competence::TYPE_END . '.png', 
                    $this->get_competences_by_type(Competence::TYPE_END)));
        }
        return $tabs->render();
    }

    public function get_competences_by_type($type)
    {
        $course = $this->get_course();
        $html = array();
        
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_competences_' . $type);
            if ($course->has_competences($type, false))
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $course->get_id(), 
                        Translation::get('General'), 
                        null, 
                        $this->get_course_competences_by_type($course, $type)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_competences($type, false))
                {
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $child->get_id(), 
                            $child->get_name(), 
                            null, 
                            $this->get_course_competences_by_type($child, $type)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_competences_by_type($course, $type);
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_course_competences_by_type($course, $type)
    {
        $table_data = array();
        
        $competences = $course->get_competences_by_type($type);
        $has_structured_competences = false;
        
        foreach ($competences as $competence)
        {
            if ($competence instanceof CompetenceStructured)
            {
                $has_structured_competences = true;
                break;
            }
        }
        
        foreach ($competences as $competence)
        {
            if ($competence instanceof CompetenceDescription && ! $has_structured_competences)
            {
                $html[] = '<div class="content_object" style="padding: 10px 10px 10px 10px;">';
                $html[] = '<div class="description">';
                $html[] = $competence->get_description();
                $html[] = '</div>';
                $html[] = '</div>';
            }
            
            if ($competence instanceof CompetenceStructured)
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
            $table->setColumnHeader(0, Translation::get('Code'), false);
            // $table->setColumnHeader(1, Translation :: get('Summary'), false);
            $table->setColumnHeader(1, Translation::get('Description'), false);
            $table->setColumnHeader(2, Translation::get('Level'), false);
            $html[] = $table->as_html();
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_content()
    {
        $html = array();
        $course = $this->get_course();
        
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_contents');
            if ($course->has_content(false))
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $course->get_id(), 
                        Translation::get('General'), 
                        null, 
                        $this->get_course_contents($course)));
            }
            foreach ($course->get_children() as $child)
            {
                if ($child->has_content(false))
                {
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $child->get_id(), 
                            $child->get_name(), 
                            null, 
                            $this->get_course_contents($child)));
                }
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_contents($course);
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_course_contents($course)
    {
        $html = array();
        
        if (! StringUtilities::getInstance()->isNullOrEmpty($course->get_goals(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' .
                 Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Content/goals.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation::get('Goals');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_goals();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        
        if (! StringUtilities::getInstance()->isNullOrEmpty($course->get_contents(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' .
                 Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Content/contents.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation::get('Contents');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_contents();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities::getInstance()->isNullOrEmpty($course->get_coaching(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' .
                 Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Content/coaching.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation::get('Coaching');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_coaching();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (! StringUtilities::getInstance()->isNullOrEmpty($course->get_succession(), true))
        {
            $html[] = '<div class="content_object" style="background-image: url(' .
                 Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Content/succession.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation::get('Succession');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $course->get_succession();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        return implode(PHP_EOL, $html);
    }

    public function get_evaluations()
    {
        $html = array();
        $course = $this->get_course();
        
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            $tabs = new DynamicTabsRenderer('course_evaluations');
            
            $tabs->add_tab(
                new DynamicContentTab(
                    $course->get_id(), 
                    Translation::get('General'), 
                    null, 
                    $this->get_course_evaluations($course)));
            
            foreach ($course->get_children() as $child)
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $child->get_id(), 
                        $child->get_name(), 
                        null, 
                        $this->get_course_evaluations($child)));
            }
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = $this->get_course_evaluations($course);
        }
        
        return implode(PHP_EOL, $html);
    }

    public function get_course_evaluations($course)
    {
        $html = array();
        $table_data = array();
        
        $properties = array();
        $properties[Translation::get('Credits')] = $course->get_credits();
        $properties[Translation::get('Weight')] = $course->get_weight();
        if (($course->get_programme_type() == Course::PROGRAMME_TYPE_SIMPLE ||
             $course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX) && ! is_null($course->get_deliberation()))
        {
            $properties[Translation::get('Deliberation')] = Translation::get(
                'DeliberationInfo', 
                array('DELIBERATION' => $course->get_deliberation()));
        }
        if ($course->get_programme_type() == Course::PROGRAMME_TYPE_SIMPLE ||
             $course->get_programme_type() == Course::PROGRAMME_TYPE_PART)
        {
            $properties[Translation::get('Quotation')] = Translation::get($course->get_result_scale_string());
            $second_chance = array();
            if ($course->get_second_chance()->get_exam())
            {
                $exam_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'Evaluation/SecondChance/exam_allowed.png" alt="' . Translation::get('SecondChanceExamAllowed') .
                     '" title="' . Translation::get('SecondChanceExamAllowed') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $exam_image, 
                    Translation::get('SecondChanceExamAllowed'), 
                    Translation::get('SecondChanceExam'));
            }
            else
            {
                $exam_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'Evaluation/SecondChance/exam_not_allowed.png" alt="' .
                     Translation::get('SecondChanceExamNotAllowed') . '" title="' .
                     Translation::get('SecondChanceExamNotAllowed') . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $exam_image, 
                    Translation::get('SecondChanceExamNotAllowed'), 
                    Translation::get('SecondChanceExam'));
            }
            $second_chance[] = $exam_image;
            
            if (! is_null($course->get_second_chance()->get_enrollment()))
            {
                if ($course->get_second_chance()->get_enrollment())
                {
                    $enrollment_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                         'Evaluation/SecondChance/enrollment_allowed.png" alt="' .
                         Translation::get('SecondChanceEnrollmentAllowed') . '" title="' .
                         Translation::get('SecondChanceEnrollmentAllowed') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $enrollment_image, 
                        Translation::get('SecondChanceEnrollmentAllowed'), 
                        Translation::get('SecondChanceEnrollment'));
                }
                else
                {
                    $enrollment_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                         'Evaluation/SecondChance/enrollment_not_allowed.png" alt="' .
                         Translation::get('SecondChanceEnrollmentNotAllowed') . '" title="' .
                         Translation::get('SecondChanceEnrollmentNotAllowed') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $enrollment_image, 
                        Translation::get('SecondChanceEnrollmentNotAllowed'), 
                        Translation::get('SecondChanceEnrollment'));
                }
                $second_chance[] = $enrollment_image;
            }
            
            $properties[Translation::get('SecondChance')] = implode(' ', $second_chance);
        }
        elseif ($course->get_programme_type() == Course::PROGRAMME_TYPE_COMPLEX)
        {
            if (! is_null($course->get_score_calculation()))
            {
                $properties[Translation::get('ScoreCalculation')] = Translation::get(
                    $course->get_score_calculation_string());
            }
            $quotation_parts = array();
            
            $data_source = $this->get_module_instance()->get_setting('data_source');
            $course_module_instance = \Ehb\Application\Discovery\Module::exists(
                'Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex', 
                array('data_source' => $data_source));
            
            foreach ($course->get_children() as $child)
            {
                $parameters = new Parameters($child->get_id(), $child->get_source());
                
                $is_allowed = \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::is_allowed(
                    \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                    $course_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $child_url = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $link = '<a href="' . $child_url . '">' . $child->get_name() . '</a>';
                }
                else
                {
                    $link = $child->get_name();
                }
                
                $quotation_parts[] = Translation::get(
                    'QuotationParts', 
                    array('COURSE' => $link, 'RESULT_SCALE' => Translation::get($child->get_result_scale_string())));
            }
            $properties[Translation::get('Quotation')] = implode('<br/>', $quotation_parts);
            
            if (! is_null($course->get_second_chance()->get_exam_parts()))
            {
                $exam_parts_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                     'Evaluation/SecondChance/exam_parts/' . $course->get_second_chance()->get_exam_parts() .
                     '.png" alt="' . Translation::get($course->get_second_chance()->get_exam_parts_string()) .
                     '" title="' . Translation::get($course->get_second_chance()->get_exam_parts_string()) . '"/>';
                LegendTable::getInstance()->addSymbol(
                    $exam_parts_image, 
                    Translation::get($course->get_second_chance()->get_exam_parts_string()), 
                    Translation::get('SecondChance'));
                
                $properties[Translation::get('SecondChance')] = $exam_parts_image;
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
                    $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'Evaluation/permanent.png" alt="' .
                         Translation::get('IsPermanent') . '" title="' . Translation::get('IsPermanent') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $image, 
                        Translation::get('IsPermanent'), 
                        Translation::get('Permanent'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme::getInstance()->getImagesPath() . 'Evaluation/not_permanent.png" alt="' .
                         Translation::get('IsNotPermanent') . '" title="' . Translation::get('IsNotPermanent') . '"/>';
                    LegendTable::getInstance()->addSymbol(
                        $image, 
                        Translation::get('IsNotPermanent'), 
                        Translation::get('Permanent'));
                    $table_row[] = $image;
                }
                
                $table_data[] = $table_row;
            }
        }
        
        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->setColumnHeader(0, Translation::get('Try'), false);
            $table->setColumnHeader(1, Translation::get('Moment'), false);
            $table->setColumnHeader(2, Translation::get('Type'), false);
            $table->setColumnHeader(3, Translation::get('Percentage'), false);
            $table->setColumnHeader(4, Translation::get('Remarks'), false);
            $table->setColumnHeader(5, Translation::get('Permanent'), false);
            $html[] = $table->as_html();
        }
        
        return implode(PHP_EOL, $html);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
