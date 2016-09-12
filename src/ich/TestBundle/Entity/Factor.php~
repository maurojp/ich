<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Factor
 *
 * @ORM\Table(name="factor")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\FactorRepository")
 * @UniqueEntity("codigo")
 */
class Factor
{
	
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Competencia", inversedBy="factores")
	 * @ORM\JoinColumn(name="competencia_id",referencedColumnName="id", nullable=false)
	 */
	protected $competencia;
	
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Pregunta", mappedBy="factor")
	 */
	protected $preguntas;
	
	/**
	 * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Auditoria", inversedBy="factor")
	 * @ORM\JoinColumn(name="eliminado",referencedColumnName="id", nullable=true)
	 */
	protected $auditoria;
	
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
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="nroOrden", type="integer")
     */
    private $nroOrden;

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
     * @return Factor
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
     * @return Factor
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
     * Set nroOrden
     *
     * @param integer $nroOrden
     * @return Factor
     */
    public function setNroOrden($nroOrden)
    {
        $this->nroOrden = $nroOrden;

        return $this;
    }

    /**
     * Get nroOrden
     *
     * @return integer 
     */
    public function getNroOrden()
    {
        return $this->nroOrden;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Factor
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
     * Constructor
     */
    public function __construct()
    {
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set competencia
     *
     * @param \ich\TestBundle\Entity\Competencia $competencia
     * @return Factor
     */
    public function setCompetencia(\ich\TestBundle\Entity\Competencia $competencia)
    {
        $this->competencia = $competencia;

        return $this;
    }

    /**
     * Get competencia
     *
     * @return \ich\TestBundle\Entity\Competencia 
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * Add preguntas
     *
     * @param \ich\TestBundle\Entity\Pregunta $preguntas
     * @return Factor
     */
    public function addPregunta(\ich\TestBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas[] = $preguntas;

        return $this;
    }

    /**
     * Remove preguntas
     *
     * @param \ich\TestBundle\Entity\Pregunta $preguntas
     */
    public function removePregunta(\ich\TestBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas->removeElement($preguntas);
    }

    /**
     * Get preguntas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    /**
     * Set auditoria
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditoria
     * @return Factor
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
}
