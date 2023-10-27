<?php

namespace App\Form\Type;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    
            ->add("nom", TextType::class)
            ->add("description", TextType::class)
            ->add("prix", NumberType::class)
            ->add("quantiteEnStock", IntegerType::class)
            ->add("categorie", EntityType::class, 
                    [ 
                        "class" => Categorie::class,
                        "choice_label" => "nom", 
                    ])
            ->add("dateCreation", DateTimeType::class)
            ->add("dateMiseAJour", DateTimeType::class)
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