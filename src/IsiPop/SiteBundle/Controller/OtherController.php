<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OtherController extends Controller
{
    public function indexAction()
    {
        return $this->render('IsiPopSiteBundle:Other:index.html.twig', array(
                // ...
            ));    }

}
