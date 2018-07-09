<?php

namespace Core\NodeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('main', 'ckeditor')
            ->add('short_main')
            ->add('category', 'entity', array(
                'class' => 'NodeBundle:NodeCategory',
                'property' => 'title'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\NodeBundle\Entity\Node'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_nodebundle_node';
    }
}
