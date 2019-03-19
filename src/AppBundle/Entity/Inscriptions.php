<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscriptions
 *
 * @ORM\Table(name="inscriptions", indexes={@ORM\Index(name="inscriptions_participants_fk", columns={"participants_no_participant"})})
 * @ORM\Entity
 */
class Inscriptions
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime", nullable=false)
     */
    private $dateInscription;

    /**
     * @var integer
     *
     * @ORM\Column(name="sorties_no_sortie", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sortiesNoSortie;

    /**
     * @var integer
     *
     * @ORM\Column(name="participants_no_participant", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $participantsNoParticipant;

    /**
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * @param \DateTime $dateInscription
     * @return Inscriptions
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortiesNoSortie()
    {
        return $this->sortiesNoSortie;
    }

    /**
     * @param int $sortiesNoSortie
     * @return Inscriptions
     */
    public function setSortiesNoSortie($sortiesNoSortie)
    {
        $this->sortiesNoSortie = $sortiesNoSortie;
        return $this;
    }

    /**
     * @return int
     */
    public function getParticipantsNoParticipant()
    {
        return $this->participantsNoParticipant;
    }

    /**
     * @param int $participantsNoParticipant
     * @return Inscriptions
     */
    public function setParticipantsNoParticipant($participantsNoParticipant)
    {
        $this->participantsNoParticipant = $participantsNoParticipant;
        return $this;
    }

}

