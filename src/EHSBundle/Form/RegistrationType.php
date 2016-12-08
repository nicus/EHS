<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 06/12/2016
 * Time: 12:20
 */

namespace EHSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label'=> 'Adresse e-mail'))
            ->add('lastname', TextType::class, array('label' => 'Nom ou Raison Sociale'))
            ->add('firstname' ,TextType::class, array('label' => 'Prénom (Facultatif si Raison Sociale'))
            ->add('address', TextType::class, array('label' => 'Adresse' ))
            ->add('zipCode', IntegerType::class, array('label' => 'Code Postal'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('phone' , TextType::class, array('label' => 'Téléphone'))
            ->add('birth', BirthdayType::class, array('format'=> 'dd-MM-yyyy','label' => 'Date de naissance'))
            ->add('newsletter')
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getLastname()
    {
        return $this->getBlockPrefix();
    }

    public function getFirstname()
    {
        return $this->getBlockPrefix();
    }

    public function getAddress()
    {
        return $this->getBlockPrefix();
    }

    public function getZipCode()
    {
        return $this->getBlockPrefix();
    }

    public function getCity()
    {
        return $this->getBlockPrefix();
    }

    public function getPhone()
    {
        return $this->getBlockPrefix();
    }

    public function getBirth()
    {
        return $this->getBlockPrefix();
    }
}