<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use ich\TestBundle\Entity\Usuario;

class UsuarioController extends Controller
{
    public function homeAction()
    {
        return $this->render('ichTestBundle:Usuario:home.html.twig');    
    }
}
