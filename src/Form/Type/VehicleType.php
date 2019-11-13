<?php

namespace App\Form\Type;

use App\Entity\Vehicle;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ])
            ->add('registrationPlateNumber', TextType::class, [
                'required' => true,
            ])
            ->add('vinCode', TextType::class, [
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Automobilis' => 'car',
                    'Vilkikas' => 'truck',
                    'Puspriekabė' => 'semitrailer',
                    'Mikroautobusas' => 'van',
                ],
            ])
            ->add('additionalInformation', TextType::class)
            ->add('save', SubmitType::class)
        ;

        $builder->add('events', CollectionType::class, [
            'entry_type' => EventType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
        ]);

        $builder->add('tasks', CollectionType::class, [
            'entry_type' => TaskType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
        ]);

        $builder->add('expenseEntries', CollectionType::class, [
            'entry_type' => ExpenseEntryType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
        ]);
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
