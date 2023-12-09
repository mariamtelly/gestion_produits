<?php

namespace App\Form\Type;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Badge;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    
            ->add("nom", TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control mb-5'],
            ])
            ->add("description", TextareaType::class, [
                'label' => "Description du produit",
                'attr' => ['class' => 'form-control mb-5'],
            ])
            ->add("prix", NumberType::class, [
                'label' => "Prix du produit à l'unité",
                'attr' => ['class' => 'form-control mb-5'],
            ])
            ->add("quantiteEnStock", IntegerType::class, [
                'label' => "Quantité en stock",
                'attr' => ['class' => 'form-control mb-5'],
            ])
            ->add("categorie", EntityType::class, 
                    [ 
                        "label" => "Catégorie du produit",
                        "class" => Categorie::class,
                        "choice_label" => "nom", 
                        'attr' => ['class' => 'form-control mb-5'],
                    ])
            ->add("badge", EntityType::class, 
                [
                    "label" => "Badge",
                    "class" => Badge::class,
                    "choice_label" => "etiquette",
                    'attr' => ['class' => 'form-control mb-5'],
                ])
            ->add("imageName", FileType::class, [
                "label" => "Image",
                "mapped" => false,
                "required" => true,
                "constraints" => [
                    new File([
                        "mimeTypes" => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                            "image/webp"
                        ],
                        "mimeTypesMessage" => "Uploadez un format png, jpg, jpeg ou webp!"
                    ]),
                ],
                'attr' => ['class' => 'form-control mb-5'],
            ])
            ->add("save", SubmitType::class, [
                "label" => "Créer",
                'attr' => ['class' => 'btn btn-primary px-5 mb-5'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }

}