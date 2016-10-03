<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Puesto
 *
 * @ORM\Table(name="puesto")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\PuestoRepository")
 * @UniqueEntity("codigo")
 */
class Puesto
{

    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Puesto_Competencia", mappedBy="puesto", cascade={"persist", "remove"})
     */
    protected $competencias;

    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Evaluacion", mappedBy="puesto")
     */
    protected $evaluaciones;
    
    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Auditoria", inversedBy="puesto") 
     * @ORM\JoinColumn(name="eliminado",referencedColumnName="id", nullable=true)
     */
    protected $auditoria;

    /**
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Empresa",inversedBy="puestos")
     * @ORM\JoinColumn(name="empresa_id",referencedColumnName="id", nullable=false)
     */
    protected $empresa;


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
     * @return Puesto
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
     * @return Puesto
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
     * @return Puesto
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
     * Add competencias
     *
     * @param \ich\TestBundle\Entity\Puesto_Competencia $competencia
     * @return Puesto
     */
    public function addCompetencia(\ich\TestBundle\Entity\Puesto_Competencia $competencia)
    {
        $competencia->setPuesto($this);
        
        $this->competencias->add($competencia);
    }

    /**
     * Remove competencias
     *
     * @param \ich\TestBundle\Entity\Puesto_Competencia $competencia
     */
    public function removeCompetencia(\ich\TestBundle\Entity\Puesto_Competencia $competencia)
    {
        $this->competencias->removeElement($competencia);
    }

    /**
     * Get competencias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompetencias()
    {
        return $this->competencias;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competencias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set auditoria
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditoria
     * @return Puesto
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
     * Set empresa
     *
     * @param \ich\TestBundle\Entity\Empresa $empresa
     * @return Puesto
     */
    public function setEmpresa(\ich\TestBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \ich\TestBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Add evaluaciones
     *
     * @param \ich\TestBundle\Entity\Evaluacion $evaluaciones
     * @return Puesto
     */
    public function addEvaluacione(\ich\TestBundle\Entity\Evaluacion $evaluaciones)
    {
        $this->evaluaciones[] = $evaluaciones;

        return $this;
    }

    /**
     * Remove evaluaciones
     *
     * @param \ich\TestBundle\Entity\Evaluacion $evaluaciones
     */
    public function removeEvaluacione(\ich\TestBundle\Entity\Evaluacion $evaluaciones)
    {
        $this->evaluaciones->removeElement($evaluaciones);
    }

    /**
     * Get evaluaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvaluaciones()
    {
        return $this->evaluaciones;
    }
}
