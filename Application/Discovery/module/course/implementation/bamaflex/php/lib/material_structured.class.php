<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

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

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_id()
    {
        return $this->get_default_property(self :: PROPERTY_ID);
    }

    function set_id($id)
    {
        $this->set_default_property(self :: PROPERTY_ID, $id);
    }

    function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
    }

    function get_group()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP);
    }

    function set_group($group)
    {
        $this->set_default_property(self :: PROPERTY_GROUP, $group);
    }

    function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    function get_author()
    {
        return $this->get_default_property(self :: PROPERTY_AUTHOR);
    }

    function set_author($author)
    {
        $this->set_default_property(self :: PROPERTY_AUTHOR, $author);
    }

    function get_editor()
    {
        return $this->get_default_property(self :: PROPERTY_EDITOR);
    }

    function set_editor($editor)
    {
        $this->set_default_property(self :: PROPERTY_EDITOR, $editor);
    }

    function get_edition()
    {
        return $this->get_default_property(self :: PROPERTY_EDITION);
    }

    function set_edition($edition)
    {
        $this->set_default_property(self :: PROPERTY_EDITION, $edition);
    }

    function get_isbn()
    {
        return $this->get_default_property(self :: PROPERTY_ISBN);
    }

    function set_isbn($isbn)
    {
        $this->set_default_property(self :: PROPERTY_ISBN, $isbn);
    }

    function get_medium_id()
    {
        return $this->get_default_property(self :: PROPERTY_MEDIUM_ID);
    }

    function set_medium_id($medium_id)
    {
        $this->set_default_property(self :: PROPERTY_MEDIUM_ID, $medium_id);
    }

    function get_medium()
    {
        return $this->get_default_property(self :: PROPERTY_MEDIUM);
    }

    function set_medium($medium)
    {
        $this->set_default_property(self :: PROPERTY_MEDIUM, $medium);
    }

    function get_price()
    {
        return $this->get_default_property(self :: PROPERTY_PRICE);
    }

    function set_price($price)
    {
        $this->set_default_property(self :: PROPERTY_PRICE, $price);
    }

    function get_for_sale()
    {
        return $this->get_default_property(self :: PROPERTY_FOR_SALE);
    }

    function set_for_sale($for_sale)
    {
        $this->set_default_property(self :: PROPERTY_FOR_SALE, $for_sale);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
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
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * @return string
     */
    function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
?>