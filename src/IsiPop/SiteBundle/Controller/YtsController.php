<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Httpful\Request;
use IsiPop\SiteBundle\Entity\Movie;

class YtsController extends Controller
{
    public function indexAction($page,$search)
    {
        $uri = "https://yts.to/api/v2/list_movies.json?limit=18&quality=720p";
        $uri.=$search;
        $uri.="&page=".$page;
        return $this->ReturnMovieList($uri,$page,$search);
    }
    
    public function searchFormAction()
    {
        $search = $this->get('request')->request->get('query');
        return $this->redirect( $this->generateUrl('isi_pop_site_homepage', array('search' => '&query_term='.urlencode($search))));
    }
    
    private function ReturnMovieList($uri,$page,$search)
    {
        $response = Request::get($uri)->send();    
        $movieList  = [];
        
        foreach($response->body->data->movies as $m)
       {
           $movie = new Movie();
           $movie->setId($m->id);
           $movie->setTitle($m->title);
           $movie->setSeeds(isset($m->torrents[0]->seeds) ? $m->torrents[0]->seeds: 0 );
           $movie->setPeers(isset($m->torrents[0]->peers) ? $m->torrents[0]->peers: 0) ;
           $movie->setUrl(urlencode(isset($m->torrents[0]->url) ? $m->torrents[0]->url : 'NOTFOUND'));
           $movie->setCover($m->medium_cover_image);
           $movie->setImdb($m->imdb_code);
           array_push($movieList,$movie);
       }
        
             return $this->render('IsiPopSiteBundle:main:index.html.twig',array(
            'movies'  => $movieList,
            'CurrentPage' => $page,
            'Search' => $search));
    }
}
