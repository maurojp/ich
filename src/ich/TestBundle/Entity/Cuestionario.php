<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cuestionario
 *
 * @ORM\Table(name="cuestionario")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\CuestionarioRepository")
 */
class Cuestionario
{
    /** 
     * @Assert\NotBlank()    
     * @ORM\ManyToOne(targetEntity="Candidato", inversedBy="cuestionarios")
     * @ORM\JoinColumn(name="candidato_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     */
    protected $candidato;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Evaluacion", inversedBy="cuestionarios")
     * @ORM\JoinColumn(name="evaluacion_id",referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $evaluacion;
    
    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\CopiaCompetencia", mappedBy="cuestionario", cascade={"persist", "remove"})
     */
    protected $copiaCompetencias;
    
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
     *
     * @ORM\Column(name="clave", type="string", length=100)
     */
    private $clave;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comienzoEn", type="datetime", nullable=true)
     */
    private $comienzoEn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="estadoEn", type="datetime", nullable=true)
     */
    private $estadoEn;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="cantAccesos", type="integer")
     */
    private $cantAccesos;

    /**
     * @var int
     *
     * @ORM\Column(name="cantMaxAccesos", type="integer")
     */
    private $cantMaxAccesos;

    /**
     * @var float
     *
     * @ORM\Column(name="tiempoMax", type="float")
     */
    private $tiempoMax;

    /**
     * @var float
     *
     * @ORM\Column(name="tiempoMaxActivo", type="float")
     */
    private $tiempoMaxActivo;

    /**
     * @var float
     *
     * @ORM\Column(name="puntajeTotal", type="float")
     */
    private $puntajeTotal;


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
     * Set clave
     *
     * @param string $clave
     * @return Cuestionario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set comienzoEn
     *
     * @param \DateTime $comienzoEn
     * @return Cuestionario
     */
    public function setComienzoEn($comienzoEn)
    {
        $this->comienzoEn = $comienzoEn;

        return $this;
    }

    /**
     * Get comienzoEn
     *
     * @return \DateTime 
     */
    public function getComienzoEn()
    {
        return $this->comienzoEn;
    }

    /**
     * Set estadoEn
     *
     * @param \DateTime $estadoEn
     * @return Cuestionario
     */
    public function setEstadoEn($estadoEn)
    {
        $this->estadoEn = $estadoEn;

        return $this;
    }

    /**
     * Get estadoEn
     *
     * @return \DateTime 
     */
    public function getEstadoEn()
    {
        return $this->estadoEn;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return Cuestionario
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set cantAccesos
     *
     * @param integer $cantAccesos
     * @return Cuestionario
     */
    public function setCantAccesos($cantAccesos)
    {
        $this->cantAccesos = $cantAccesos;

        return $this;
    }

    /**
     * Get cantAccesos
     *
     * @return integer 
     */
    public function getCantAccesos()
    {
        return $this->cantAccesos;
    }

    /**
     * Set cantMaxAccesos
     *
     * @param integer $cantMaxAccesos
     * @return Cuestionario
     */
    public function setCantMaxAccesos($cantMaxAccesos)
    {
        $this->cantMaxAccesos = $cantMaxAccesos;

        return $this;
    }

    /**
     * Get cantMaxAccesos
     *
     * @return integer 
     */
    public function getCantMaxAccesos()
    {
        return $this->cantMaxAccesos;
    }

    /**
     * Set tiempoMax
     *
     * @param float $tiempoMax
     * @return Cuestionario
     */
    public function setTiempoMax($tiempoMax)
    {
        $this->tiempoMax = $tiempoMax;

        return $this;
    }

    /**
     * Get tiempoMax
     *
     * @return float 
     */
    public function getTiempoMax()
    {
        return $this->tiempoMax;
    }

    /**
     * Set tiempoMaxActivo
     *
     * @param float $tiempoMaxActivo
     * @return Cuestionario
     */
    public function setTiempoMaxActivo($tiempoMaxActivo)
    {
        $this->tiempoMaxActivo = $tiempoMaxActivo;

        return $this;
    }

    /**
     * Get tiempoMaxActivo
     *
     * @return float 
     */
    public function getTiempoMaxActivo()
    {
        return $this->tiempoMaxActivo;
    }

    /**
     * Set puntajeTotal
     *
     * @param float $puntajeTotal
     * @return Cuestionario
     */
    public function setPuntajeTotal($puntajeTotal)
    {
        $this->puntajeTotal = $puntajeTotal;

        return $this;
    }

    /**
     * Get puntajeTotal
     *
     * @return float 
     */
    public function getPuntajeTotal()
    {
        return $this->puntajeTotal;
    }

    /**
     * Set candidato
     *
     * @param \ich\TestBundle\Entity\Candidato $candidato
     * @return Cuestionario
     */
    public function setCandidato(\ich\TestBundle\Entity\Candidato $candidato = null)
    {
        $this->candidato = $candidato;

        return $this;
    }

    /**
     * Get candidato
     *
     * @return \ich\TestBundle\Entity\Candidato 
     */
    public function getCandidato()
    {
        return $this->candidato;
    }

    /**
     * Set evaluacion
     *
     * @param \ich\TestBundle\Entity\Evaluacion $evaluacion
     * @return Cuestionario
     */
    public function setEvaluacion(\ich\TestBundle\Entity\Evaluacion $evaluacion)
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    /**
     * Get evaluacion
     *
     * @return \ich\TestBundle\Entity\Evaluacion 
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }
    
    
    /**
     * Add copiaCompetencias
     *
     * @param \ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencias
     * @return CopiaCompetencia
     */
    public function addCopiaCompetencia(\ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencias)
    {
    	$this->copiaCompetencias[] = $copiaCompetencias;
    
    	return $this;
    }
    
    /**
     * Remove copiaCompetencias
     *
     * @param \ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencias
     */
    public function removeCopiaCompetencia(\ich\TestBundle\Entity\CopiaCompetencia $copiaCompetencias)
    {
    	$this->copiaCompetencias->removeElement($copiaCompetencias);
    }
    
    /**
     * Get copiaCompetencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCopiaCompetencias()
    {
    	return $this->copiaCompetencias;
    }
}
