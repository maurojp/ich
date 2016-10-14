<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GrupoOpciones
 *
 * @ORM\Table(name="grupo_opciones")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\GrupoOpcionesRepository")
 */
class GrupoOpciones
{
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Pregunta", mappedBy="grupoOpciones")
	 */
	protected $preguntas;
	
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\OpcionRespuesta", mappedBy="grupoOpciones")
	 */
	protected $opcionesRespuesta;
	
	/**
	 * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Auditoria", inversedBy="grupoOpciones")
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
     * Set nombre
     *
     * @param string $nombre
     * @return GrupoOpciones
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
     * @return GrupoOpciones
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
        $this->opcionesRespuesta = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add preguntas
     *
     * @param \ich\TestBundle\Entity\Pregunta $preguntas
     * @return GrupoOpciones
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
     * Add opcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\OpcionRespuesta $opcionesRespuesta
     * @return GrupoOpciones
     */
    public function addOpcionesRespuestum(\ich\TestBundle\Entity\OpcionRespuesta $opcionesRespuesta)
    {
        $this->opcionesRespuesta[] = $opcionesRespuesta;

        return $this;
    }

    /**
     * Remove opcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\OpcionRespuesta $opcionesRespuesta
     */
    public function removeOpcionesRespuestum(\ich\TestBundle\Entity\OpcionRespuesta $opcionesRespuesta)
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

    /**
     * Set auditoria
     *
     * @param \ich\TestBundle\Entity\Auditoria $auditoria
     * @return GrupoOpciones
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
    
    public function __toString()
    {
    	return (string) $this->getNombre();
    }
}
