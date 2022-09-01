<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Client" => "ROLE_CLIENT",
                    "Modérateur" => "ROLE_MODO"
                ], 
                "multiple" => true,
                "expanded" => true,
                "label" => "Accréditation"
            ])
            ->add('password')
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('civilite')
            ->add('ville')
            ->add('code_postal')
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
