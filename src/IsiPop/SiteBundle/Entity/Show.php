<?php

namespace IsiPop\SiteBundle\Entity;

/**
 * Show
 */
class Show
{
      /**
     * @var integer
     */
    private $id;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    
    /**
     * @var string
     */
    private $title;
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @var string
     */
    private $poster;
    
    public function getPoster()
    {
        return $this->poster;
    }
    
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @var string
     */
    private $fanart;
    
    public function getFanart()
    {
        return $this->fanart;
    }
    
    public function setFanart($fanart)
    {
        $this->fanart = $fanart;

        return $this;
    }

    /**
     * @var string
     */
    private $banner;
    
    public function getBanner()
    {
        return $this->banner;
    }
    
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }
    /**
     * @var integer
     */
    private $imbd;
 
    public function getImbd()
    {
        return $this->imbd;
    }
    
    public function setImbd($imbd)
    {
        $this->imbd = $imbd;

        return $this;
    }    
      /**
     * @var integer
     */
    private $numSeasons;
    
    public function getNumSeasons()
    {
        return $this->numSeasons;
    }
    
    public function setNumSeasons($numSeasons)
    {
        $this->numSeasons = $numSeasons;

        return $this;
    }    
     /**
     * @var integer
     */
    private $year;
    
    public function getYear()
    {
        return $this->year;
    }
    
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }  
    
         /**
     * @var list objects
     */
    private $seasons;
    
    public function getSeasons()
    {
        return $this->seasons;
    }
    
    public function setSeasons($seasons)
    {
        $this->seasons = $seasons;

        return $this;
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

