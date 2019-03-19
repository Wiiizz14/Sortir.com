<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participants
 *
 * @ORM\Table(name="participants", uniqueConstraints={@ORM\UniqueConstraint(name="participants_pseudo_uk", columns={"pseudo"})})
 * @ORM\Entity
 */
class Participants
{
    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=30, nullable=false)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=false)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="administrateur", type="boolean", nullable=false)
     */
    private $administrateur;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="sites_no_site", type="integer", nullable=false)
     */
    private $sitesNoSite;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_participant", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noParticipant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Sorties", mappedBy="participantsNoParticipant")
     */
    private $sortiesNoSortie;

    /**
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     * @return Participants
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Participants
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return Participants
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return Participants
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Participants
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Participants
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * @param bool $administrateur
     * @return Participants
     */
    public function setAdministrateur($administrateur)
    {
        $this->administrateur = $administrateur;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     * @return Participants
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return int
     */
    public function getSitesNoSite()
    {
        return $this->sitesNoSite;
    }

    /**
     * @param int $sitesNoSite
     * @return Participants
     */
    public function setSitesNoSite($sitesNoSite)
    {
        $this->sitesNoSite = $sitesNoSite;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoParticipant()
    {
        return $this->noParticipant;
    }

    /**
     * @param int $noParticipant
     * @return Participants
     */
    public function setNoParticipant($noParticipant)
    {
        $this->noParticipant = $noParticipant;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSortiesNoSortie()
    {
        return $this->sortiesNoSortie;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $sortiesNoSortie
     * @return Participants
     */
    public function setSortiesNoSortie($sortiesNoSortie)
    {
        $this->sortiesNoSortie = $sortiesNoSortie;
        return $this;
    }


}

