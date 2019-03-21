<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lieu;
use AppBundle\Entity\Site;
use AppBundle\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SortiesType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

/*
    nom                           VARCHAR(30) NOT NULL,
    datedebut                     DATETIME NOT NULL,
    duree                         INTEGER,
    datecloture                   DATETIME NOT NULL,
    nbinscriptionsmax             INTEGER NOT NULL,
    descriptioninfos              VARCHAR(500),
    etatsortie                    INTEGER,
	urlPhoto                      VARCHAR (250)
 */

        $builder
            ->add("nom", TextType::class)
            ->add("datedebut", DateType::class)
            ->add("datecloture", DateType::class)
            ->add("nbinscriptionsmax", IntegerType::class)
            ->add("duree", IntegerType::class)
            ->add("description", TextType::class)
            ->add("site", EntityType::class,[
                "class"=> Site::class,
                "choice_label"=> "nom"
            ])
//            ->add("ville", EntityType::class,[
//                "class"=> Ville::class,
//                "choice_label"=> "nom"
//            ])
//            ->add("lieu", EntityType::class,[
//                "class"=> Lieu::class,
//                "choice_label"=> "nom"
//            ])
//            ->add("rue", EntityType::class,[
//                "class"=> Lieu::class,
//                "choice_label"=> "rue"
//            ])
//            ->add("codePostal", EntityType::class,[
//                "class"=> Ville::class,
//                "choice_label"=> "codepostal"
//            ])

            ->add("Enregistrer", SubmitType::class)
            ->add("Publier la sortie", SubmitType::class)
            ->add("Annuler", SubmitType::class);
    }
}




