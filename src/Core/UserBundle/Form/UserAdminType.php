<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserAdminType extends AbstractType
{
    private $roles;

    public function __construct($roles) {
        $this->roles = $roles;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('roles', 'choice', array(
                'choices' => $this->flattenArray($this->roles),
                'multiple' => true
            ))
            ->add('enabled', 'checkbox',
                array(
                    'required' => false
                ))
            ->add('locked', 'checkbox',
                array(
                    'required' => false
                )
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user_bundle_user_admin_type';
    }

    private function flattenArray(array $data)
    {
        $returnData = array();

        foreach($data as $key => $value)
        {
            $tempValue = str_replace("ROLE_", '', $key);
            $tempValue = ucwords(strtolower(str_replace("_", ' ', $tempValue)));
            $returnData[$key] = $tempValue;
        }
        return $returnData;
    }
}