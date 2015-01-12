<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

class MaterialStructured extends Material
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_ID = 'id';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP = 'group';
    const PROPERTY_TITLE = 'title';
    const PROPERTY_AUTHOR = 'author';
    const PROPERTY_EDITOR = 'editor';
    const PROPERTY_EDITION = 'edition';
    const PROPERTY_ISBN = 'isbn';
    const PROPERTY_MEDIUM_ID = 'medium_id';
    const PROPERTY_MEDIUM = 'medium';
    const PROPERTY_PRICE = 'price';
    const PROPERTY_FOR_SALE = 'for_sale';

    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function get_id()
    {
        return $this->get_default_property(self :: PROPERTY_ID);
    }

    public function set_id($id)
    {
        $this->set_default_property(self :: PROPERTY_ID, $id);
    }

    public function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    public function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
    }

    public function get_group()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP);
    }

    public function set_group($group)
    {
        $this->set_default_property(self :: PROPERTY_GROUP, $group);
    }

    public function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    public function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    public function get_author()
    {
        return $this->get_default_property(self :: PROPERTY_AUTHOR);
    }

    public function set_author($author)
    {
        $this->set_default_property(self :: PROPERTY_AUTHOR, $author);
    }

    public function get_editor()
    {
        return $this->get_default_property(self :: PROPERTY_EDITOR);
    }

    public function set_editor($editor)
    {
        $this->set_default_property(self :: PROPERTY_EDITOR, $editor);
    }

    public function get_edition()
    {
        return $this->get_default_property(self :: PROPERTY_EDITION);
    }

    public function set_edition($edition)
    {
        $this->set_default_property(self :: PROPERTY_EDITION, $edition);
    }

    public function get_isbn()
    {
        return $this->get_default_property(self :: PROPERTY_ISBN);
    }

    public function set_isbn($isbn)
    {
        $this->set_default_property(self :: PROPERTY_ISBN, $isbn);
    }

    public function get_medium_id()
    {
        return $this->get_default_property(self :: PROPERTY_MEDIUM_ID);
    }

    public function set_medium_id($medium_id)
    {
        $this->set_default_property(self :: PROPERTY_MEDIUM_ID, $medium_id);
    }

    public function get_medium()
    {
        return $this->get_default_property(self :: PROPERTY_MEDIUM);
    }

    public function set_medium($medium)
    {
        $this->set_default_property(self :: PROPERTY_MEDIUM, $medium);
    }

    public function get_price()
    {
        return $this->get_default_property(self :: PROPERTY_PRICE);
    }

    public function set_price($price)
    {
        $this->set_default_property(self :: PROPERTY_PRICE, $price);
    }

    public function get_price_string()
    {
        return $this->get_price() . ' &euro;';
    }

    public function get_for_sale()
    {
        return $this->get_default_property(self :: PROPERTY_FOR_SALE);
    }

    public function set_for_sale($for_sale)
    {
        $this->set_default_property(self :: PROPERTY_FOR_SALE, $for_sale);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP;
        $extended_property_names[] = self :: PROPERTY_TITLE;
        $extended_property_names[] = self :: PROPERTY_AUTHOR;
        $extended_property_names[] = self :: PROPERTY_EDITOR;
        $extended_property_names[] = self :: PROPERTY_EDITION;
        $extended_property_names[] = self :: PROPERTY_ISBN;
        $extended_property_names[] = self :: PROPERTY_MEDIUM_ID;
        $extended_property_names[] = self :: PROPERTY_MEDIUM;
        $extended_property_names[] = self :: PROPERTY_PRICE;
        $extended_property_names[] = self :: PROPERTY_FOR_SALE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
