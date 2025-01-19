<?php

namespace App\Form;

use App\Entity\Professeur;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('professeurs', ChoiceType::class, [
                'label' => 'Professeurs',
                'choices' => $options['professeurs'],
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function(Professeur $professeur) {
                    return $professeur->getNom() . ' ' . $professeur->getPrenom() . " - " . $professeur->getMatiere()->getLibelle();
                },
            ])
            ->add('stagiaires', ChoiceType::class, [
                'label' => 'Stagiaires',
                'choices' => $options['stagiaires'],
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function(Stagiaire $stagiaire) {
                    return $stagiaire->getNom() . ' ' . $stagiaire->getPrenom();
                },
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'professeurs' => [],
            'stagiaires' => [],
        ]);
    }
}

