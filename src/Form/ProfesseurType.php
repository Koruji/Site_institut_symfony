<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Professeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            if ($options['hide_matricule']) {
                $builder->add('matricule', HiddenType::class, [
                    'data' => $options['matricule'],
                ]);
            } else {
                $builder->add('matricule');
            };

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'libelle',
                'required' => false,
            ])
        ;

        if ($options['hide_mdp']) {
            $builder->add('mdp', HiddenType::class, [
                'data' => $options['mdp_actuel'],
                'mapped' => false,
            ]);
        } else {
            $builder->add('mdp', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => true,
                //TODO : implémenter le fait que le mdp soit obligatoire
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
            'hide_mdp' => false,
            'hide_matricule' => false,
            'matricule' => null,
            'mdp_actuel' => null,
        ]);
    }
}
