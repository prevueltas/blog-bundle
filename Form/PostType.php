<?php

namespace Prh\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PostType.
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('date', 'date', [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text'
            ])
            ->add('content', 'textarea')
            ->add('metaDescription', 'textarea')
            ->add('note', 'textarea', [
                'required' => false
            ])
            ->add('state', 'choice', [
                'choices' => [
                    '0' => 'Draft',
                    '1' => 'Published'
                ]
            ])
            ->add('featured', 'choice', [
                'choices' => [
                    '0' => 'Standard',
                    '1' => 'Featured'
                ]
            ])
            ->add('categories', 'entity', [
                'class' => 'PrhBlogBundle:Category',
                'multiple' => true
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'prh_blog_posttype';
    }
}
