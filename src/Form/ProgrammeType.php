<?php

namespace App\Form;

use App\Entity\ModuleFormation;
use App\Entity\Programme;
use App\Form\DataTransformer\SessionTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    private $transformer;

    public function __construct(SessionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_jour_module', NumberType::class, [
                'label' => 'durée (jours)',
                'attr' => ['min' => '1/2', 'max' => '15'],
            ])
            ->add('session', HiddenType::class)
            ->add('moduleFormation', EntityType::class, [
                'label' => 'module',
                'class' => ModuleFormation::class,
                'choice_label' => 'nom',
            ])
        ;
        $builder
            ->get('session')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
