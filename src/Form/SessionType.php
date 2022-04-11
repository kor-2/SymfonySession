<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('debut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('nb_places', NumberType::class)
            ->add('formateur', EntityType::class,[
                'class' => Formateur::class,
                'choice_label' => 'nom',
            ])
            ->add('formation', EntityType::class,[
                'class' => Formation::class,
                'choice_label' => 'nom',])
            ->add('Valider',SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
