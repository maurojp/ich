<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ichTestBundle:Default:index.html.twig');
    }
}
