<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evaluacion
 *
 * @ORM\Table(name="evaluacion")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\EvaluacionRepository")
 */
class Evaluacion
{
	/**
	 * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Puesto", inversedBy="evaluaciones")
	 * @ORM\JoinColumn(name="puesto_id",referencedColumnName="id", nullable=false)
	 */
	protected $puesto;
	
	/**
	 * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Cuestionario", mappedBy="evaluacion",cascade={"persist", "remove"})
	 */
	protected $cuestionarios;
	
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
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="fechaCreacion", type="date")
     */
    private $fechaCreacion;


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
     * @return Evaluacion
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Evaluacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion; 

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set puesto
     *
     * @param \ich\TestBundle\Entity\Puesto $puesto
     * @return Evaluacion
     */
    public function setPuesto(\ich\TestBundle\Entity\Puesto $puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return \ich\TestBundle\Entity\Puesto 
     */
    public function getPuesto()
    {
        return $this->puesto;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuestionarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cuestionarios
     *
     * @param \ich\TestBundle\Entity\Cuestionario $cuestionarios
     * @return Evaluacion
     */
    public function addCuestionario(\ich\TestBundle\Entity\Cuestionario $cuestionarios)
    {
        $this->cuestionarios[] = $cuestionarios;

        return $this;
    }

    /**
     * Remove cuestionarios
     *
     * @param \ich\TestBundle\Entity\Cuestionario $cuestionarios
     */
    public function removeCuestionario(\ich\TestBundle\Entity\Cuestionario $cuestionarios)
    {
        $this->cuestionarios->removeElement($cuestionarios);
    }

    /**
     * Get cuestionarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCuestionarios()
    {
        return $this->cuestionarios;
    }
}
