<?php

namespace Core\AdBundle\Form;

use Core\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdSubType extends AbstractType
{

    private $user;
    private $prices = [
      0 => [
          7 => '$7',
          13 => '$13',
          21 => '$21',
      ],
      1 => [
          7 => '$7',
          13 => '$13',
          21 => '$21',
          35 => '$35',
      ],
      2 => [
          7 => '$7',
          13 => '$13',
          21 => '$21',
          35 => '$35',
          55 => '$55',
      ],
      3 => [
          7 => '$7',
          13 => '$13',
          21 => '$21',
          35 => '$35',
          55 => '$55',
          101 => '$101',
      ],
      4 => [
          7 => '$7',
          13 => '$13',
          21 => '$21',
          35 => '$35',
          55 => '$55',
          101 => '$101',
      ],
    ];


    /**
     * @param $user User
     */
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
                'label' => 'Наименование',
                'required' => true
            ))
            ->add('executDays', 'integer', array(
                'label' => 'Дни на выполнение'
            ))
            ->add('price', 'choice', array(
                'label' => 'Цена',
                'choices' => $this->prices[$this->user->getLevel()]
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\AdBundle\Entity\AdSub',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_adbundle_ad_sub';
    }
}
