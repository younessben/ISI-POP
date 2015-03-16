<?php

namespace IsiPop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IsiPopAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
