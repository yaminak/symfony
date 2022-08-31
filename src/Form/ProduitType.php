<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class, [
                "label" => "référence n°",
                "constraints" =>[
                    new Length([
                        "max" => 20,
                        "maxMessage" => "La référence ne doit pas dépasser 20 caractères",
                        "min" => 5,
                        "minMessage" => "La référence doit comporter au moins 5 caractères",

                    ])
                ]
            ])
            ->add('categorie', TextType::class, [
                "label" => "categorie"
            ])
            ->add('titre')
            ->add('description')
            ->add('photo', FileType::class, [
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => ["image/gif", "image/jpeg","image/png"],
                        "mimeTypesMessage" => "Les formats autorisés sont gif, jpeg, png",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Le fichier ne peut pas peser plus de 2Mo"

                    ])
                ]
            ])
            ->add('prix')
            ->add('stock')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
