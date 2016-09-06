<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Auditoria
 *
 * @ORM\Table(name="auditoria")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\AuditoriaRepository")
 */
class Auditoria
{

    /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Puesto", mappedBy="auditoriaId")
     */
    protected $puesto;

     /**
     * @ORM\OneToOne(targetEntity="ich\TestBundle\Entity\Competencia", mappedBy="auditoriaId")
     */
    protected $competencia;

      /**
     * @ORM\ManyToOne(targetEntity="ich\TestBundle\Entity\Usuario", inversedBy="auditorias") 
     * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id", nullable=false)
     */
    protected $usuarioId;

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
     *
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
     * Set usuarioId
     *
     * @param \ich\TestBundle\Entity\Usuario $usuarioId
     * @return Auditoria
     */
    public function setUsuarioId(\ich\TestBundle\Entity\Usuario $usuarioId = null)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return \ich\TestBundle\Entity\Usuario
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }
}
