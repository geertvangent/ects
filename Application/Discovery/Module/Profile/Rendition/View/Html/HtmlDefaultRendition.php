<?php
namespace Chamilo\Application\Discovery\Module\Profile\Rendition\View\Html;

use Chamilo\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Application\Discovery\Rendition\Format\HtmlRendition;
use Chamilo\Application\Discovery\Module\Profile\Profile;
use Chamilo\Application\Discovery\Module\Profile\Email;

class HtmlDefaultRendition extends HtmlRendition
{

    public function render()
    {
        $html = array();

        if ($this->get_profile() instanceof Profile)
        {
            $html[] = '<div class="content_object" style="background-image: url(' .
                 Theme :: get_image_path(__NAMESPACE__) . 'types/general.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('General');
            $html[] = '</div>';

            $html[] = '<div class="description">';

            $html[] = '<table style="width: 100%">';
            $html[] = '<tr>';

            $html[] = '<td style="padding-right: 10px;">';
            $table = new PropertiesTable($this->get_general_properties());
            // $table->setAttribute('style', 'margin-top: 1em; margin-bottom: 0; margin-right: 300px;');
            $html[] = $table->toHtml();
            $html[] = '</td>';

            if ($this->get_profile()->has_photo())
            {
                $html[] = '<td style="text-align: right; vertical-align: top; width: 150px;">';
                $html[] = '<img src="' . $this->get_profile()->get_photo()->get_source() .
                     '" style="width: 150px; border: 1px solid grey;"/>';
                $html[] = '</td>';
            }

            $html[] = '</tr>';
            $html[] = '</table>';

            $html[] = '</div>';
            $html[] = '</div>';

            if ($this->get_rendition_implementation()->is_allowed_to_contact())
            {
                if (count($this->get_profile()->get_communication()) > 1)
                {
                    $data = array();

                    foreach ($this->get_profile()->get_communication() as $communication)
                    {
                        $row = array();
                        $row[] = Translation :: get($communication->get_type_string());
                        $row[] = Translation :: get($communication->get_device_string());
                        $row[] = $communication->get_number();
                        $data[] = $row;
                    }

                    $html[] = '<div class="content_object" style="background-image: url(' .
                         Theme :: get_image_path(__NAMESPACE__) . 'types/communication_number.png);">';
                    $html[] = '<div class="title">';
                    $html[] = Translation :: get('CommunicationNumbers');
                    $html[] = '</div>';

                    $html[] = '<div class="description">';

                    $table = new SortableTable($data);
                    $table->set_header(0, Translation :: get('Type'), false);
                    $table->set_header(1, Translation :: get('Device'), false);
                    $table->set_header(2, Translation :: get('Number'), false);
                    $html[] = $table->toHTML();

                    $html[] = '</div>';
                    $html[] = '</div>';
                }
            }

            $number_of_emails = 0;

            foreach ($this->get_profile()->get_email() as $email)
            {
                if ($email->get_type() != Email :: TYPE_PRIVATE ||
                     $this->get_rendition_implementation()->is_allowed_to_contact())
                {
                    $number_of_emails ++;
                }
            }

            if ($number_of_emails > 1)
            {
                $data = array();

                foreach ($this->get_profile()->get_email() as $email)
                {
                    if ($email->get_type() != Email :: TYPE_PRIVATE ||
                         $this->get_rendition_implementation()->is_allowed_to_contact())
                    {
                        $row = array();
                        $row[] = Translation :: get($email->get_type_string());
                        $row[] = $email->get_address();
                        $data[] = $row;
                    }
                }

                $html[] = '<div class="content_object" style="background-image: url(' .
                     Theme :: get_image_path(__NAMESPACE__) . 'types/email_address.png);">';
                $html[] = '<div class="title">';
                $html[] = Translation :: get('EmailAddresses');
                $html[] = '</div>';

                $html[] = '<div class="description">';

                $table = new SortableTable($data);
                $table->set_header(0, Translation :: get('Type'), false);
                $table->set_header(1, Translation :: get('Address'), false);
                $html[] = $table->toHTML();

                $html[] = '</div>';
                $html[] = '</div>';
            }
        }
        else
        {
            $html[] = Display :: normal_message('NoData', true);
        }

        return implode("\n", $html);
    }

    /**
     *
     * @return multitype:string
     */
    public function get_general_properties()
    {
        $properties = array();
        $properties[Translation :: get('FirstName')] = $this->get_profile()->get_name()->get_first_names();
        $properties[Translation :: get('LastName')] = $this->get_profile()->get_name()->get_last_name();
        $properties[Translation :: get('Language')] = $this->get_profile()->get_language();

        foreach ($this->get_profile()->get_identification_code() as $identification_code)
        {
            $properties[Translation :: get($identification_code->get_type_string())] = $identification_code->get_code();
        }

        if ($this->get_rendition_implementation()->is_allowed_to_contact())
        {
            if (count($this->get_profile()->get_communication()) == 1)
            {
                $communication = $this->get_profile()->get_communication();
                $communication = $communication[0];
                $properties[Translation :: get('CommunicationNumber')] = $communication->get_number() . ' (' .
                     $communication->get_device_string() . ')';
            }
        }

        $number_of_emails = 0;

        foreach ($this->get_profile()->get_email() as $email)
        {
            if ($email->get_type() != Email :: TYPE_PRIVATE ||
                 $this->get_rendition_implementation()->is_allowed_to_contact())
            {
                $number_of_emails ++;
            }
        }

        if ($number_of_emails == 1)
        {
            foreach ($this->get_profile()->get_email() as $email)
            {
                if ($email->get_type() !== Email :: TYPE_PRIVATE ||
                     $this->get_rendition_implementation()->is_allowed_to_contact())
                {
                    break;
                }
            }

            $properties[Translation :: get('Email')] = $email->get_address() . ' (' . $email->get_type_string() . ')';
        }

        if (method_exists($this->get_rendition_implementation(), 'get_general_properties'))
        {
            $properties = array_merge($properties, $this->get_rendition_implementation()->get_general_properties());
        }

        return $properties;
    }
}
