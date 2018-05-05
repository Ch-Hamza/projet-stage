<?php

namespace MuseumsBundle\Form;

use MuseumsBundle\Entity\Museum;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MuseumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',        TextType::class)
            ->add('description_fr', TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('description_en', TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('description_it', TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('description_de', TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('horaire',     TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('price',       TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('place',       TextType::class)
            ->add('rue',     TextType::class)
            ->add('codePostal',     TextType::class)
            ->add('directions',  TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('phone',     TextType::class)
            ->add('fax',     TextType::class)
            ->add('email',     TextType::class)
            ->add('website',     TextType::class)

            ->add('criteres', EntityType::class, array(
                'class'         => 'MuseumsBundle\Entity\Critere',
                'choice_label'  => 'title_en',
                'multiple'      => true,
                'attr' => array('size' => 3)
            ))

            ->add('categories', EntityType::class, array(
                'class'         => 'MuseumsBundle\Entity\Category',
                'choice_label'  => 'title_en',
                'multiple'      => true,
                'attr' => array('size' => 3)
            ))

            ->add('canton', EntityType::class, array(
                'class' => 'MuseumsBundle\Entity\Canton',
                'choice_label'  => 'title',
            ))

            ->add('location', LocationType::class)

            ->add('images', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))

            ->add('save',      SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Museum::class,
        ));
    }
}