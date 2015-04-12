<?php

namespace IsiPop\SiteBundle\Controller;

use IsiPop\SiteBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OtherController extends Controller
{
    public function indexAction()
    {
            $movie = $this->getDoctrine()     
              ->getManager()
              ->getRepository('IsiPopAdminBundle:Movie');
              $movies = $movie->findAll();
           
        return $this->render('IsiPopSiteBundle:Other:index.html.twig',array(
            'movies'  => $movies,
            'CurrentPage' => 1,
            'Search' => null));
    }

}
