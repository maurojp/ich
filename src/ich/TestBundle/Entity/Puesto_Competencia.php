<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Puesto_Competencia
 *
 * @ORM\Table(name="puesto_competencia")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\Puesto_CompetenciaRepository")
 * @UniqueEntity(
 *     fields={"puesto", "competencia"},
 *     message="Hay competencias duplicadas en el puesto."
 * )
 */
class Puesto_Competencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Puesto", inversedBy="competencias", cascade={"persist", "remove"}) 
     * @ORM\JoinColumn(name="puesto_id",referencedColumnName="id", nullable=false)
     */
    protected $puesto;
  
    /**
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Competencia", inversedBy="puestos", cascade={"persist"}) 
     * @ORM\JoinColumn(name="competencia_id",referencedColumnName="id", nullable=false)
     */
    protected $competencia;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="ponderacion", type="integer")
     */
    private $ponderacion;

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

    /**
     * Set puesto
     *
     * @param \ich\TestBundle\Entity\Puesto $puesto
     * @return Puesto_Competencia
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
     * Set competencia
     *
     * @param \ich\TestBundle\Entity\Competencia $competencia
     * @return Puesto_Competencia
     */
    public function setCompetencia(\ich\TestBundle\Entity\Competencia $competencia)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
