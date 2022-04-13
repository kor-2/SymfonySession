<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('debut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('nb_places', NumberType::class)
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'choice_label' => 'nom',
            ])
            ->add('programmes', CollectionType::class, [ // la collection attend l'élément fourni dans le form
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // pas de référence à setProgramme dans l'entité Session
                // 'choice_label' => 'programme',
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom', ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'], ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
