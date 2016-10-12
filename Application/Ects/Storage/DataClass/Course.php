<?php
namespace Ehb\Application\Ects\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Course extends DataClass
{
    // Properties
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_ID = 'id';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_PARENT_ID = 'parent_id';
    const PROPERTY_PREVIOUS_PARENT_ID = 'previous_parent_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_NAME_CODE = 'name_code';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_PROGRAMME_TYPE = 'programme_type';
    const PROPERTY_WEIGHT = 'weight';
    const PROPERTY_TOTAL_STUDY_TIME = 'total_study_time';
    const PROPERTY_TOTAL_MATERIAL_PRICE = 'total_material_price';
    const PROPERTY_TIMEFRAME_VISUAL_ID = 'timeframe_visual_id';
    const PROPERTY_TIMEFRAME_ID = 'timeframe_id';
    const PROPERTY_RESULT_SCALE_ID = 'result_scale_id';
    const PROPERTY_SCORE_CALCULATION = 'score_calculation';
    const PROPERTY_SECOND_EXAM_CHANCE = 'second_exam_chance';
    const PROPERTY_DELIBERATION = 'deliberation';
    const PROPERTY_LEVEL = 'level';
    const PROPERTY_KIND = 'kind';
    const PROPERTY_IMPOSSIBLE_CREDIT = 'impossible_credit';
    const PROPERTY_IMPOSSIBLE_EXAM_DEGREE = 'impossible_exam_degree';
    const PROPERTY_IMPOSSIBLE_EXAM_CREDIT = 'impossible_exam_credit';
    const PROPERTY_SECOND_ENROLLMENT = 'second_enrollment';
    const PROPERTY_MATERIAL_OPTIONAL = 'material_optional';
    const PROPERTY_MATERIAL_REQUIRED = 'material_required';
    const PROPERTY_COMPETENCES_START = 'competences_start';
    const PROPERTY_COMPETENCES_END = 'competences_end';
    const PROPERTY_GOALS = 'goals';
    const PROPERTY_CONTENTS = 'contents';
    const PROPERTY_COACHING = 'coaching';
    const PROPERTY_SUCCESSION = 'succession';
    const PROPERTY_ADDITIONAL_COSTS = 'additional_costs';
    const PROPERTY_EVALUATION = 'evaluation';
    const PROPERTY_JURY = 'jury';
    const PROPERTY_REPLACEABLE = 'replaceable';
    const PROPERTY_ACTIVITIES = 'activities';
    const PROPERTY_TRAINING_UNIT = 'training_unit';
    const PROPERTY_SECOND_EXAM_PARTS = 'second_exam_parts';
    const PROPERTY_APPROVED = 'approved';
    const PROPERTY_EXCHANGE = 'exchange';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_SOURCE;
        $extended_property_names[] = self::PROPERTY_ID;
        $extended_property_names[] = self::PROPERTY_PREVIOUS_ID;
        $extended_property_names[] = self::PROPERTY_PARENT_ID;
        $extended_property_names[] = self::PROPERTY_PREVIOUS_PARENT_ID;
        $extended_property_names[] = self::PROPERTY_NAME;
        $extended_property_names[] = self::PROPERTY_NAME_CODE;
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_TRAINING_ID;
        $extended_property_names[] = self::PROPERTY_TRAINING;
        $extended_property_names[] = self::PROPERTY_FACULTY;
        $extended_property_names[] = self::PROPERTY_FACULTY_ID;
        $extended_property_names[] = self::PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self::PROPERTY_CREDITS;
        $extended_property_names[] = self::PROPERTY_PROGRAMME_TYPE;
        $extended_property_names[] = self::PROPERTY_WEIGHT;
        $extended_property_names[] = self::PROPERTY_TOTAL_STUDY_TIME;
        $extended_property_names[] = self::PROPERTY_TOTAL_MATERIAL_PRICE;
        $extended_property_names[] = self::PROPERTY_TIMEFRAME_VISUAL_ID;
        $extended_property_names[] = self::PROPERTY_TIMEFRAME_ID;
        $extended_property_names[] = self::PROPERTY_RESULT_SCALE_ID;
        $extended_property_names[] = self::PROPERTY_SCORE_CALCULATION;
        $extended_property_names[] = self::PROPERTY_SECOND_EXAM_CHANCE;
        $extended_property_names[] = self::PROPERTY_DELIBERATION;
        $extended_property_names[] = self::PROPERTY_LEVEL;
        $extended_property_names[] = self::PROPERTY_KIND;
        $extended_property_names[] = self::PROPERTY_IMPOSSIBLE_CREDIT;
        $extended_property_names[] = self::PROPERTY_IMPOSSIBLE_EXAM_DEGREE;
        $extended_property_names[] = self::PROPERTY_IMPOSSIBLE_EXAM_CREDIT;
        $extended_property_names[] = self::PROPERTY_SECOND_ENROLLMENT;
        $extended_property_names[] = self::PROPERTY_MATERIAL_OPTIONAL;
        $extended_property_names[] = self::PROPERTY_MATERIAL_REQUIRED;
        $extended_property_names[] = self::PROPERTY_COMPETENCES_START;
        $extended_property_names[] = self::PROPERTY_COMPETENCES_END;
        $extended_property_names[] = self::PROPERTY_GOALS;
        $extended_property_names[] = self::PROPERTY_CONTENTS;
        $extended_property_names[] = self::PROPERTY_COACHING;
        $extended_property_names[] = self::PROPERTY_SUCCESSION;
        $extended_property_names[] = self::PROPERTY_ADDITIONAL_COSTS;
        $extended_property_names[] = self::PROPERTY_EVALUATION;
        $extended_property_names[] = self::PROPERTY_JURY;
        $extended_property_names[] = self::PROPERTY_REPLACEABLE;
        $extended_property_names[] = self::PROPERTY_ACTIVITIES;
        $extended_property_names[] = self::PROPERTY_TRAINING_UNIT;
        $extended_property_names[] = self::PROPERTY_SECOND_EXAM_PARTS;
        $extended_property_names[] = self::PROPERTY_APPROVED;
        $extended_property_names[] = self::PROPERTY_EXCHANGE;

        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function getSource()
    {
        return $this->get_default_property(self::PROPERTY_SOURCE);
    }

    /**
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->set_default_property(self::PROPERTY_SOURCE, $source);
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->get_default_property(self::PROPERTY_ID);
    }

    /**
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->set_default_property(self::PROPERTY_ID, $id);
    }

    /**
     *
     * @return integer
     */
    public function getPreviousId()
    {
        return $this->get_default_property(self::PROPERTY_PREVIOUS_ID);
    }

    /**
     *
     * @param string $previous_id
     */
    public function setPreviousId($previous_id)
    {
        $this->set_default_property(self::PROPERTY_PREVIOUS_ID, $previous_id);
    }

    /**
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->get_default_property(self::PROPERTY_PARENT_ID);
    }

    /**
     *
     * @param string $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->set_default_property(self::PROPERTY_PARENT_ID, $parent_id);
    }

    /**
     *
     * @return integer
     */
    public function getPreviousParentId()
    {
        return $this->get_default_property(self::PROPERTY_PREVIOUS_PARENT_ID);
    }

    /**
     *
     * @param string $previous_parent_id
     */
    public function setPreviousParentId($previous_parent_id)
    {
        $this->set_default_property(self::PROPERTY_PREVIOUS_PARENT_ID, $previous_parent_id);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->get_default_property(self::PROPERTY_NAME);
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->set_default_property(self::PROPERTY_NAME, $name);
    }

    /**
     *
     * @return string
     */
    public function getNameCode()
    {
        return $this->get_default_property(self::PROPERTY_NAME_CODE);
    }

    /**
     *
     * @param string $name_code
     */
    public function setNameCode($name_code)
    {
        $this->set_default_property(self::PROPERTY_NAME_CODE, $name_code);
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->get_default_property(self::PROPERTY_YEAR);
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->set_default_property(self::PROPERTY_YEAR, $year);
    }

    /**
     *
     * @return integer
     */
    public function getTrainingId()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @param string $training_id
     */
    public function setTrainingId($training_id)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     *
     * @return string
     */
    public function getTraining()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING);
    }

    /**
     *
     * @param string $training
     */
    public function setTraining($training)
    {
        $this->set_default_property(self::PROPERTY_TRAINING, $training);
    }

    /**
     *
     * @return string
     */
    public function getFaculty()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY);
    }

    /**
     *
     * @param string $faculty
     */
    public function setFaculty($faculty)
    {
        $this->set_default_property(self::PROPERTY_FACULTY, $faculty);
    }

    /**
     *
     * @return integer
     */
    public function getFacultyId()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY_ID);
    }

    /**
     *
     * @param string $faculty_id
     */
    public function setFacultyId($faculty_id)
    {
        $this->set_default_property(self::PROPERTY_FACULTY_ID, $faculty_id);
    }

    /**
     *
     * @return string
     */
    public function getTrajectoryPart()
    {
        return $this->get_default_property(self::PROPERTY_TRAJECTORY_PART);
    }

    /**
     *
     * @param string $trajectory_part
     */
    public function setTrajectoryPart($trajectory_part)
    {
        $this->set_default_property(self::PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    /**
     *
     * @return float
     */
    public function getCredits()
    {
        return $this->get_default_property(self::PROPERTY_CREDITS);
    }

    /**
     *
     * @param string $credits
     */
    public function setCredits($credits)
    {
        $this->set_default_property(self::PROPERTY_CREDITS, $credits);
    }

    /**
     *
     * @return integer
     */
    public function getProgrammeType()
    {
        return $this->get_default_property(self::PROPERTY_PROGRAMME_TYPE);
    }

    /**
     *
     * @param string $programme_type
     */
    public function setProgrammeType($programme_type)
    {
        $this->set_default_property(self::PROPERTY_PROGRAMME_TYPE, $programme_type);
    }

    /**
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->get_default_property(self::PROPERTY_WEIGHT);
    }

    /**
     *
     * @param string $weight
     */
    public function setWeight($weight)
    {
        $this->set_default_property(self::PROPERTY_WEIGHT, $weight);
    }

    /**
     *
     * @return float
     */
    public function getTotalStudyTime()
    {
        return $this->get_default_property(self::PROPERTY_TOTAL_STUDY_TIME);
    }

    /**
     *
     * @param string $total_study_time
     */
    public function setTotalStudyTime($total_study_time)
    {
        $this->set_default_property(self::PROPERTY_TOTAL_STUDY_TIME, $total_study_time);
    }

    /**
     *
     * @return float
     */
    public function getTotalMaterialPrice()
    {
        return $this->get_default_property(self::PROPERTY_TOTAL_MATERIAL_PRICE);
    }

    /**
     *
     * @param string $total_material_price
     */
    public function setTotalMaterialPrice($total_material_price)
    {
        $this->set_default_property(self::PROPERTY_TOTAL_MATERIAL_PRICE, $total_material_price);
    }

    /**
     *
     * @return integer
     */
    public function getTimeframeVisualId()
    {
        return $this->get_default_property(self::PROPERTY_TIMEFRAME_VISUAL_ID);
    }

    /**
     *
     * @param string $timeframe_visual_id
     */
    public function setTimeframeVisualId($timeframe_visual_id)
    {
        $this->set_default_property(self::PROPERTY_TIMEFRAME_VISUAL_ID, $timeframe_visual_id);
    }

    /**
     *
     * @return integer
     */
    public function getTimeframeId()
    {
        return $this->get_default_property(self::PROPERTY_TIMEFRAME_ID);
    }

    /**
     *
     * @param string $timeframe_id
     */
    public function setTimeframeId($timeframe_id)
    {
        $this->set_default_property(self::PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    /**
     *
     * @return integer
     */
    public function getResultScaleId()
    {
        return $this->get_default_property(self::PROPERTY_RESULT_SCALE_ID);
    }

    /**
     *
     * @param string $result_scale_id
     */
    public function setResultScaleId($result_scale_id)
    {
        $this->set_default_property(self::PROPERTY_RESULT_SCALE_ID, $result_scale_id);
    }

    /**
     *
     * @return integer
     */
    public function getScoreCalculation()
    {
        return $this->get_default_property(self::PROPERTY_SCORE_CALCULATION);
    }

    /**
     *
     * @param string $score_calculation
     */
    public function setScoreCalculation($score_calculation)
    {
        $this->set_default_property(self::PROPERTY_SCORE_CALCULATION, $score_calculation);
    }

    /**
     *
     * @return integer
     */
    public function getSecondExamChance()
    {
        return $this->get_default_property(self::PROPERTY_SECOND_EXAM_CHANCE);
    }

    /**
     *
     * @param string $second_exam_chance
     */
    public function setSecondExamChance($second_exam_chance)
    {
        $this->set_default_property(self::PROPERTY_SECOND_EXAM_CHANCE, $second_exam_chance);
    }

    /**
     *
     * @return string
     */
    public function getDeliberation()
    {
        return $this->get_default_property(self::PROPERTY_DELIBERATION);
    }

    /**
     *
     * @param string $deliberation
     */
    public function setDeliberation($deliberation)
    {
        $this->set_default_property(self::PROPERTY_DELIBERATION, $deliberation);
    }

    /**
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->get_default_property(self::PROPERTY_LEVEL);
    }

    /**
     *
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->set_default_property(self::PROPERTY_LEVEL, $level);
    }

    /**
     *
     * @return string
     */
    public function getKind()
    {
        return $this->get_default_property(self::PROPERTY_KIND);
    }

    /**
     *
     * @param string $kind
     */
    public function setKind($kind)
    {
        $this->set_default_property(self::PROPERTY_KIND, $kind);
    }

    /**
     *
     * @return integer
     */
    public function getImpossibleCredit()
    {
        return $this->get_default_property(self::PROPERTY_IMPOSSIBLE_CREDIT);
    }

    /**
     *
     * @param string $impossible_credit
     */
    public function setImpossibleCredit($impossible_credit)
    {
        $this->set_default_property(self::PROPERTY_IMPOSSIBLE_CREDIT, $impossible_credit);
    }

    /**
     *
     * @return integer
     */
    public function getImpossibleExamDegree()
    {
        return $this->get_default_property(self::PROPERTY_IMPOSSIBLE_EXAM_DEGREE);
    }

    /**
     *
     * @param string $impossible_exam_degree
     */
    public function setImpossibleExamDegree($impossible_exam_degree)
    {
        $this->set_default_property(self::PROPERTY_IMPOSSIBLE_EXAM_DEGREE, $impossible_exam_degree);
    }

    /**
     *
     * @return integer
     */
    public function getImpossibleExamCredit()
    {
        return $this->get_default_property(self::PROPERTY_IMPOSSIBLE_EXAM_CREDIT);
    }

    /**
     *
     * @param string $impossible_exam_credit
     */
    public function setImpossibleExamCredit($impossible_exam_credit)
    {
        $this->set_default_property(self::PROPERTY_IMPOSSIBLE_EXAM_CREDIT, $impossible_exam_credit);
    }

    /**
     *
     * @return integer
     */
    public function getSecondEnrollment()
    {
        return $this->get_default_property(self::PROPERTY_SECOND_ENROLLMENT);
    }

    /**
     *
     * @param string $second_enrollment
     */
    public function setSecondEnrollment($second_enrollment)
    {
        $this->set_default_property(self::PROPERTY_SECOND_ENROLLMENT, $second_enrollment);
    }

    /**
     *
     * @return string
     */
    public function getMaterialOptional()
    {
        return $this->get_default_property(self::PROPERTY_MATERIAL_OPTIONAL);
    }

    /**
     *
     * @param string $material_optional
     */
    public function setMaterialOptional($material_optional)
    {
        $this->set_default_property(self::PROPERTY_MATERIAL_OPTIONAL, $material_optional);
    }

    /**
     *
     * @return string
     */
    public function getMaterialRequired()
    {
        return $this->get_default_property(self::PROPERTY_MATERIAL_REQUIRED);
    }

    /**
     *
     * @param string $material_required
     */
    public function setMaterialRequired($material_required)
    {
        $this->set_default_property(self::PROPERTY_MATERIAL_REQUIRED, $material_required);
    }

    /**
     *
     * @return string
     */
    public function getCompetencesStart()
    {
        return $this->get_default_property(self::PROPERTY_COMPETENCES_START);
    }

    /**
     *
     * @param string $competences_start
     */
    public function setCompetencesStart($competences_start)
    {
        $this->set_default_property(self::PROPERTY_COMPETENCES_START, $competences_start);
    }

    /**
     *
     * @return string
     */
    public function getCompetencesEnd()
    {
        return $this->get_default_property(self::PROPERTY_COMPETENCES_END);
    }

    /**
     *
     * @param string $competences_end
     */
    public function setCompetencesEnd($competences_end)
    {
        $this->set_default_property(self::PROPERTY_COMPETENCES_END, $competences_end);
    }

    /**
     *
     * @return string
     */
    public function getGoals()
    {
        return $this->get_default_property(self::PROPERTY_GOALS);
    }

    /**
     *
     * @param string $goals
     */
    public function setGoals($goals)
    {
        $this->set_default_property(self::PROPERTY_GOALS, $goals);
    }

    /**
     *
     * @return string
     */
    public function getContents()
    {
        return $this->get_default_property(self::PROPERTY_CONTENTS);
    }

    /**
     *
     * @param string $contents
     */
    public function setContents($contents)
    {
        $this->set_default_property(self::PROPERTY_CONTENTS, $contents);
    }

    /**
     *
     * @return string
     */
    public function getCoaching()
    {
        return $this->get_default_property(self::PROPERTY_COACHING);
    }

    /**
     *
     * @param string $coaching
     */
    public function setCoaching($coaching)
    {
        $this->set_default_property(self::PROPERTY_COACHING, $coaching);
    }

    /**
     *
     * @return string
     */
    public function getSuccession()
    {
        return $this->get_default_property(self::PROPERTY_SUCCESSION);
    }

    /**
     *
     * @param string $succession
     */
    public function setSuccession($succession)
    {
        $this->set_default_property(self::PROPERTY_SUCCESSION, $succession);
    }

    /**
     *
     * @return string
     */
    public function getAdditionalCosts()
    {
        return $this->get_default_property(self::PROPERTY_ADDITIONAL_COSTS);
    }

    /**
     *
     * @param string $additional_costs
     */
    public function setAdditionalCosts($additional_costs)
    {
        $this->set_default_property(self::PROPERTY_ADDITIONAL_COSTS, $additional_costs);
    }

    /**
     *
     * @return string
     */
    public function getEvaluation()
    {
        return $this->get_default_property(self::PROPERTY_EVALUATION);
    }

    /**
     *
     * @param string $evaluation
     */
    public function setEvaluation($evaluation)
    {
        $this->set_default_property(self::PROPERTY_EVALUATION, $evaluation);
    }

    /**
     *
     * @return integer
     */
    public function getJury()
    {
        return $this->get_default_property(self::PROPERTY_JURY);
    }

    /**
     *
     * @param string $jury
     */
    public function setJury($jury)
    {
        $this->set_default_property(self::PROPERTY_JURY, $jury);
    }

    /**
     *
     * @return integer
     */
    public function getReplaceable()
    {
        return $this->get_default_property(self::PROPERTY_REPLACEABLE);
    }

    /**
     *
     * @param string $replaceable
     */
    public function setReplaceable($replaceable)
    {
        $this->set_default_property(self::PROPERTY_REPLACEABLE, $replaceable);
    }

    /**
     *
     * @return string
     */
    public function getActivities()
    {
        return $this->get_default_property(self::PROPERTY_ACTIVITIES);
    }

    /**
     *
     * @param string $activities
     */
    public function setActivities($activities)
    {
        $this->set_default_property(self::PROPERTY_ACTIVITIES, $activities);
    }

    /**
     *
     * @return string
     */
    public function getTrainingUnit()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_UNIT);
    }

    /**
     *
     * @param string $training_unit
     */
    public function setTrainingUnit($training_unit)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_UNIT, $training_unit);
    }

    /**
     *
     * @return integer
     */
    public function getSecondExamParts()
    {
        return $this->get_default_property(self::PROPERTY_SECOND_EXAM_PARTS);
    }

    /**
     *
     * @param string $second_exam_parts
     */
    public function setSecondExamParts($second_exam_parts)
    {
        $this->set_default_property(self::PROPERTY_SECOND_EXAM_PARTS, $second_exam_parts);
    }

    /**
     *
     * @return integer
     */
    public function getApproved()
    {
        return $this->get_default_property(self::PROPERTY_APPROVED);
    }

    /**
     *
     * @param string $approved
     */
    public function setApproved($approved)
    {
        $this->set_default_property(self::PROPERTY_APPROVED, $approved);
    }

    /**
     *
     * @return integer
     */
    public function getExchange()
    {
        return $this->get_default_property(self::PROPERTY_EXCHANGE);
    }

    /**
     *
     * @param string $exchange
     */
    public function setExchange($exchange)
    {
        $this->set_default_property(self::PROPERTY_EXCHANGE, $exchange);
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_discovery_course_basic';
    }
}