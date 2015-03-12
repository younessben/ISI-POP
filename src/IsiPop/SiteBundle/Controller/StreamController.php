<?php

namespace IsiPop\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Httpful\Request;

class StreamController extends Controller
{
    public function indexAction($id,$file)
    { 
        // on retourne un lien valable vers un fichier .mp4 dans la page de stream
        
        // GET HOSTNAME 
        $hostname = $this->getRequest()->getHost();
        
        
        $host = "http://".$hostname;
        $port = $this->getPort(8889,8999);
        
        // TEST PORT RETURN TO SHOW ERROR
        /*
         * if($port==-1)
         * {
         * NO PORT AVAILABLE
         * }
         */
        $data = array("TOTO" => 'ok');
     //   $fp = fopen('/tmp/isipop.data','w');
    //    fwrite($fp,json_encode($data));
     //   fclose($fp);
        
        $streamURL = $host.':'.$port;
        
        // $torrent = "https://yts.re/torrent/download/9EB1A2D6A377731FF645A9802293F9C6DDE6F3B1.torrent";
        // 
        // Le seigneur des anneaux
        $id = 3792;
        $torrent = "https://yts.re/torrent/download/EA974AA1432B16C764DA618453CEBCFF7812EAAD.torrent";
        
        $command = 'nohup peerflix '.$torrent.' -p '.$port.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $pid = (int)$op[0];
        
        
        $uri = "https://yts.re/api/v2/movie_details.json?with_images=true&movie_id=".$id;
        
        // the complete parse file movie information
        $response = Request::get($uri)->send();
        if($response->body->status == "error")
        {
            var_dump("LE FILM N'A PAS ETE TROUVER PAS DE SOUS TITRE");
        }
        else
        {
            $title = $response->body->data->title;
            $seeds = $response->body->data->torrents[0]->seeds;
            $peers = $response->body->data->torrents[0]->peers;
            $cover = $response->body->data->images->medium_cover_image;
            $imdb_code = $response->body->data->imdb_code;
        }
     
             $uri = "http://api.yifysubtitles.com/subs/".$imdb_code;
        
        // the complete parse file movie information
            $response = Request::get($uri)->send();            
            $lang = $response->body->subs->$imdb_code;
            
            $listsubtitle = [];
            $tmpzip = "/tmp/tmpzip.zip";
            $titleClean = str_replace(':','', $title);
            $titleClean = str_replace(' ','_', $titleClean);
            
            foreach ($lang as $key => $val) {
                $langSubtitle = $key; // la langue des sous-titres
                $urlsubtitle = "http://www.yifysubtitles.com/".$val[0]->url; // lien vers le sous titre
                file_put_contents($tmpzip, fopen($urlsubtitle, 'r')); // on recupere le fichier zip
                
                $zip = new  \ZipArchive;
                
                if ($zip->open($tmpzip) === true) 
                    {
                        for($i = 0; $i < $zip->numFiles; $i++) 
                        {
                            $filename = $zip->getNameIndex($i);               
                            if(substr(strrchr($filename,'.'),1) == "srt")
                            {
                                $directorySubtitleEncode = "subtitles/".urlencode($titleClean)."/";
                                $directorySubtitleNoEncode = "subtitles/".$titleClean."/";
                                // on verifie que le repertoire des sous-titres existes sinon on le créer
                                if (!file_exists($directorySubtitleNoEncode)) {
                                mkdir($directorySubtitleNoEncode, 0777, true);
                                }
                                
                                // si le fichier sous-titre n'existe pas on le copy
                              if(!file_exists($directorySubtitleNoEncode.$langSubtitle.".srt"))
                            copy("zip://".$tmpzip."#".$filename, $directorySubtitleNoEncode.$langSubtitle.".srt"); 
                            }
                        }                  
                    $zip->close(); 
                    }
                    $subtitle = ['lang' => $langSubtitle,
                'url' => "http://".$hostname."/".$directorySubtitleEncode.$langSubtitle.".srt"];
                    array_push($listsubtitle,$subtitle);
            }
     
        
        return $this->render('IsiPopSiteBundle:main:stream.html.twig',array(
            'streamUrl'  => $streamURL,
            'subtitles' => $listsubtitle));
    }
    // This function return the first port available in a range
    private function getPort($portStart,$portEnd)
    {
        
        for($i = $portStart ; $i <= $portEnd ; $i++)
        {
            if(!@fsockopen("localhost",$i))
            {
            return $i;
            }
        }
        
    }
}
/*
 * exports.create = function(self, streamURL, hostname, params) {
  var getport = require('getport');
  var request = require('request');
  var AdmZip = require('adm-zip');
  var http = require('http');
  var fs = require('fs');
  var opensrt = require('opensrt_js');
  var _ = require('underscore');
  var isWin = process.platform === 'win32';
  getport(8889, 8999, function (e, port) {
    if (e) {
      self.redirect('/');
    } else {
      var osSpecificCommand = isWin ? 'cmd' : 'peerflix';
      var osSpecificArgs = isWin ? ['/c', 'peerflix', decodeURIComponent(params.file),  '--port=' + port] : [decodeURIComponent(params.file),  '--port=' + port];
      var childStream = require('child')({
        command: osSpecificCommand,
        args: osSpecificArgs,
        cbStdout: function(data) {
          console.log(String(data));
        }
      });
      streamURL = "http://" + hostname + ":" + port;
      var subtitles = {};
      // if it's a movie
      if (!params.show || params.show !== '1') {
        request('https://yts.re/api/v2/movie_details.json?with_images=true&movie_id=' + params.id, function (error, response, body) {
          if (!error && response.statusCode == 200) {
            var yifyResponse = JSON.parse(body);
            var data = {};
            data.title = yifyResponse.data.title;
            data.seeds = yifyResponse.data.torrents[0].seeds;
            data.peers = yifyResponse.data.torrents[0].peers;
                    
            // IMAGE : MEDIUM              
            data.cover = yifyResponse.data.images.medium_cover_image;
            // fetch subtitles
            request('http://api.yifysubtitles.com/subs/' + yifyResponse.data.imdb_code, function (error, response, body) {
              if (!error && response.statusCode == 200) {
                var yifySubsResponse = JSON.parse(body);
                // download a subtitle
                function fetchSub (url, dest, lang, callBack) {
                  var file = fs.createWriteStream(dest);
                  var request = http.get(url, function(response) {
                    response.pipe(file);
                    file.on('finish', function() {
                      file.close(callBack(dest, lang));
                    });
                  });
                }
                // unzip
                function unzip (dest, lang) {
                  var zip = new AdmZip(dest);
                  var zipEntries = zip.getEntries();
                  zipEntries.forEach(function(zipEntry) {
                      var fileName = zipEntry.entryName.toString();
                      var i = fileName.lastIndexOf('.');
                      if (fileName.substr(i) == '.srt') { // Only unzip the srt file
                        var dir = "public/subtitles/" + yifyResponse.data.title + '/';
                        zip.extractEntryTo(fileName, dir , false, true);
                        fs.renameSync(dir + fileName, dir + lang + '.srt'); // Rename to language.srt
                      }
                      fs.unlinkSync(dest); // Remove the zip
                  });
                }
                for (var subs in yifySubsResponse.subs) {
                  for (var lang in yifySubsResponse.subs[subs]) {
                    var subUrl = 'http://www.yifysubtitles.com' + _.max(yifySubsResponse.subs[subs][lang], function(s){return s.rating;}).url
                    fetchSub(subUrl, 'public/subtitles/' + lang + '.zip', lang, unzip);
                    // Build the subtitle url
                    subtitles[lang] = 'http://' + hostname + ':' + geddy.config.port + '/subtitles/';
                    subtitles[lang] += encodeURIComponent(yifyResponse.data.title) + '/' + lang + '.srt';
                  }
                }
                childStream.start(function(pid){
                  geddy.config.streamingProcesses.push({
                    pid: pid,
                    child: childStream,
                    torrent: decodeURIComponent(params.file),
                    stream: streamURL,
                    data: data,
                    subtitles: subtitles
                  });
                });
                self.respond({
                  params: params,
                  streamURL: streamURL,
                  subtitles: subtitles
                }, {
                  format: 'html',
                  template: 'app/views/main/stream'
                });
              }
            });
          }
        });
      }
      // else if it's a tv show
      else {
        request('http://eztvapi.re/show/' + params.id, function (error, response, body) {
          if (!error) {
            var show = JSON.parse(body);
            var data = {};
            data.title = show.title + ' S' + params.season + 'E' + params.episode;
            data.seeds = '0';
            data.peers = '0';
            data.cover = show.images.poster;
            var fileName = params.file.split("&");
            for (var i=0; i<fileName.length; i++) {
               tmp = fileName[i].split("=");
               if ( [tmp[0]] == "dn" ) { fileName = tmp[1]; }
             }
            // prepare the query to fetch tv show subtitles
            var query = {
              imdbid: params.id,
              season: params.season,
              episode: params.episode,
              filename: fileName
            }
            // Fetch subtitles
            opensrt.searchEpisode(query, function(err, res){
              if(err) return console.error("Error: " + err);
              for (var lang in res) {
                subtitles[lang] = res[lang].url;
              }
              childStream.start(function(pid){
                geddy.config.streamingProcesses.push({
                  pid: pid,
                  child: childStream,
                  torrent: decodeURIComponent(params.file),
                  stream: streamURL,
                  data: data,
                  subtitles: subtitles
                });
              });
              self.respond({
                params: params,
                streamURL: streamURL,
                subtitles: subtitles
              }, {
                format: 'html',
                template: 'app/views/main/stream'
              });
            })
          }
        });
      }
    }
  });
};
 */