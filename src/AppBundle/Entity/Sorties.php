<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sorties
 *
 * @ORM\Table(name="sorties", indexes={@ORM\Index(name="sorties_etats_fk", columns={"etats_no_etat"}), @ORM\Index(name="sorties_lieux_fk", columns={"lieux_no_lieu"}), @ORM\Index(name="sorties_participants_fk", columns={"organisateur"})})
 * @ORM\Entity
 */
class Sorties
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="datetime", nullable=false)
     */
    private $datedebut;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecloture", type="datetime", nullable=false)
     */
    private $datecloture;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbinscriptionsmax", type="integer", nullable=false)
     */
    private $nbinscriptionsmax;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptioninfos", type="string", length=500, nullable=true)
     */
    private $descriptioninfos;

    /**
     * @var integer
     *
     * @ORM\Column(name="etatsortie", type="integer", nullable=true)
     */
    private $etatsortie;

    /**
     * @var string
     *
     * @ORM\Column(name="urlPhoto", type="string", length=250, nullable=true)
     */
    private $urlphoto;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_sortie", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noSortie;

    /**
     * @var \AppBundle\Entity\Etats
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Etats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etats_no_etat", referencedColumnName="no_etat")
     * })
     */
    private $etatsNoEtat;

    /**
     * @var \AppBundle\Entity\Lieux
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lieux")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieux_no_lieu", referencedColumnName="no_lieu")
     * })
     */
    private $lieuxNoLieu;

    /**
     * @var \AppBundle\Entity\Participants
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Participants")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisateur", referencedColumnName="no_participant")
     * })
     */
    private $organisateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Participants", inversedBy="sortiesNoSortie")
     * @ORM\JoinTable(name="inscriptions",
     *   joinColumns={
     *     @ORM\JoinColumn(name="sorties_no_sortie", referencedColumnName="no_sortie")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="participants_no_participant", referencedColumnName="no_participant")
     *   }
     * )
     */
    private $participantsNoParticipant;

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Sorties
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * @param \DateTime $datedebut
     * @return Sorties
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param int $duree
     * @return Sorties
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatecloture()
    {
        return $this->datecloture;
    }

    /**
     * @param \DateTime $datecloture
     * @return Sorties
     */
    public function setDatecloture($datecloture)
    {
        $this->datecloture = $datecloture;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbinscriptionsmax()
    {
        return $this->nbinscriptionsmax;
    }

    /**
     * @param int $nbinscriptionsmax
     * @return Sorties
     */
    public function setNbinscriptionsmax($nbinscriptionsmax)
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptioninfos()
    {
        return $this->descriptioninfos;
    }

    /**
     * @param string $descriptioninfos
     * @return Sorties
     */
    public function setDescriptioninfos($descriptioninfos)
    {
        $this->descriptioninfos = $descriptioninfos;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtatsortie()
    {
        return $this->etatsortie;
    }

    /**
     * @param int $etatsortie
     * @return Sorties
     */
    public function setEtatsortie($etatsortie)
    {
        $this->etatsortie = $etatsortie;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlphoto()
    {
        return $this->urlphoto;
    }

    /**
     * @param string $urlphoto
     * @return Sorties
     */
    public function setUrlphoto($urlphoto)
    {
        $this->urlphoto = $urlphoto;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoSortie()
    {
        return $this->noSortie;
    }

    /**
     * @param int $noSortie
     * @return Sorties
     */
    public function setNoSortie($noSortie)
    {
        $this->noSortie = $noSortie;
        return $this;
    }

    /**
     * @return Etats
     */
    public function getEtatsNoEtat()
    {
        return $this->etatsNoEtat;
    }

    /**
     * @param Etats $etatsNoEtat
     * @return Sorties
     */
    public function setEtatsNoEtat($etatsNoEtat)
    {
        $this->etatsNoEtat = $etatsNoEtat;
        return $this;
    }

    /**
     * @return Lieux
     */
    public function getLieuxNoLieu()
    {
        return $this->lieuxNoLieu;
    }

    /**
     * @param Lieux $lieuxNoLieu
     * @return Sorties
     */
    public function setLieuxNoLieu($lieuxNoLieu)
    {
        $this->lieuxNoLieu = $lieuxNoLieu;
        return $this;
    }

    /**
     * @return Participants
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param Participants $organisateur
     * @return Sorties
     */
    public function setOrganisateur($organisateur)
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipantsNoParticipant()
    {
        return $this->participantsNoParticipant;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $participantsNoParticipant
     * @return Sorties
     */
    public function setParticipantsNoParticipant($participantsNoParticipant)
    {
        $this->participantsNoParticipant = $participantsNoParticipant;
        return $this;
    }

}

