<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Httpful\Request;
use IsiPop\SiteBundle\Entity\Show;
use IsiPop\SiteBundle\Entity\Episode;

class ShowController extends Controller
{
    public function indexAction($page, $search)
    {
        if ($search =="")
        {
            $uri = "http://eztvapi.re/shows/";
            $uri.=$page;
            $response = Request::get($uri)->send();
        }
        else
        {
            $uri = "http://eztvapi.re/shows/search/";
            $uri.=$search;
            $uri.="/all";
            $response = Request::get($uri)->send();  
        }
        
        $showList  = [];
    
        foreach($response->body as $s)
       {
           $show = new Show();
           $show->setId($s->_id);
           $show->setTitle($s->title);
           $show->setPoster($s->images->poster);
           $show->setBanner($s->images->banner);
           $show->setFanart($s->images->fanart);
           $show->setYear($s->year);
           $show->setNumSeasons($s->num_seasons);
           $show->setImbd($s->imdb_id);
           array_push($showList,$show);
       }

             return $this->render('IsiPopSiteBundle:shows:index.html.twig',array(
            'shows'  => $showList,
            'CurrentPage' => $page,
            'Search' => $search));
    }
    
    public function showAction($id)
    {
        $uri = "http://eztvapi.re/show/".$id;
        $response = Request::get($uri)->send();
    
        $show = new Show();
        $show->setId($response->body->_id);
        $show->setTitle($response->body->title);
        $show->setPoster($response->body->images->poster);
        $show->setBanner($response->body->images->banner);
        $show->setFanart($response->body->images->fanart);
        $show->setYear($response->body->year);
        $show->setNumSeasons($response->body->num_seasons);
        $show->setImbd($response->body->imdb_id);
        $episodes = [];
    
        foreach($response->body->episodes as $e)
       {
           $episode = new Episode();
           $episode->setTvbdId($e->tvdb_id);
           $episode->setSeason($e->season);
           $episode->setEpisode($e->episode);
           $episode->setTitle($e->title);
           $episode->setOverview($e->overview);
           $episode->setFirstAired($e->first_aired);
           foreach($e->torrents as $key => $tor)
           {
                if ($key == '0'){
                    $episode->setDefaultUrl(urlencode($tor->url));
                }
                if ($key == '480p'){
                    $episode->setMidUrl(urlencode($tor->url));
                }
                if ($key == '720p'){
                    $episode->setHighUrl(urlencode($tor->url));
                }
           }
           
           $numseas = $e->season; 
           if (isset($episodes[$numseas-1])){
               array_push($episodes[$numseas-1],$episode);
           }
           else{
               $episodes[$numseas-1]=[];
               array_push($episodes[$numseas-1],$episode);
            }
       }

            $show->setSeasons($episodes);
             return $this->render('IsiPopSiteBundle:shows:show.html.twig',array(
            'show'  => $show));
    }
    public function searchFormAction()
    {
        $search = $this->get('request')->request->get('query');
        return $this->redirect( $this->generateUrl('isi_pop_site_shows', array('search' => urlencode($search))));
    }
    
}
