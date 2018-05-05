<?php

namespace ExpositionBundle\Form;

use ExpositionBundle\Entity\Exposition;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',           TextType::class)
            ->add('description',    TextareaType::class, array(
                'attr' => array('rows' => '10'),
            ))
            ->add('start',          DateType::class)
            ->add('finish',         DateType::class)
            ->add('hostingMuseum',  EntityType::class, array(
                'class'         => 'MuseumsBundle\Entity\Museum',
                'choice_label'  => 'name',
            ))
            ->add('images',         CollectionType::class, array(
                'entry_type' => \ExpositionBundle\Form\ImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('save',          SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Exposition::class,
        ));
    }
}