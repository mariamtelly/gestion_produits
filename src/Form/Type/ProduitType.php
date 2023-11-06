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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    
            ->add("nom", TextType::class, [
                'label' => 'Nom du produit',
            ])
            ->add("description", TextType::class)
            ->add("prix", NumberType::class)
            ->add("quantiteEnStock", IntegerType::class)
            ->add("categorie", EntityType::class, 
                    [ 
                        "class" => Categorie::class,
                        "choice_label" => "nom", 
                    ])
            ->add("badge", EntityType::class, 
                [
                    "class" => Badge::class,
                    "choice_label" => "etiquette",
                ])
            ->add("Ajouter", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }

}