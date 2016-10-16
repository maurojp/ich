<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pregunta_OpcionRespuesta
 *
 * @ORM\Table(name="pregunta__opcion_respuesta",uniqueConstraints = {
 *      @ORM\UniqueConstraint(columns = {"pregunta_id", "opcionRespuesta_id"}) })
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\Pregunta_OpcionRespuestaRepository")
 * @UniqueEntity(
 *     fields={"pregunta", "opcionRespuesta"},
 *     message="Hay opciones de Respuesta duplicadas en la Pregunta."
 * )
 */
class Pregunta_OpcionRespuesta
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var int
	 * @Assert\NotBlank()
	 * @ORM\Column(name="ponderacion", type="integer")
	 */
	private $ponderacion;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Pregunta", inversedBy="opcionesRespuesta",cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="pregunta_id",referencedColumnName="id", nullable=false)
	 */
	protected $pregunta;
		
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\OpcionRespuesta",inversedBy="preguntas",cascade={"persist"})
	 * @ORM\JoinColumn(name="opcionRespuesta_id",referencedColumnName="id", nullable=false)
	 */
	protected $opcionRespuesta;
	

	/**
	 * Set ponderacion
	 *
	 * @param int $ponderacion
	 * @return Pregunta_OpcionRespuesta
	 */
	public function setPonderacion($ponderacion)
	{
		$this->ponderacion = $ponderacion;
	
		return $this;
	}
	
	/**
	 * Get ponderacion
	 *
	 * @return int
	 */
	public function getPonderacion()
	{
		return $this->ponderacion;
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
     * Set pregunta
     *
     * @param \ich\TestBundle\Entity\Pregunta $pregunta
     * @return Pregunta_OpcionRespuesta
     */
    public function setPregunta(\ich\TestBundle\Entity\Pregunta $pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return \ich\TestBundle\Entity\Pregunta 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set opcionRespuesta
     *
     * @param \ich\TestBundle\Entity\OpcionRespuesta $opcionRespuesta
     * @return Pregunta_OpcionRespuesta
     */
    public function setOpcionRespuesta(\ich\TestBundle\Entity\OpcionRespuesta $opcionRespuesta)
    {
        $this->opcionRespuesta = $opcionRespuesta;

        return $this;
    }

    /**
     * Get opcionRespuesta
     *
     * @return \ich\TestBundle\Entity\OpcionRespuesta 
     */
    public function getOpcionRespuesta()
    {
        return $this->opcionRespuesta;
    }
}
