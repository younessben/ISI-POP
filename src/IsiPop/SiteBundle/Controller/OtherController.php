<?php

namespace IsiPop\SiteBundle\Controller;

use IsiPop\SiteBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OtherController extends Controller
{
    public function indexAction()
    {
        
           $movieList  = [];
        
           // SAMPLE OF FRENCH MOVIE 1
           $movie = new Movie();
           $movie->setId(1);
           $movie->setTitle("Taken 3");
           $movie->setSeeds(10731);
           $movie->setPeers(508) ;
           $movie->setUrl(urlencode("http://www.cpasbien.pw/telechargement/taken-3-french-dvdscr-2015.torrent"));
           $movie->setCover("http://www.cpasbien.pw/_pictures/taken-3-french-dvdscr-2015.jpg");
           $movie->setImdb("tt2446042");
           array_push($movieList,$movie);
           
           // SAMPLE OF FRENCH MOVIE 2
           $movie2 = new Movie();
           $movie2->setId(2);
           $movie2->setTitle("La nuit au musée 3");
           $movie2->setSeeds(9232);
           $movie2->setPeers(399) ;
           $movie2->setUrl(urlencode("http://www.cpasbien.pw/telechargement/la-nuit-au-musee-le-secret-des-pharaons-french-dvdrip-2015.torrent"));
           $movie2->setCover("http://www.cpasbien.pw/_pictures/la-nuit-au-musee-le-secret-des-pharaons-french-dvdrip-2015.jpg");
           $movie2->setImdb("tt2692250");
           array_push($movieList,$movie2);
       
        return $this->render('IsiPopSiteBundle:Other:index.html.twig',array(
            'movies'  => $movieList,
            'CurrentPage' => 1,
            'Search' => null));
    }

}
