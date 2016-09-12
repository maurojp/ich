<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Registro
 *
 * @ORM\Table(name="registro")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\RegistroRepository")
 */
class Registro
{
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
     * @ORM\Column(name="fechaHora", type="datetime")
     */
    private $fechaHora;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="accion", type="string",columnDefinition="ENUM('inicioEjecucion','finEjecucion')", length=20)
     */
    private $accion;


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
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     * @return Registro
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime 
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    /**
     * Set accion
     *
     * @param string $accion
     * @return Registro
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string 
     */
    public function getAccion()
    {
        return $this->accion;
    }
}
