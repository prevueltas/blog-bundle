<?php

namespace Prh\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SubscriberType.
 */
class SubscriberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', 'email')
            ->add('state', 'choice', [
                'choices' => [
                    '1' => 'Subscribed',
                    '0' => 'Unsubscribed'
                ]
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'prh_blog_subscribertype';
    }
}
