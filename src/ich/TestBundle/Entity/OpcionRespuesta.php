<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OpcionRespuesta
 *
 * @ORM\Table(name="opcion_respuesta")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\OpcionRespuestaRepository")
 */
class OpcionRespuesta
{
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Pregunta_OpcionRespuesta", mappedBy="opcionRespuesta")
	 */
	protected $preguntas;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\GrupoOpciones", inversedBy="opcionesRespuesta")
	 * @ORM\JoinColumn(name="grupoOpciones_id",referencedColumnName="id", nullable=false)
	 */
	protected $grupoOpciones;
	
     /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="descripcion", type="string", length=150)
     */
    private $descripcion;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="ordenEvaluacion", type="integer")
     */
    private $ordenEvaluacion;


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
     * Set descripcion
     *
     * @param string $descripcion
     * @return OpcionRespuesta
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
     * Set ordenEvaluacion
     *
     * @param integer $ordenEvaluacion
     * @return OpcionRespuesta
     */
    public function setOrdenEvaluacion($ordenEvaluacion)
    {
        $this->ordenEvaluacion = $ordenEvaluacion;

        return $this;
    }

    /**
     * Get ordenEvaluacion
     *
     * @return integer 
     */
    public function getOrdenEvaluacion()
    {
        return $this->ordenEvaluacion;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return OpcionRespuesta
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add preguntas
     *
     * @param \ich\TestBundle\Entity\Pregunta_OpcionRespuesta $preguntas
     * @return OpcionRespuesta
     */
    public function addPregunta(\ich\TestBundle\Entity\Pregunta_OpcionRespuesta $preguntas)
    {
        $this->preguntas[] = $preguntas;

        return $this;
    }

    /**
     * Remove preguntas
     *
     * @param \ich\TestBundle\Entity\Pregunta_OpcionRespuesta $preguntas
     */
    public function removePregunta(\ich\TestBundle\Entity\Pregunta_OpcionRespuesta $preguntas)
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
     * Set grupoOpciones
     *
     * @param \ich\TestBundle\Entity\GrupoOpciones $grupoOpciones
     * @return OpcionRespuesta
     */
    public function setGrupoOpciones(\ich\TestBundle\Entity\GrupoOpciones $grupoOpciones)
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
    
    public function __toString()
    {
    	return (string) $this->getDescripcion();
    }
}
