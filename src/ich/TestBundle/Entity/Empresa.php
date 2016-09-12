<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Empresa
 *
 * @ORM\Table(name="empresa")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\EmpresaRepository")
 */
class Empresa
{

    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Puesto", mappedBy="empresa")
     */
    protected $puestos;

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
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;


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
     * @return empresa
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
     * Constructor
     */
    public function __construct()
    {
        $this->puestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add puestos
     *
     * @param \ich\TestBundle\Entity\Puesto $puestos
     * @return Empresa
     */
    public function addPuesto(\ich\TestBundle\Entity\Puesto $puestos)
    {
        $this->puestos[] = $puestos;

        return $this;
    }

    /**
     * Remove puestos
     *
     * @param \ich\TestBundle\Entity\Puesto $puestos
     */
    public function removePuesto(\ich\TestBundle\Entity\Puesto $puestos)
    {
        $this->puestos->removeElement($puestos);
    }

    /**
     * Get puestos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPuestos()
    {
        return $this->puestos;
    }
}
