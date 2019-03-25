<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom',TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('username',TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de Téléphone'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Confirmer mot de passe']
            ])
            ->add('isAdministrateur', CheckboxType::class, [
                'required'=>false,
                'label' => 'Droits Administrateur'
            ])
            ->add('site',  EntityType::class, [
                'class' => 'AppBundle:Site',
                'choice_label' => 'nom',
            ])
            ->add('urlPhoto', FileType::class, [
                'data_class'=> null,
                'required'=>false,
                'label' => 'Photo de profil'
            ])
            ->add('enregistrer', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Participant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_participant';
    }


}
