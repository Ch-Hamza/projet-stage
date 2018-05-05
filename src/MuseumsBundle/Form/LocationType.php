<?php

namespace MuseumsBundle\Form;

use MuseumsBundle\Entity\Location;
use MuseumsBundle\Entity\MuseumImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longitude', TextType::class, array(
                'required'          => false
            ))
            ->add('lattitude', TextType::class, array(
                'required'          => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Location::class,
        ));
    }
}