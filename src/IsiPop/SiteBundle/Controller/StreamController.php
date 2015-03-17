<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Httpful\Request;

use IsiPop\SiteBundle\Entity\Host;
use IsiPop\SiteBundle\Entity\Process;
use IsiPop\SiteBundle\Entity\Movie;
use IsiPop\SiteBundle\Entity\Subtitle;

class StreamController extends Controller
{
    public function ytsAction($id,$url)
    {   
        $torrent = urldecode($url);
        var_dump($torrent);
        // Data test only
        // Bilbo 3
        
        // Create a host object
        $HOST = new Host();
        $HOST->setHostname($this->getRequest()->getHost());
        $HOST->setPortHost($this->getRequest()->getPort());
        $HOST->setPortStream(8889, 8999);
        
        // Get Movie information
        $uri = "https://yts.re/api/v2/movie_details.json?with_images=true&movie_id=".$id;
        $response = Request::get($uri)->send();
        
        if($response->body->status == "error")
        {
            $messageError = "L'id du film n'a pas été trouvé";
             return $this->render('IsiPopSiteBundle:layouts:errors.html.twig',array(
             'errorMessage'  => $messageError))  ;
        }
        
        $Movie = new Movie();
        $Movie->setId($id);
        $Movie->setTitle($response->body->data->title);
        $Movie->setSeeds($response->body->data->torrents[0]->seeds);
        $Movie->setPeers($response->body->data->torrents[0]->peers);
        $Movie->setCover($response->body->data->images->medium_cover_image);
        $Movie->setImdb($response->body->data->imdb_code);
        
        
        // Get subtitles
        $subtitles = [];
        // GET IMDB CODE
        $Imdb = $Movie->getImdb();
        
        $uri = "http://api.yifysubtitles.com/subs/".$Imdb;
        $response = Request::get($uri)->send();            
        
        if(isset($response->body->subs->$Imdb))
        {  
        
        $listSubtitles = $response->body->subs->$Imdb;
        
        
        $tempory_folder = sys_get_temp_dir();
        $tmpZip = $tempory_folder."/TMP.ZIP";
        
        foreach ($listSubtitles as $key => $val) {
            // Define Subtitle Information
            $subtitle = new Subtitle();
            $subtitle->setLang($key);
            $subtitle->setUrlFile("http://www.yifysubtitles.com/".$val[0]->url);
            $subtitle->setDirectoryEncode("subtitles/".$Movie->getCleanTitle()."/");
            $subtitle->setDirectoryNoEncode("subtitles/".$Movie->getCleanTitleEncode()."/");
            
            file_put_contents($tmpZip, fopen($subtitle->getUrlFile(), 'r')); // on recupere le fichier zip
                
            $zip = new  \ZipArchive;
                if ($zip->open($tmpZip) === true) 
                    {
                        for($i = 0; $i < $zip->numFiles; $i++) 
                        {
                            $filename = $zip->getNameIndex($i);               
                            if(substr(strrchr($filename,'.'),1) == "srt")
                            {
                                // on verifie que le repertoire des sous-titres existes sinon on le créer
                                if (!file_exists($subtitle->getDirectoryNoEncode())) {
                                mkdir($subtitle->getDirectoryNoEncode(), 0777, true);
                                }
                                
                                // si le fichier sous-titre n'existe pas on le copy
                              if(!file_exists($subtitle->getFile()))
                            copy("zip://".$tmpZip."#".$filename, $subtitle->getFile()); 
                            }
                        }                  
                    $zip->close(); 
                    }
                    $subtitle->setUrlStream($HOST->getHostUrl()."/".$subtitle->getFile());
                    array_push($subtitles,$subtitle);
            }
        
        }
        
           

        // TODO Create command for windows and linux compatible
        // execute Command
        // Windows usage debug
      //$Process = new \Symfony\Component\Process\ProcessBuilder(array('C:\Program Files\nodejs\peerflix.cmd' ,$torrent,'-p '. $HOST->getPortStream()));
       
        
        
        // linux command
     //  $Process = new \Symfony\Component\Process\ProcessBuilder(array('nohup','peerflix' ,$torrent,'-p '. $HOST->getPortStream(),'> /dev/null 2>&1 & echo $!'));
    //   $Process->getProcess()->start();          
      
    
    //   while($Process->getProcess()->isRunning()) {}
       
       
      
       
        
        $command = 'nohup peerflix '.$torrent.' -p '.$HOST->getPortStream().' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $pid = (int)$op[0];
        
        
                return $this->render('IsiPopSiteBundle:main:stream.html.twig',array(
            'streamUrl'  => $HOST->getStreamUrl(),
            'subtitles' => $subtitles));
        
        
    }
}

   // return $this->render('IsiPopSiteBundle:layouts:errors.html.twig',array(
        //    'errorMessage'  => 'Error message to display'))  ;
        
        // TEST PORT RETURN TO SHOW ERROR
        /*
         * if($port==-1)
         * {
         * NO PORT AVAILABLE
         * }
         */