<?php

namespace App\Form\Type;

use App\Entity\Vehicle;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', TextType::class, [
                'required' => true,
            ])
            ->add('model', TextType::class, [
                'required' => true,
            ])
            ->add('firstRegistration', DateType::class, [
                'required' => true,
            ])
            ->add('registrationPlateNumber', TextType::class, [
                'required' => true,
            ])
            ->add('vinCode', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 17, 'max' => 17])],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Automobilis' => 'car',
                    'Vilkikas' => 'truck',
                    'Puspriekabė' => 'semitrailer',
                    'Mikroautobusas' => 'van',
                ],
                'placeholder' => 'Tipas',

            ])
            ->add('additionalInformation', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
