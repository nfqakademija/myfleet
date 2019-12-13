<?php

namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
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
            ->add('description', TextareaType::class, [
                'required' => true,
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
            'data_class' => Event::class,
        ]);
    }
}
