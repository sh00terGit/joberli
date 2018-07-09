<?php

namespace Core\AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('public', 'checkbox')
            ->add('sort', 'integer')
            ->add('cover', 'file', array(
                'data_class' => null,
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\AdBundle\Entity\AdCategory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_adbundle_adcategory';
    }
}
