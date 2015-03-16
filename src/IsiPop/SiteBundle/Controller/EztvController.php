<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EztvController extends Controller
{
    public function indexAction()
    {
        return $this->render('IsiPopSiteBundle:Eztv:index.html.twig', array(
                // ...
            ));    }

}
