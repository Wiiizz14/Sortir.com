<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Villes
 *
 * @ORM\Table(name="villes")
 * @ORM\Entity
 */
class Villes
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom_ville", type="string", length=30, nullable=false)
     */
    private $nomVille;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=10, nullable=false)
     */
    private $codePostal;

    /**
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     * @return Villes
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoVille()
    {
        return $this->noVille;
    }

    /**
     * @param int $noVille
     * @return Villes
     */
    public function setNoVille($noVille)
    {
        $this->noVille = $noVille;
        return $this;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="no_ville", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noVille;


    /**
     * @return string
     */
    public function getNomVille()
    {
        return $this->nomVille;
    }

    /**
     * @param string $nomVille
     * @return Villes
     */
    public function setNomVille($nomVille)
    {
        $this->nomVille = $nomVille;
        return $this;
    }




}

