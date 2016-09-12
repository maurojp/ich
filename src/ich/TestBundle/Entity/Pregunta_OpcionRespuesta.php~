<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pregunta_OpcionRespuesta
 *
 * @ORM\Table(name="pregunta__opcion_respuesta")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\Pregunta_OpcionRespuestaRepository")
 */
class Pregunta_OpcionRespuesta
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Pregunta", inversedBy="opcionesRespuesta")
	 * @ORM\JoinColumn(name="pregunta_id",referencedColumnName="id", nullable=false)
	 */
	protected $pregunta;
		
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\OpcionRespuesta",inversedBy="preguntas")
	 * @ORM\JoinColumn(name="opcionRespuesta_id",referencedColumnName="id", nullable=false)
	 */
	protected $opcionRespuesta;
	

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

    /**
     * Set grupoOpciones
     *
     * @param \ich\TestBundle\Entity\GrupoOpciones $grupoOpciones
     * @return Pregunta_OpcionRespuesta
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

    /**
     * Set opcionRespuesta_grupoOpciones
     *
     * @param \ich\TestBundle\Entity\OpcionRespuesta $opcionRespuestaGrupoOpciones
     * @return Pregunta_OpcionRespuesta
     */
    public function setOpcionRespuestaGrupoOpciones(\ich\TestBundle\Entity\OpcionRespuesta $opcionRespuestaGrupoOpciones)
    {
        $this->opcionRespuesta_grupoOpciones = $opcionRespuestaGrupoOpciones;

        return $this;
    }

    /**
     * Get opcionRespuesta_grupoOpciones
     *
     * @return \ich\TestBundle\Entity\OpcionRespuesta 
     */
    public function getOpcionRespuestaGrupoOpciones()
    {
        return $this->opcionRespuesta_grupoOpciones;
    }
}
