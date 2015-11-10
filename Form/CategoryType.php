<?php

namespace Prh\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CategoryType.
 */
class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('shortDescription', 'textarea')
            ->add('description', 'textarea')
            ->add('metaDescription', 'textarea')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'prh_blog_categorytype';
    }
}
