<?php

namespace IsiPop\SiteBundle\Entity;

/**
 * Movie
 */
class Movie
{
      /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $seeds;

    /**
     * @var integer
     */
    private $peers;

    /**
     * @var string
     */
    private $cover;

    /**
     * @var string
     */
    private $imdb;
    
      /**
     * @var string
     */
    private $url;

     /**
     * @var integer
     */
    private $pid;
    
     /**
     * @var integer
     */
    private $port;

    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * Set id
     *
     * @param integer $id
     * @return Movie
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
     /**
     * Set title
     *
     * @param string $title
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set seeds
     *
     * @param integer $seeds
     * @return Movie
     */
    public function setSeeds($seeds)
    {
        $this->seeds = $seeds;

        return $this;
    }

    /**
     * Get seeds
     *
     * @return integer 
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     * Set peers
     *
     * @param integer $peers
     * @return Movie
     */
    public function setPeers($peers)
    {
        $this->peers = $peers;

        return $this;
    }

    /**
     * Get peers
     *
     * @return integer 
     */
    public function getPeers()
    {
        return $this->peers;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return Movie
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string 
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set imdb
     *
     * @param string $imdb
     * @return Movie
     */
    public function setImdb($imdb)
    {
        $this->imdb = $imdb;

        return $this;
    }

    /**
     * Get imdb
     *
     * @return string 
     */
    public function getImdb()
    {
        return $this->imdb;
    }
    
     /**
     * Set url
     *
     * @param string $url
     * @return Movie
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    
        /**
     * Set pid
     *
     * @param integer $pid
     * @return Movie
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }
    
    
          /**
     * Set port
     *
     * @param integer $port
     * @return Movie
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }
    
    public function getCleanTitle()
    {
        $titleClean = $this->getTitle();
        
        $titleClean = str_replace(':','', $titleClean);
        $titleClean = str_replace("'",'', $titleClean);
        $titleClean = str_replace(' ','_', $titleClean);
        
        return $titleClean;
    }
    
    public function getCleanTitleEncode()
    {
        return urlencode($this->getCleanTitle());
    }
   
   
}
