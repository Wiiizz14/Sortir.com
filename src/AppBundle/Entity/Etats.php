<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etats
 *
 * @ORM\Table(name="etats")
 * @ORM\Entity
 */
class Etats
{
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=30, nullable=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_etat", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noEtat;

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Etats
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoEtat()
    {
        return $this->noEtat;
    }

    /**
     * @param int $noEtat
     * @return Etats
     */
    public function setNoEtat($noEtat)
    {
        $this->noEtat = $noEtat;
        return $this;
    }

}

