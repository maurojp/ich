<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\UsuarioRepository")
 * @UniqueEntity("usuario");
 * @UniqueEntity("email");
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Auditoria", mappedBy="usuario")
     */
    protected $auditorias;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30)
     * @Assert\NotBlank()
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoDocumento", type="string", columnDefinition="ENUM('DNI', 'LE', 'LC', 'PP')", length=50)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"DNI", "LE", "LC", "PP"})
     */
    private $tipoDocumento;

    /**
     * @var int
     *
     * @ORM\Column(name="nroDocumento", type="integer")
     */
    private $nroDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", columnDefinition="ENUM('M','H')", length=50)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"M", "H"})
     */
    private $genero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     * @Assert\NotBlank()
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="nacionalidad", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $nacionalidad;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
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
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     * @return Usuario
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set nroDocumento
     *
     * @param integer $nroDocumento
     * @return Usuario
     */
    public function setNroDocumento($nroDocumento)
    {
        $this->nroDocumento = $nroDocumento;

        return $this;
    }

    /**
     * Get nroDocumento
     *
     * @return integer 
     */
    public function getNroDocumento()
    {
        return $this->nroDocumento;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
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
     * Set genero
     *
     * @param string $genero
     * @return Usuario
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero
     *
     * @return string 
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Usuario
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set nacionalidad
     *
     * @param string $nacionalidad
     * @return Usuario
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string 
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }
    
    public function getFullname()
    {
        return $this->nombre." ".$this->apellido;
    }
    
    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->usuario;
    }
    
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        
    }
    
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->usuario,
            $this->password,
            ));
    }
    
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->usuario,
            $this->password,
            ) = unserialize($serialized);
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }
    
    public function isAccountNonLocked()
    {
        return true;
    }
    
    public function isCredentialsNonExpired()
    {
        return true;
    }
    
    public function isEnabled()
    {
        return true;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auditorias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add auditorias
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditorias
     * @return Usuario
     */
    public function addAuditoria(\ich\TestBundle\Entity\Auditoria $auditorias)
    {
        $this->auditorias[] = $auditorias;

        return $this;
    }

    /**
     * Remove auditorias
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditorias
     */
    public function removeAuditoria(\ich\TestBundle\Entity\Auditoria $auditorias)
    {
        $this->auditorias->removeElement($auditorias);
    }

    /**
     * Get auditorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuditorias()
    {
        return $this->auditorias;
    }
}
