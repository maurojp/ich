<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CopiaOpcionRespuesta
 *
 * @ORM\Table(name="copia_opcion_respuesta")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CopiaOpcionRespuestaRepository")
 */
class CopiaOpcionRespuesta
{
	/**
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\CopiaPregunta", inversedBy="copiaOpcionesRespuesta")
	 * @ORM\JoinColumn(name="copiaPregunta_id",referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $copiaPregunta;
	
	/**
     * @var int
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
     * @var bool
     *
     * @ORM\Column(name="seleccionada", type="boolean", nullable=true)
     */
    private $seleccionada;
    

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="ordenEvaluacion", type="integer")
     */
    private $ordenEvaluacion;

    
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
     * Set ponderacion
     *
     * @param integer $ponderacion
     * @return CopiaOpcionRespuesta
     */
    public function setPonderacion($ponderacion)
    {
        $this->ponderacion = $ponderacion;

        return $this;
    }

    /**
     * Get ponderacion
     *
     * @return integer 
     */
    public function getPonderacion()
    {
        return $this->ponderacion;
    }

    /**
     * Set seleccionada
     *
     * @param boolean $seleccionada
     * @return CopiaOpcionRespuesta
     */
    public function setSeleccionada($seleccionada)
    {
        $this->seleccionada = $seleccionada;

        return $this;
    }

    /**
     * Get seleccionada
     *
     * @return boolean 
     */
    public function getSeleccionada()
    {
        return $this->seleccionada;
    }

    /**
     * Set ordenEvaluacion
     *
     * @param integer $ordenEvaluacion
     * @return CopiaOpcionRespuesta
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return CopiaOpcionRespuesta
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
     * Set copiaPregunta
     *
     * @param \ich\TestBundle\Entity\CopiaPregunta $copiaPregunta
     * @return CopiaOpcionRespuesta
     */
    public function setCopiaPregunta(\ich\TestBundle\Entity\CopiaPregunta $copiaPregunta)
    {
        $this->copiaPregunta = $copiaPregunta;

        return $this;
    }

    /**
     * Get copiaPregunta
     *
     * @return \ich\TestBundle\Entity\CopiaPregunta 
     */
    public function getCopiaPregunta()
    {
        return $this->copiaPregunta;
    }
}
