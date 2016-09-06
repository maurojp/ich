<?php

namespace ich\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Empresa
 *
 * @ORM\Table(name="Empresa")
 * @ORM\Entity(repositoryClass="ich\TestBundle\Repository\EmpresaRepository")
 */
class Empresa
{

    /**
     * @ORM\OneToMany(targetEntity="ich\TestBundle\Entity\Puesto", mappedBy="empresaId")
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
     *
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
     * Add puestos
     *
     * @param \ich\TestBundle\Entity\Puesto $puestos
     * @return Empresa
     */
    public function addPuesto(\ich\TestBundle\Entity\Auditoria $puestos)
    {
        $this->puestos[] = $puestos;

        return $this;
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
