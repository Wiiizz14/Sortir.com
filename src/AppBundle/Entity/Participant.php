<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @UniqueEntity(fields={"username"}, message="Pseudo déjà existant.")
 * @UniqueEntity(fields={"email"}, message="Email déjà existant.")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipantRepository")
 */
class Participant implements UserInterface
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
     * @var string
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Vous devez remplir ce champs.")
     * @Assert\Type(type="string", message="Le nom doit être une chaîne de caractères.")
     * @Assert\Length(max="50", maxMessage="Votre nom ne doit pas excéder 50 caractères.")
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Vous devez remplir ce champs.")
     * @Assert\Type(type="string", message="Le prénom doit être une chaîne de caractères.")
     * @Assert\Length(max="30", maxMessage="Votre prénom ne doit pas excéder 30 caractères.")
     *
     * @ORM\Column(name="prenom", type="string", length=30)
     */
    private $prenom;

    /**
     * @var string
     *
     * @Groups({"sortieGroupe"})
     *
     * @Assert\NotBlank(message="Vous devez remplir ce champs.")
     * @Assert\Type(type="string", message="Le pseudo doit être une chaîne de caractères.")
     * @Assert\Length(max="30", maxMessage="Votre pseudo ne doit pas excéder 50 caractères.")
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="'#^0[6-7]{1}\d{8}$#'", message="Votre numéro de téléphone doit comporter 10 chiffres sans espaces ni points.")
     *
     * @ORM\Column(name="telephone", type="integer", length=10, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Vous devez remplir ce champs.")
     * @Assert\Email(message="Le format de l'email '{{ value }}' n'est pas conforme.")
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Length(max="50", maxMessage="Le mot de passe doit contenir entre 8 et 50 caractères.",
     *     min="8", minMessage="Le mot de passe doit contenir entre 8 et 50 caractères.")
     *
     * @ORM\Column(name="password", type="string", length=50)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_administrateur", type="boolean")
     */
    private $isAdministrateur;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_actif", type="boolean")
     */
    private $isActif;

    /**
     * @var
     * @Assert\Image(maxSize="4M", minWidth="100", minHeight="100")
     * @ORM\Column(name="url_photo", type="string", length=255, nullable=true)
     */
    private $urlPhoto;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Sortie", inversedBy="participants", cascade={"persist"})
     */
    private $sorties;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sortie", mappedBy="organisateur", cascade={"persist"})
     */
    private $organisations;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Site", inversedBy="participants", cascade={"persist"})
     */
    private $site;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

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
     * @return Participant
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Participant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Participant
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Participant
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Participant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Participant
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isAdministrateur
     *
     * @param $isAdministrateur
     * @return Participant
     */
    public function setIsAdministrateur($isAdministrateur)
    {
        $this->isAdministrateur = $isAdministrateur;

        return $this;
    }

    /**
     * Get isAdministrateur
     *
     * @return bool
     */
    public function getIsAdministrateur()
    {
        return $this->isAdministrateur;
    }

    /**
     * Set isActif
     *
     * @param boolean $isActif
     *
     * @return Participant
     */
    public function setIsActif($isActif)
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * Get isActif
     *
     * @return bool
     */
    public function getIsActif()
    {
        return $this->isActif;
    }

    /**
     * Set urlPhoto
     *
     * @param string $urlPhoto
     *
     * @return Participant
     */
    public function setUrlPhoto($urlPhoto)
    {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    /**
     * Get urlPhoto
     *
     * @return string
     */
    public function getUrlPhoto()
    {
        return $this->urlPhoto;
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
     * @return Participant
     */
    public function setSorties($sorties)
    {
        $this->sorties = $sorties;
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
     * @return Participant
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return Participant
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return Participant
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getOrganisations()
    {
        return $this->organisations;
    }

    /**
     * @param mixed $organisations
     * @return Participant
     */
    public function setOrganisations($organisations)
    {
        $this->organisations = $organisations;
        return $this;
    }


}

