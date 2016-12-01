<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auditoria
 *
 * @ORM\Table(name="auditoria")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\AuditoriaRepository")
 */
class Auditoria
{

    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Puesto", mappedBy="auditoria")
     */
    protected $puesto;

    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Factor", mappedBy="auditoria")
     */
    protected $factor;
    
    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Competencia", mappedBy="auditoria")
     */
    protected $competencia;

    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Pregunta", mappedBy="auditoria")
     */
    protected $pregunta;
    
    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\GrupoOpciones", mappedBy="auditoria")
     */
    protected $grupoOpciones;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Usuario", inversedBy="auditorias") 
     * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id", nullable=false)
     */
    protected $usuario;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="fechaHoraEliminacion", type="datetime")
     */
    private $fechaHoraEliminacion;

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
     * Set fechaHoraEliminacion
     *
     * @param \DateTime $fechaHoraEliminacion
     * @return Auditoria
     */
    public function setFechaHoraEliminacion($fechaHoraEliminacion)
    {
        $this->fechaHoraEliminacion = $fechaHoraEliminacion;

        return $this;
    }

    /**
     * Get fechaHoraEliminacion
     *
     * @return \DateTime 
     */
    public function getFechaHoraEliminacion()
    {
        return $this->fechaHoraEliminacion;
    }

 

    /**
     * Set puesto
     *
     * @param \ich\TestBundle\Entity\Puesto $puesto
     * @return Auditoria
     */
    public function setPuesto(\ich\TestBundle\Entity\Puesto $puesto = null)
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
     * Set competencia
     *
     * @param \ich\TestBundle\Entity\Competencia $competencia
     * @return Auditoria
     */
    public function setCompetencia(\ich\TestBundle\Entity\Competencia $competencia = null)
    {
        $this->competencia = $competencia;

        return $this;
    }

    /**
     * Get competencia
     *
     * @return \ich\TestBundle\Entity\Competencia 
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * Set usuario
     *
     * @param \ich\TestBundle\Entity\Usuario $usuario
     * @return Auditoria
     */
    public function setUsuario(\ich\TestBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \ich\TestBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set factor
     *
     * @param \ich\TestBundle\Entity\Factor $factor
     * @return Auditoria
     */
    public function setFactor(\ich\TestBundle\Entity\Factor $factor = null)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return \ich\TestBundle\Entity\Factor 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set pregunta
     *
     * @param \ich\TestBundle\Entity\Pregunta $pregunta
     * @return Auditoria
     */
    public function setPregunta(\ich\TestBundle\Entity\Pregunta $pregunta = null)
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
     * Set grupoOpciones
     *
     * @param \ich\TestBundle\Entity\GrupoOpciones $grupoOpciones
     * @return Auditoria
     */
    public function setGrupoOpciones(\ich\TestBundle\Entity\GrupoOpciones $grupoOpciones = null)
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
}
