<?php

namespace EHSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label'=> 'Adresse e-mail'))
            ->add('newsletter', CheckboxType::class, array('label'=> 'recevoir la newsLetter? ', 'required'=>false))
            ->add('lastname', TextType::class, array('label' => 'Nom ou Raison Sociale'))
            ->add('firstname' ,TextType::class, array('label' => 'Prénom (Facultatif si Raison Sociale'))
            ->add('address', TextType::class, array('label' => 'Adresse' ))
            ->add('zipCode', IntegerType::class, array('label' => 'Code Postal'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('phone' , TextType::class, array('label' => 'Téléphone'))
            ->add('birth', BirthdayType::class, array('format'=> 'dd-MM-yyyy','label' => 'Date de naissance'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EHSBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ehsbundle_user';
    }


}
