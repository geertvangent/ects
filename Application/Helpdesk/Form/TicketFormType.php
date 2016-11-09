<?php
namespace Ehb\Application\Helpdesk\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @package Ehb\Application\Helpdesk\Form
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class TicketFormType extends AbstractType
{
    const FIELD_SUBJECT = 'subject';

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_SUBJECT, TextType::class);
    }

    /**
     * Returns the name of this type.
     * 
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ticket';
    }
}