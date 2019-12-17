<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\ExpenseEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseEntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 10000,
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 250,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Išsaugoti',
            ]);

        $builder->get('amount')
            ->addModelTransformer(new CallbackTransformer(
                function ($amountAsInteger) {
                    return $amountAsInteger / 100;
                },
                function ($amountAsInteger) {
                    return $amountAsInteger * 100;
                }
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExpenseEntry::class,
        ]);
    }
}
