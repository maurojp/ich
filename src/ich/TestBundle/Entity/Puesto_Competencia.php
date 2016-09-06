<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puesto_Competencia
 *
 * @ORM\Table(name="Puesto_Competencia")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\Puesto_CompetenciaRepository")
 */
class Puesto_Competencia
{
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Puesto", inversedBy="competencias") 
     * @ORM\JoinColumn(name="puesto_id",referencedColumnName="id", nullable=false)
     */
    protected $puestoId;
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Competencia", inversedBy="puestos") 
     * @ORM\JoinColumn(name="competencia_id",referencedColumnName="id", nullable=false)
     */
    protected $competenciaId;

    /**
     * @var int
     *
     * @ORM\Column(name="ponderacion", type="integer")
     */
    private $ponderacion;


    /**
     * Set puestoId
     *
     * @param \ich\TestBundle\Entity\Puesto $puestoId
     * @return Puesto_Competencia
     */
    public function setPuestoId(\ich\TestBundle\Entity\Puesto $puestoId = null)
    {
        $this->puestoId = $puestoId;

        return $this;
    }

    /**
     * Get puestoId
     *
     * @return \ich\TestBundle\Entity\Puesto
     */
    public function getPuestoId()
    {
        return $this->puestoId;
    }

    
       /**
     * Set competenciaId
     *
     * @param \ich\TestBundle\Entity\Competencia $competenciaId
     * @return Puesto_Competencia
     */
    public function setCompetenciaId(\ich\TestBundle\Entity\Competencia $competenciaId = null)
    {
        $this->competenciaId = $competenciaId;

        return $this;
    }

    /**
     * Get competenciaId
     *
     * @return \ich\TestBundle\Entity\Competencia
     */
    public function getCompetenciaId()
    {
        return $this->competenciaId;
    }


    /**
     * Set ponderacion
     *
     * @param int $ponderacion
     * @return Puesto_Competencia
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
}
