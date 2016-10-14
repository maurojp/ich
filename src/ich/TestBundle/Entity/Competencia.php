<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Competencia
 *
 * @ORM\Table(name="competencia")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CompetenciaRepository")
 * @UniqueEntity("codigo")
 */
class Competencia
{
    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Puesto_Competencia", mappedBy="competencia")
     */
    protected $puestos;
	
     /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Auditoria", inversedBy="competencia") 
     * @ORM\JoinColumn(name="eliminado",referencedColumnName="id", nullable=true)
     */
    protected $auditoria;
    
    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Factor", mappedBy="competencia")
     */
    protected $factores;

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
     * @Assert\NotBlank()
     * @ORM\Column(name="codigo", type="string", length=10, unique=true)
     */
    private $codigo;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="descripcion", type="string", length=150)
     */
    private $descripcion;

   
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
     * Set codigo
     *
     * @param string $codigo
     * @return competencia
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return competencia
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return competencia
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

 


    /**
     * Set auditoria
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditoria
     * @return Competencia
     */
    public function setAuditoria(\ich\TestBundle\Entity\Auditoria $auditoria = null)
    {
        $this->auditoria = $auditoria;

        return $this;
    }

    /**
     * Get auditoria
     *
     * @return \ich\TestBundle\Entity\Auditoria 
     */
    public function getAuditoria()
    {
        return $this->auditoria;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add puestos
     *
     * @param \ich\TestBundle\Entity\Puesto_Competencia $puestos
     * @return Competencia
     */
    public function addPuesto(\ich\TestBundle\Entity\Puesto_Competencia $puestos)
    {
        $this->puestos[] = $puestos;

        return $this;
    }

    /**
     * Remove puestos
     *
     * @param \ich\TestBundle\Entity\Puesto_Competencia $puestos
     */
    public function removePuesto(\ich\TestBundle\Entity\Puesto_Competencia $puestos)
    {
        $this->puestos->removeElement($puestos);
    }

    /**
     * Get puestos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPuestos()
    {
        return $this->puestos;
    }

    /**
     * Add factores
     *
     * @param \ich\TestBundle\Entity\Factor $factores
     * @return Competencia
     */
    public function addFactore(\ich\TestBundle\Entity\Factor $factores)
    {
        $this->factores[] = $factores;

        return $this;
    }

    /**
     * Remove factores
     *
     * @param \ich\TestBundle\Entity\Factor $factores
     */
    public function removeFactore(\ich\TestBundle\Entity\Factor $factores)
    {
        $this->factores->removeElement($factores);
    }

    /**
     * Get factores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactores()
    {
        return $this->factores;
    }
    
    public function __toString()
    {
    	return (string) $this->getNombre();
    }
}
