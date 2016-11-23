<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CopiaFactor
 *
 * @ORM\Table(name="copia_factor")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CopiaFactorRepository")
 */
class CopiaFactor
{
	
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\CopiaCompetencia", inversedBy="copiaFactores")
	 * @ORM\JoinColumn(name="copiaCompetencia_id",referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $copiaCompetencia;
	
	
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\CopiaPregunta", mappedBy="copiaFactor", cascade={"persist", "remove"})
	 */
	protected $copiaPreguntas;
	
	
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
     * @Assert\NotBlank()
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @var int
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
     * @var float
     * @ORM\Column(name="puntajeObtenido", type="float", nullable=true)
     */
    private $puntajeObtenido;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->copiaPreguntas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CopiaFactor
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
     * @return CopiaFactor
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
     * @return CopiaFactor
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
     * Set puntajeObtenido
     *
     * @param integer $puntajeObtenido
     * @return CopiaFactor
     */
    public function setPuntajeObtenido($puntajeObtenido)
    {
    	$this->puntajeObtenido = $puntajeObtenido;
    
    	return $this;
    }
    
    /**
     * Get puntajeObtenido
     *
     * @return integer
     */
    public function getPuntajeObtenido()
    {
    	return $this->puntajeObtenido;
    }
    
    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CopiaFactor
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
     * Set copiaCompetencia
     *
     * @param \ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencia
     * @return CopiaFactor
     */
    public function setCopiaCompetencia(\ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencia)
    {
        $this->copiaCompetencia = $copiaCompetencia;

        return $this;
    }

    /**
     * Get copiaCompetencia
     *
     * @return \ich\TestBundle\Entity\CopiaCompetencia 
     */
    public function getCopiaCompetencia()
    {
        return $this->copiaCompetencia;
    }

    /**
     * Add copiaPreguntas
     *
     * @param \ich\TestBundle\Entity\CopiaPregunta $copiaPreguntas
     * @return CopiaFactor
     */
    public function addCopiaPregunta(\ich\TestBundle\Entity\CopiaPregunta $copiaPreguntas)
    {
        $this->copiaPreguntas[] = $copiaPreguntas;

        return $this;
    }

    /**
     * Remove copiaPreguntas
     *
     * @param \ich\TestBundle\Entity\CopiaPregunta $copiaPreguntas
     */
    public function removeCopiaPregunta(\ich\TestBundle\Entity\CopiaPregunta $copiaPreguntas)
    {
        $this->copiaPreguntas->removeElement($copiaPreguntas);
    }

    /**
     * Get copiaPreguntas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCopiaPreguntas()
    {
        return $this->copiaPreguntas;
    }
}
