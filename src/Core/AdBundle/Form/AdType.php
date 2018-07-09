<?php

namespace Core\AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Core\AdBundle\Form\AdSubType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdType extends AbstractType
{
    private $user;

    public function __construct ($user) {
        $this->user = $user;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Название услуги'
            ))
            ->add('executDays', 'integer', array('label' => 'Дней на выполнения 1 заказа'))
            ->add('description', 'textarea', array(
                'label' => 'Описание услуги'
            ))
            ->add('instruction', 'textarea', array(
                'label' => 'Инструкция покупателю',
                'required' => false
            ))
            ->add('tags', 'tags_entry', array('attr' => array(
                    'class' => 'tags',
                    'id' => 'ad_edit_form_tags'
                ),
                'label' => 'Ключевые слова',
                'required' => false
            ))
            ->add('subAd', 'collection', array(
                'type' => new AdSubType($this->user),
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
            'data_class' => 'Core\AdBundle\Entity\Ad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_adbundle_ad';
    }
}
