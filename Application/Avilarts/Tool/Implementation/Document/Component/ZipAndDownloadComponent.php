<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublicationCategory;
use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;
use Chamilo\Core\Repository\ContentObject\File\Storage\DataClass\File;
use Chamilo\Core\Repository\ContentObject\Webpage\Storage\DataClass\Webpage;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\File\Compression\Filecompression;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\SubselectCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * $Id: document_zip_and_download.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.document.component
 */
class ZipAndDownloadComponent extends Manager
{

    private $action_bar;

    private $zip_name;

    public function run()
    {
        $archive_url = $this->create_document_archive();

        Filesystem :: file_send_for_download($archive_url, true, $this->zip_name . '.zip');
        Filesystem :: remove($archive_url);
    }

    private function create_document_archive()
    {
        $parent = $this->get_parent();
        $count = 0;

        $course_name = $this->get_course()->get_title();
        $this->zip_name = $course_name . ' - ' . Translation :: get("Document");

        $category_id = $parent->get_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_CATEGORY);
        if (! isset($category_id) || $category_id == 0 || strlen($category_id) == 0)
        {
            $category_id = 0;
        }
        else
        {
            $category = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                ContentObjectPublicationCategory :: class_name(),
                $category_id);

            $this->zip_name .= ' -_' . $category->get_name();
        }

        // needed to prevent cutoff at a space char
        $this->zip_name = str_replace(" ", "_", $this->zip_name);

        $category_folder_mapping = $this->create_folder_structure($category_id);

        $target_path = current($category_folder_mapping);
        foreach ($category_folder_mapping as $category_id => $dir)
        {
            $is_course_admin = $this->get_course()->is_course_admin($this->get_user());

            // if we have access, retrieve the publications in the current
            // category

            if ($is_course_admin)
            {
                $conditions = array();

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(),
                        ContentObjectPublication :: PROPERTY_COURSE_ID),
                    new StaticConditionVariable($this->get_course_id()));

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(),
                        ContentObjectPublication :: PROPERTY_TOOL),
                    new StaticConditionVariable($this->get_tool_id()));

                $conditions[] = new InCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(),
                        ContentObjectPublication :: PROPERTY_CATEGORY_ID),
                    $category_id);

                $subselect_condition = new InCondition(
                    new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_TYPE),
                    array(File :: class_name(), Webpage :: class_name()));

                $conditions[] = new SubselectCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(),
                        ContentObjectPublication :: PROPERTY_CONTENT_OBJECT_ID),
                    new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_ID),
                    ContentObject :: get_table_name(),
                    $subselect_condition);

                $condition = new AndCondition($conditions);

                $publications = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications(
                    $condition);
            }
            else
            {
                if ($category_id == 0)
                {
                    $course_module_id = $this->get_tool_registration()->get_id();
                    $location_id = \Ehb\Application\Avilarts\Rights\Rights :: get_instance()->get_weblcms_location_by_identifier_from_courses_subtree(
                        \Ehb\Application\Avilarts\Rights\Rights :: TYPE_COURSE_MODULE,
                        $course_module_id,
                        $this->get_course_id());
                }
                else
                {
                    $location_id = \Ehb\Application\Avilarts\Rights\Rights :: get_instance()->get_weblcms_location_by_identifier_from_courses_subtree(
                        \Ehb\Application\Avilarts\Rights\Rights :: TYPE_COURSE_CATEGORY,
                        $category_id,
                        $this->get_course_id());
                }

                $publications = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications_with_view_right_granted_in_category_location(
                    $location_id,
                    $this->get_entities(),
                    null,
                    array(),
                    0,
                    - 1,
                    $this->get_user_id());
            }

            if ($publications)
            {
                while ($publication = $publications->next_result())
                {
                    if (! $is_course_admin && $publication[ContentObjectPublication :: PROPERTY_HIDDEN])
                    {
                        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT, $publication))
                        {
                            continue;
                        }
                    }

                    $count ++;

                    $document = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
                        ContentObject :: class_name(),
                        $publication[ContentObjectPublication :: PROPERTY_CONTENT_OBJECT_ID]);

                    if (! $document instanceof File && ! $document instanceof Webpage)
                    {
                        continue;
                    }

                    $document_path = $document->get_full_path();
                    $archive_file_location = $dir . '/' . Filesystem :: create_unique_name(
                        $dir,
                        $document->get_filename());
                    Filesystem :: copy_file($document_path, $archive_file_location);
                }
            }
        }

        if ($count == 0)
        {
            throw new \Exception(Translation :: get('NoDocumentsPublished'));
        }

        $compression = Filecompression :: factory();
        $archive_file = $compression->create_archive($target_path);
        Filesystem :: remove($target_path);
        $archive_url = Path :: getInstance()->getBasePath() . str_replace(
            DIRECTORY_SEPARATOR,
            '/',
            str_replace(realpath(Path :: getInstance()->getBasePath()), '', $archive_file));
        return $archive_url;
    }

    /**
     * Creates a folder structure from the given categories.
     *
     * @param $categories array|int
     * @param $category_folder_mapping array
     * @param $path
     * @return array An array mapping the category id to the folder.
     */
    private function create_folder_structure($parent_cat, &$category_folder_mapping = array(), $path = null)
    {
        if (is_null($path))
        {
            $path = realpath(Path :: getInstance()->getTemporaryPath());
            $path = Filesystem :: create_unique_name($path . '/weblcms_document_download_' . $this->get_course_id());
            $category_folder_mapping[$parent_cat] = $path;
            Filesystem :: create_dir($path);
        }

        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(),
                ContentObjectPublicationCategory :: PROPERTY_COURSE),
            new StaticConditionVariable($this->get_course_id()));

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(),
                ContentObjectPublicationCategory :: PROPERTY_TOOL),
            new StaticConditionVariable($this->get_tool_id()));

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(),
                ContentObjectPublicationCategory :: PROPERTY_PARENT),
            new StaticConditionVariable($parent_cat));

        $condition = new AndCondition($conditions);

        $categories = \Ehb\Application\Avilarts\Storage\DataManager :: retrieves(
            ContentObjectPublicationCategory :: class_name(),
            new DataClassRetrievesParameters($condition));

        while ($category = $categories->next_result())
        {
            // Make safe name
            $safe_name = strtr(
                $category->get_name(),
                'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ',
                'aaaaaaaceeeeiiiidnoooooouuuuyaaaaaaceeeeiiiidnoooooouuuuyy');
            $safe_name = preg_replace('/[^0-9a-zA-Z\-\s\(\),]/', '_', $safe_name);

            $category_path = Filesystem :: create_unique_name($path . '/' . $safe_name);
            $category_folder_mapping[$category->get_id()] = $category_path;
            Filesystem :: create_dir($category_path);
            $this->create_folder_structure($category->get_id(), $category_folder_mapping, $category_path);
        }

        return $category_folder_mapping;
    }
}
