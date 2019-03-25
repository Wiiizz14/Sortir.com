<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SiteRepository")
 */
class Site
{
    /**
     * @var int
     * @Groups({"sortieGroupe"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"sortieGroupe"})
     * @var string
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="site")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sortie", mappedBy="site")
     */
    private $sorties;


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
     * @return Site
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
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     * @return Site
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSorties()
    {
        return $this->sorties;
    }

    /**
     * @param mixed $sorties
     * @return Site
     */
    public function setSorties($sorties)
    {
        $this->sorties = $sorties;
        return $this;
    }

}

