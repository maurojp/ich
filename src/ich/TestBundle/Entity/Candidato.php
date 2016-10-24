<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Candidato
 *
 * @ORM\Table(name="candidato")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CandidatoRepository")
 * @UniqueEntity("nroCandidato")
 */
class Candidato
{
    /**
     * @ORM\OneToMany(targetEntity="Cuestionario", mappedBy="candidato")
     */
    protected $cuestionarios;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nroCandidato", type="integer", unique=true)
     */
    private $nroCandidato;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=60)
     */
    private $apellido;

    /**
     * @var int
     *
     * @ORM\Column(name="tipoDocumento", type="integer")
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
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="genero", type="integer")
     */
    private $genero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="nacionalidad", type="string", length=50)
     */
    private $nacionalidad;

    /**
     * @var string
     *
     * @ORM\Column(name="escolaridad", type="string", length=50)
     */
    private $escolaridad;

    /**
     * @var bool
     *
     * @ORM\Column(name="eliminado", type="boolean")
     */
    private $eliminado;

    public function __construct()
    {
        $this->cuestionarios = new ArrayCollection();
    }

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
     * Set nroCandidato
     *
     * @param integer $nroCandidato
     * @return Candidato
     */
    public function setNroCandidato($nroCandidato)
    {
        $this->nroCandidato = $nroCandidato;

        return $this;
    }

    /**
     * Get nroCandidato
     *
     * @return integer 
     */
    public function getNroCandidato()
    {
        return $this->nroCandidato;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Candidato
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
     * @return Candidato
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
     * Set tipoDocumento
     *
     * @param integer $tipoDocumento
     * @return Candidato
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return integer 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set nroDocumento
     *
     * @param integer $nroDocumento
     * @return Candidato
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
     * @return Candidato
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
     * @param integer $genero
     * @return Candidato
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero
     *
     * @return integer 
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Candidato
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
     * @return Candidato
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

    /**
     * Set escolaridad
     *
     * @param string $escolaridad
     * @return Candidato
     */
    public function setEscolaridad($escolaridad)
    {
        $this->escolaridad = $escolaridad;

        return $this;
    }

    /**
     * Get escolaridad
     *
     * @return string 
     */
    public function getEscolaridad()
    {
        return $this->escolaridad;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     * @return Candidato
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado
     *
     * @return boolean 
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Add cuestionarios
     *
     * @param \ich\TestBundle\Entity\Cuestionario $cuestionarios
     * @return Candidato
     */
    public function addCuestionario(\ich\TestBundle\Entity\Cuestionario $cuestionarios)
    {
        $this->cuestionarios[] = $cuestionarios;

        return $this;
    }

    /**
     * Remove cuestionarios
     *
     * @param \ich\TestBundle\Entity\Cuestionario $cuestionarios
     */
    public function removeCuestionario(\ich\TestBundle\Entity\Cuestionario $cuestionarios)
    {
        $this->cuestionarios->removeElement($cuestionarios);
    }

    /**
     * Get cuestionarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCuestionarios()
    {
        return $this->cuestionarios;
    }
}
