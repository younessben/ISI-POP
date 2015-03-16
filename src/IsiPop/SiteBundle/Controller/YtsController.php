<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Httpful\Request;
use IsiPop\SiteBundle\Entity\Movie;

class YtsController extends Controller
{
    public function indexAction()
    {
        $uri = "https://yts.re/api/v2/list_movies.json?limit=18&quality=720p&sort_by=";
        $response = Request::get($uri)->send();
        
        
    
        $movieList  = [];
    
        foreach($response->body->data->movies as $m)
       {
           $movie = new Movie();
           $movie->setId($m->id);
           $movie->setTitle($m->title);
           $movie->setSeeds($m->torrents[0]->seeds);
           $movie->setPeers($m->torrents[0]->peers);
           $movie->setUrl($m->torrents[0]->url);
           $movie->setCover($m->medium_cover_image);
           $movie->setImdb($m->imdb_code);
           array_push($movieList,$movie);
       }
        
        
             return $this->render('IsiPopSiteBundle:main:index.html.twig',array(
            'movies'  => $movieList));
    }
}
