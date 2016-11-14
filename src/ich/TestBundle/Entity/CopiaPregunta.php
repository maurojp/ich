<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CopiaPregunta
 *
 * @ORM\Table(name="copia_pregunta")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CopiaPreguntaRepository")
 */
class CopiaPregunta
{
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\CopiaFactor", inversedBy="copiaPreguntas")
	 * @ORM\JoinColumn(name="copiaFactor_id",referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $copiaFactor;
	
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\CopiaOpcionRespuesta", mappedBy="copiaPregunta", cascade={"persist"})
	 */
	protected $copiaOpcionesRespuesta;
		
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
     * @ORM\Column(name="codigo", type="string", length=10)
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
     * @var int
     * @ORM\Column(name="nroOrden", type="integer", nullable=true)
     */
    private $nroOrden;
    
    
    /**
     * @var int
     * @ORM\Column(name="nroBloque", type="integer", nullable=true)
     */
    private $nroBloque;
  
  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->copiaOpcionesRespuesta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigo
     *
     * @param string $codigo
     * @return CopiaPregunta
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
     * @return CopiaPregunta
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
     * @return CopiaPregunta
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
     * Set nroOrden
     *
     * @param integer $nroOrden
     * @return CopiaPregunta
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
     * Set nroBloque
     *
     * @param integer $nroBloque
     * @return CopiaPregunta
     */
    public function setNroBloque($nroBloque)
    {
    	$this->nroBloque = $nroBloque;
    
    	return $this;
    }
    
    /**
     * Get nroBloque
     *
     * @return integer
     */
    public function getNroBloque()
    {
    	return $this->nroBloque;
    }
    
    /**
     * Set copiaFactor
     *
     * @param \ich\TestBundle\Entity\CopiaFactor $copiaFactor
     * @return CopiaPregunta
     */
    public function setCopiaFactor(\ich\TestBundle\Entity\CopiaFactor $copiaFactor)
    {
        $this->copiaFactor = $copiaFactor;

        return $this;
    }

    /**
     * Get copiaFactor
     *
     * @return \ich\TestBundle\Entity\CopiaFactor 
     */
    public function getCopiaFactor()
    {
        return $this->copiaFactor;
    }

    /**
     * Add copiaOpcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\CopiaOpcionRespuesta $copiaOpcionesRespuesta
     * @return CopiaPregunta
     */
    public function addCopiaOpcionesRespuestum(\ich\TestBundle\Entity\CopiaOpcionRespuesta $copiaOpcionesRespuesta)
    {
        $this->copiaOpcionesRespuesta[] = $copiaOpcionesRespuesta;

        return $this;
    }

    /**
     * Remove copiaOpcionesRespuesta
     *
     * @param \ich\TestBundle\Entity\CopiaOpcionRespuesta $copiaOpcionesRespuesta
     */
    public function removeCopiaOpcionesRespuestum(\ich\TestBundle\Entity\CopiaOpcionRespuesta $copiaOpcionesRespuesta)
    {
        $this->copiaOpcionesRespuesta->removeElement($copiaOpcionesRespuesta);
    }

    /**
     * Get copiaOpcionesRespuesta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCopiaOpcionesRespuesta()
    {
        return $this->copiaOpcionesRespuesta;
    }
}
