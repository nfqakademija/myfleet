<?php

namespace App\Form\Type;

use App\Entity\Vehicle;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VehicleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('plateNumber', TextType::class, [
                'required' => true,
            ])
            ->add('vin', TextType::class, [
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Automobilis' => 'car',
                    'Vilkikas' => 'truck',
                    'Puspriekabė' => 'semitrailer',
                    'Mikroautobusas' => 'van',
                ],
            ])
            ->add('additionalInformation', TextareaType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Išsaugoti'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
