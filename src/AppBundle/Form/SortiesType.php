<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lieu;
use AppBundle\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class SortiesType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nom", TextType::class, [
                "label"=>"Nom :"
            ])
            ->add("dateDebut", DateType::class, [
                "label"=>"Date de début :"
            ])
            ->add("dateCloture", DateType::class, [
                "label"=>"Date de cloture :"
            ])
            ->add("nbInscriptionsMax", IntegerType::class, [
                "label"=>"Nombre d'inscriptions max :"
            ])
            ->add("duree", IntegerType::class, [
                "label"=>"Durée :"
            ])
            ->add("description", TextType::class,[
                "label"=>"Description & infos :"
            ])

            ->add("lieu", EntityType::class, [
                "class"=>Lieu::class
            ])

            ->add("Enregistrer", SubmitType::class);
            /*
            ->add("Publier", SubmitType::class)
            ->add("Annuler", SubmitType::class);
            */
    }
}




