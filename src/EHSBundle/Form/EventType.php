<?php

namespace EHSBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('startDate', DateTimeType::class, array('label'=> 'Date et heure de début',
                'data'=> new \DateTime()
            ))
            ->add('endDate', DateTimeType::class, array('label'=> 'Date et heure de Fin',
                'data'=> new \DateTime()
            ))
            ->add('placeNumber', NumberType::class, array('label' => 'Nombre de place disponible'))
            ->add('addInfo', TextareaType::class, array('label'=> 'Information complémentaire', 'required'=> false))
//            ->add('archived')
//            ->add('images')
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
            ->setAction('new')
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
