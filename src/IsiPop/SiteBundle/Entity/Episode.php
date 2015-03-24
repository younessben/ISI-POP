<?php
namespace IsiPop\SiteBundle\Entity;

class Episode
{
     /**
     * @var integer
     */
    private $tvbdid;
    
    public function getTvbdId()
    {
        return $this->tvbdid;
    }
    
    public function setTvbdId($tvbdid)
    {
        $this->tvbdid = $tvbdid;

        return $this;
    }
    
         /**
     * @var integer
     */
    private $season;
    
    public function getSeason()
    {
        return $this->season;
    }
    
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }
    
         /**
     * @var integer
     */
    private $episode;
    
    public function getEpisode()
    {
        return $this->episode;
    }
    
    public function setEpisode($episode)
    {
        $this->episode = $episode;

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
    private $firstAired;
    
    public function getFirstAired()
    {
        return $this->firstAired;
    }
    
    public function setFirstAired($firstAired)
    {
        $this->firstAired = $firstAired;

        return $this;
    }
    
    /**
     * @var string
     */
    private $overview;
    
    public function getOverview()
    {
        return $this->overview;
    }
    
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
    }
    
    /**
     * @var string
     */
    private $defaultUrl;
    
    public function getDefaultUrl()
    {
        return $this->defaultUrl;
    }
    
    public function setDefaultUrl($defaultUrl)
    {
        $this->defaultUrl = $defaultUrl;

        return $this;
    }
    
        /**
     * @var string
     */
    private $midUrl;
    
    public function getMidUrl()
    {
        return $this->midUrl;
    }
    
    public function setMidUrl($MidUrl)
    {
        $this->midUrl = $MidUrl;

        return $this;
    }
    
        /**
     * @var string
     */
    private $highUrl;
    
    public function getHighUrl()
    {
        return $this->highUrl;
    }
    
    public function setHighUrl($highUrl)
    {
        $this->highUrl = $highUrl;

        return $this;
    }
}

