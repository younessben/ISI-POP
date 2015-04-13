<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Httpful\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use IsiPop\SiteBundle\Entity\Host;
use IsiPop\SiteBundle\Entity\Movie;
use IsiPop\SiteBundle\Entity\Show;
use IsiPop\SiteBundle\Entity\Episode;
use IsiPop\SiteBundle\Entity\Subtitle;

class StreamController extends Controller {

    public function ytsAction($id, $url) {
        // define serializer
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        // Decode URL Torrent
        $torrent = urldecode($url);

        // Get tempory folder
        $tempory_folder = sys_get_temp_dir();


        // Create a host object
        $HOST = new Host();
        $HOST->setHostname($this->getRequest()->getHost());
        $HOST->setPortHost($this->getRequest()->getPort());
        $HOST->setPortStream(8889, 8999);

        // Get tempory file
        // Get Movie information
        $uri = "https://yts.to/api/v2/movie_details.json?with_images=true&movie_id=" . $id;
        $response = Request::get($uri)->send();

        if ($response->body->status == "error") {
            $messageError = "L'id du film n'a pas été trouvé";
            return $this->render('IsiPopSiteBundle:layouts:errors.html.twig', array(
                        'errorMessage' => $messageError));
        }

        $Movie = new Movie();
        $Movie->setId($id);
        $Movie->setTitle($response->body->data->title);
        $Movie->setSeeds($response->body->data->torrents[0]->seeds);
        $Movie->setPeers($response->body->data->torrents[0]->peers);
        $Movie->setCover($response->body->data->images->medium_cover_image);
        $Movie->setImdb($response->body->data->imdb_code);
        $Movie->setUrl($torrent);
        $Movie->setPort($HOST->getPortStream());

        // define tempory file
        $tempory_movie_data = $tempory_folder . '/isipop.data';
        $movieList = [];
        $isInTmp = false;
        if (file_exists($tempory_movie_data)) {
            $json = file_get_contents($tempory_movie_data);
            $jsondecode = json_decode($json);
            foreach ($jsondecode as $jsons) {
                $Movietmp = $serializer->deserialize(json_encode($jsons), 'IsiPop\SiteBundle\Entity\Movie', 'json');
                array_push($movieList, $Movietmp);

                if ($Movietmp->getUrl() == $torrent) {
                    // this file is always in stream
                    $Movie = $Movietmp;
                    $isInTmp = true;
                    $HOST->setPortStream2($Movie->getPort());
                }
            }
        }


        // Get subtitles
        $subtitles = [];
        // GET IMDB CODE
        $Imdb = $Movie->getImdb();

        $uri = "http://api.yifysubtitles.com/subs/" . $Imdb;
        $response = Request::get($uri)->send();

        if (isset($response->body->subs->$Imdb)) {

            $listSubtitles = $response->body->subs->$Imdb;



            $tmpZip = $tempory_folder . "/TMP.ZIP";

            foreach ($listSubtitles as $key => $val) {
                // Define Subtitle Information
                $subtitle = new Subtitle();
                $subtitle->setLang($key);
                $subtitle->setUrlFile("http://www.yifysubtitles.com/" . $val[0]->url);
                $subtitle->setDirectoryEncode("subtitles/" . $Movie->getCleanTitle() . "/");
                $subtitle->setDirectoryNoEncode("subtitles/" . $Movie->getCleanTitleEncode() . "/");

                file_put_contents($tmpZip, fopen($subtitle->getUrlFile(), 'r')); // on recupere le fichier zip

                $zip = new \ZipArchive;
                if ($zip->open($tmpZip) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $filename = $zip->getNameIndex($i);
                        if (substr(strrchr($filename, '.'), 1) == "srt") {
                            // on verifie que le repertoire des sous-titres existes sinon on le créer
                            if (!file_exists($subtitle->getDirectoryNoEncode())) {
                                mkdir($subtitle->getDirectoryNoEncode(), 0777, true);
                            }

                            // si le fichier sous-titre n'existe pas on le copy
                            if (!file_exists($subtitle->getFile()))
                                copy("zip://" . $tmpZip . "#" . $filename, $subtitle->getFile());
                        }
                    }
                    $zip->close();
                }
                $subtitle->setUrlStream($HOST->getHostUrl() . "/" . $subtitle->getFile());
                array_push($subtitles, $subtitle);
            }
        }





        if ($isInTmp == false) {
            $command = 'nohup peerflix "' . $torrent . '" -p ' . $HOST->getPortStream() . ' > /dev/null 2>&1 & echo $!';
            exec($command, $op);
            $pid = (int) $op[0];

            $Movie->setPid($pid);

            array_push($movieList, $Movie);
            $jsonContent = $serializer->serialize($movieList, 'json');
            file_put_contents($tempory_folder . '/isipop.data', $jsonContent);
        }


        return $this->render('IsiPopSiteBundle:Stream:stream.html.twig', array(
                    'streamUrl' => $HOST->getStreamUrl(),
                    'subtitles' => $subtitles));
    }

    public function OtherAction($id, $url) {
        $torrent = urldecode($url);
        $tempory_folder = sys_get_temp_dir();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        // Create a host object
        $HOST = new Host();
        $HOST->setHostname($this->getRequest()->getHost());
        $HOST->setPortHost($this->getRequest()->getPort());
        $HOST->setPortStream(8889, 8999);

        $Movie = new Movie();
        $Movie->setId($id);
        $Movie->setUrl($torrent);
        $Movie->setPort($HOST->getPortStream());

        // define tempory file
        $tempory_movie_data = $tempory_folder . '/isipop.data';
        $movieList = [];
        $isInTmp = false;
        if (file_exists($tempory_movie_data)) {
            $json = file_get_contents($tempory_movie_data);
            $jsondecode = json_decode($json);
            foreach ($jsondecode as $jsons) {
                $Movietmp = $serializer->deserialize(json_encode($jsons), 'IsiPop\SiteBundle\Entity\Movie', 'json');
                array_push($movieList, $Movietmp);

                if ($Movietmp->getUrl() == $torrent) {
                    // this file is always in stream
                    $Movie = $Movietmp;
                    $isInTmp = true;
                    $HOST->setPortStream2($Movie->getPort());
                }
            }
        }


        if ($isInTmp == false) {
            $command = 'nohup peerflix "' . $torrent . '" -p ' . $HOST->getPortStream() . ' > /dev/null 2>&1 & echo $!';
            exec($command, $op);
            $pid = (int) $op[0];

            $Movie->setPid($pid);

            array_push($movieList, $Movie);
            $jsonContent = $serializer->serialize($movieList, 'json');
            file_put_contents($tempory_folder . '/isipop.data', $jsonContent);
        }


        return $this->render('IsiPopSiteBundle:Stream:streamvlc.html.twig', array(
                    'streamUrl' => $HOST->getStreamUrl(),
                    'subtitles' => null));
    }
    
    public function ShowAction($id, $url, $season, $episode) {
            
        // define serializer
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);    
        
        $tempory_folder = sys_get_temp_dir();
            
        // Create a host object
        $HOST = new Host();
        $HOST->setHostname($this->getRequest()->getHost());
        $HOST->setPortHost($this->getRequest()->getPort());
        $HOST->setPortStream(8889, 8999);
        
        $torrent = urldecode($url);

        $Movie = new Movie();
        $Movie->setId($id);
        $Movie->setUrl($torrent);
        $Movie->setPort($HOST->getPortStream());
        
        // define tempory file
        $tempory_movie_data = $tempory_folder . '/isipop.data';
        $movieList = [];
        $isInTmp = false;
        if (file_exists($tempory_movie_data)) {
            $json = file_get_contents($tempory_movie_data);
            $jsondecode = json_decode($json);
            foreach ($jsondecode as $jsons) {
                $Movietmp = $serializer->deserialize(json_encode($jsons), 'IsiPop\SiteBundle\Entity\Movie', 'json');
                array_push($movieList, $Movietmp);

                if ($Movietmp->getUrl() == $torrent) {
                    // this file is always in stream
                    $Movie = $Movietmp;
                    $isInTmp = true;
                    $HOST->setPortStream2($Movie->getPort());
                }
            }
        }

        if ($isInTmp == false) {

            $command = 'nohup peerflix "'  . $torrent . '" -p ' . $HOST->getPortStream() . ' > /dev/null 2>&1 & echo $!';
            exec($command, $op);
            $pid = (int) $op[0];

            $Movie->setPid($pid);

            array_push($movieList, $Movie);
            $jsonContent = $serializer->serialize($movieList, 'json');
            file_put_contents($tempory_folder . '/isipop.data', $jsonContent);
        }
        
        $subtitles = [];
        return $this->render('IsiPopSiteBundle:Stream:stream.html.twig', array(
            'streamUrl' => $HOST->getStreamUrl(),
            'subtitles' => $subtitles));

        
    }

}
