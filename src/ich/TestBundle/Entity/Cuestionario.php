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
     * @ORM\ManyToOne(targetEntity="Candidato", inversedBy="cuestionarios")
     * @ORM\JoinColumn(name="candidato_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     */
    protected $candidato;
    
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
     * @ORM\Column(name="comienzoEn", type="datetime")
     */
    private $comienzoEn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="estadoEn", type="datetime")
     */
    private $estadoEn;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", columnDefinition="ENUM('ACTIVO', 'COMPLETO', 'INCOMPLETO', 'SIN CONTESTAR')", length=50)
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
     * @param string $estado
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
     * @return string 
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
}
