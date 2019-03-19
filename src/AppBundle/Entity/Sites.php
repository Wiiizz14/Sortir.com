<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sites
 *
 * @ORM\Table(name="sites")
 * @ORM\Entity
 */
class Sites
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom_site", type="string", length=30, nullable=false)
     */
    private $nomSite;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_site", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noSite;

    /**
     * @return string
     */
    public function getNomSite()
    {
        return $this->nomSite;
    }

    /**
     * @param string $nomSite
     * @return Sites
     */
    public function setNomSite($nomSite)
    {
        $this->nomSite = $nomSite;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoSite()
    {
        return $this->noSite;
    }

    /**
     * @param int $noSite
     * @return Sites
     */
    public function setNoSite($noSite)
    {
        $this->noSite = $noSite;
        return $this;
    }
}

