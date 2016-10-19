<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Pregunta
 *
 * @ORM\Table(name="pregunta")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\PreguntaRepository")
 * @UniqueEntity("codigo")
 */
class Pregunta
{
	/**
	 * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Factor", inversedBy="preguntas")
	 * @ORM\JoinColumn(name="factor_id",referencedColumnName="id", nullable=false)
	 */
	protected $factor;
	
	/**
	 * @Assert\NotBlank()
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Pregunta_OpcionRespuesta", mappedBy="pregunta",cascade={"persist", "remove"})
	 */
	protected $opcionesRespuesta;
	
	/**
	 * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\GrupoOpciones", inversedBy="preguntas")
	 * @ORM\JoinColumn(name="grupoOpciones_id",referencedColumnName="id", nullable=false)
	 */
	protected $grupoOpciones;
	
	/**
	 * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Auditoria", inversedBy="pregunta")
	 * @ORM\JoinColumn(name="eliminada",referencedColumnName="id", nullable=true)
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
     * 
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="pregunta", type="string", length=150)
     */
    private $pregunta;


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
     * @return Pregunta
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Pregunta
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
     * Set pregunta
     *
     * @param string $pregunta
     * @return Pregunta
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set factor
     *
     * @param \ich\TestBundle\Entity\Factor $factor
     * @return Pregunta
     */
    public function setFactor(\ich\TestBundle\Entity\Factor $factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return \ich\TestBundle\Entity\Factor 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set grupoOpciones
     *
     * @param \ich\TestBundle\Entity\GrupoOpciones $grupoOpciones
     * @return Pregunta
     */
    public function setGrupoOpciones(\ich\TestBundle\Entity\GrupoOpciones $grupoOpciones = null)
    {
        $this->grupoOpciones = $grupoOpciones;

        return $this;
    }

    /**
     * Get grupoOpciones
     *
     * @return \ich\TestBundle\Entity\GrupoOpciones 
     */
    public function getGrupoOpciones()
    {
        return $this->grupoOpciones;
    }

    /**
     * Set auditoria
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditoria
     * @return Pregunta
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
        $this->opcionesRespuesta = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add opcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\Pregunta_OpcionRespuesta $opcionesRespuesta
     * @return Pregunta
     */
    public function addOpcionesRespuestum(\ich\TestBundle\Entity\Pregunta_OpcionRespuesta $opcionesRespuesta)
    {
    	$opcionesRespuesta->setPregunta($this);
    	$this->opcionesRespuesta[] = $opcionesRespuesta;
    
    	return $this;
    }
    
    /**
     * Remove opcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\Pregunta_OpcionRespuesta $opcionesRespuesta
     */
    public function removeOpcionesRespuestum(\ich\TestBundle\Entity\Pregunta_OpcionRespuesta $opcionesRespuesta)
    {
    	$this->opcionesRespuesta->removeElement($opcionesRespuesta);
    }

    /**
     * Get opcionesRespuesta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOpcionesRespuesta()
    {
        return $this->opcionesRespuesta;
    }
}
