<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Lieu
 *
 * @ORM\Table(name="lieu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LieuRepository")
 */
class Lieu
{
    /**
     * @var int
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     * @ORM\Column(name="rue", type="string", length=80, nullable=true)
     */
    private $rue;

    /**
     * @var float
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sortie", mappedBy="lieu")
     */
    private $sorties;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ville", inversedBy="lieux")
     * @Groups({"sortieGroupe", "lieuxGroupe"})
     */
    private $ville;

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
     * @return Lieu
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
     * Set rue
     *
     * @param string $rue
     *
     * @return Lieu
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Lieu
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Lieu
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
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
     * @return Lieu
     */
    public function setSorties($sorties)
    {
        $this->sorties = $sorties;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     * @return Lieu
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}

