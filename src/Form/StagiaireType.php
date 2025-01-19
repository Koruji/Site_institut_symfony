<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('email')
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
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
            'hide_mdp' => false,
            'mdp_actuel' => null,
        ]);
    }
}
