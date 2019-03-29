<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sortie
 *
 * @ORM\Table(name="sortie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SortieRepository")
 */
class Sortie
{

    /**
     * @var int
     *
     * @Groups({"sortieGroupe"})
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Type(type="string", message="Chaine de caractères uniquement")
     *
     * @ORM\Column(name="nom", type="string", length=80)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\GreaterThan(propertyPath="dateCloture" , message="La date de début doit-être postérieure à la date de clôture")
     * @Assert\GreaterThan("today", message="La sortie ne peut pas se réaliser dans le passé !")
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var int
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\Type(type="integer", message="Valeur numérique uniquement")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Range(min=0, minMessage="La durée ne peut pas être négative !")
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree;

    /**
     * @var \DateTime
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\LessThan(propertyPath="dateDebut", message="La date de clôture doit-être antérieure à la date de début")
     *
     * @ORM\Column(name="date_cloture", type="datetime")
     */
    private $dateCloture;

    /**
     * @var int
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Range(min=0, minMessage="Le nombre de participants ne peut pas être négatif")
     *
     * @ORM\Column(name="nb_inscriptions_max", type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @var string
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @Groups({"sortieGroupe"})
     * @ORM\Column(name="is_etat_sortie", type="boolean")
     */
    private $isEtatSortie;

    /**
     * @var
     * @Groups({"sortieGroupe"})
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Participant", inversedBy="organisations", cascade={"persist"})
     */
    private $organisateur;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="sorties")
     * @Groups({"sortieGroupe"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Etat", inversedBy="sorties", cascade={"persist"})
     * @Groups({"sortieGroupe"})
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lieu", inversedBy="sorties", cascade={"persist"})
     * @Groups({"sortieGroupe"})
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Site", inversedBy="sorties", cascade={"persist"})
     * @Groups({"sortieGroupe"})
     */
    private $site;

    /**
     * Permet d'initialiser la date de clôture des inscriptions à aujourd'hui
     * et celle de début de la sortie à J + 1.
     *
     * Sortie constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateCloture = new \DateTime('now');
        $this->dateDebut = new \DateTime('now +1 day');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Sortie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param $dateDebut
     * @return Sortie
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Sortie
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set dateCloture
     *
     * @param $dateCloture
     * @return Sortie
     */
    public function setDateCloture($dateCloture)
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    /**
     * Get dateCloture
     *
     * @return \DateTime
     */
    public function getDateCloture()
    {
        return $this->dateCloture;
    }

    /**
     * Set nbInscriptionsMax
     *
     * @param integer $nbInscriptionsMax
     *
     * @return Sortie
     */
    public function setNbInscriptionsMax($nbInscriptionsMax)
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    /**
     * Get nbInscriptionsMax
     *
     * @return int
     */
    public function getNbInscriptionsMax()
    {
        return $this->nbInscriptionsMax;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Sortie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function getIsEtatSortie()
    {
        return $this->isEtatSortie;
    }

    /**
     * @param $isEtatSortie
     * @return Sortie
     */
    public function setIsEtatSortie($isEtatSortie)
    {
        $this->isEtatSortie = $isEtatSortie;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     * @return Sortie
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     * @return Sortie
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     * @return Sortie
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     * @return Sortie
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param mixed $organisateur
     * @return Sortie
     */
    public function setOrganisateur($organisateur)
    {
        $this->organisateur = $organisateur;
        return $this;
    }

}

