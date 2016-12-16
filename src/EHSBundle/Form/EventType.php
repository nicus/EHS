<?php

namespace EHSBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label'=> 'Titre'))
            ->add('presentation', TextareaType::class, array('label'=> 'Présentation'))
            ->add('placeNumber', NumberType::class, array('label' => 'Nombre de place disponible'))
            ->add('addInfo', TextareaType::class, array('label'=> 'Information complémentaire', 'required'=> false))
            ->add('images', EntityType::class, array('label'=>'Ajouter des images', 'class'=> 'EHSBundle\Entity\Image',
                'choice_label' => 'getOriginalName',
                'multiple'=>true,'required'=>false))
            ->add('newImages', new ImageType(), array(
                'label'=>'Ajouter des images(maintenir la touche "Ctrl" pour en ajouter plusieurs)',
                'required'=>false))
            ->add('tags', EntityType::class, array('label'=>'tag à ajouter',
                'class'=>'EHSBundle\Entity\Tag',
                'choice_label'=>'tag',
                'multiple'=>true,
            ))
//            ->add('inscriptions')
            ->add('appointment', EntityType::class, array('label'=>'Adresse de l\'évènement',
                'class' =>'EHSBundle\Entity\Appointment',
                'choice_label'=>'address'
            ))
            ->add('newAdress', new AppointmentType(), array('label'=> 'Ajouter une nouvelle adresse', 'required'=>false))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EHSBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ehsbundle_event';
    }


}
