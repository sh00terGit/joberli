<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSettingsType extends AbstractType
{

    private $formType;

    /**
     * @param string $formType
     */
    public function __construct ($formType = 'user') {
        $this->formType = $formType;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->formType == 'user') {
            $builder->add('purse');
            $builder->add('skype');
            $builder->add('about', 'textarea', array('label'=> 'О себе', 'required' => false));

            $builder->add('avatar', 'file', array(
                'data_class' => null,
                'required' => false,
                'label' => 'Фото'
            ));

            $builder->add('cover', 'file', array(
                'data_class' => null,
                'required' => false,
                'label' => 'Обложка'
            ));

            $builder->add('submit', 'submit', array('label' => 'Сохранить'));
        } else if ($this->formType == 'avatar') {
            $builder->add('avatar', 'file', array(
                'data_class' => null,
                'required' => false,
                'label' => 'Фото'
            ));
        } else if ($this->formType == 'cover') {
            $builder->add('cover', 'file', array(
                'data_class' => null,
                'required' => false,
                'label' => 'Обложка'
            ));
        }

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\UserBundle\Entity\UserData'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_userbundle_userdata';
    }
}
