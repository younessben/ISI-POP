<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class YtsController extends Controller
{
    public function indexAction()
    {
        return $this->render('IsiPopSiteBundle:main:index.html.twig');
    }
}
