<?php

namespace IsiPop\SiteBundle\Entity;


/**
 * Subtitle
 */
class Subtitle
{
    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $urlFile;
    
    /**
     * @var string
     */
    private $urlStream;
    /**
     * Set lang
     *
     * @param string $lang
     * @return Subtitle
     */
    
    private $directoryEncode;
    private $directoryNoEncode;
    
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }
    
    public function setDirectoryEncode($directoryEncode)
    {
        $this->directoryEncode = $directoryEncode;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getDirectoryEncode()
    {
        return $this->directoryEncode;
    }

    
    public function setDirectoryNoEncode($directoryNoEncode)
    {
        $this->directoryNoEncode = $directoryNoEncode;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getDirectoryNoEncode()
    {
        return $this->directoryNoEncode;
    }
    /**
     * Set url
     *
     * @param string $url
     * @return Subtitle
     */
    public function setUrlFile($urlFile)
    {
        $this->urlFile = $urlFile;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrlFile()
    {
        return $this->urlFile;
    }
    
       /**
     * Set url
     *
     * @param string $url
     * @return Subtitle
     */
    public function setUrlStream($urlStream)
    {
        $this->urlStream = $urlStream;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrlStream()
    {
        return $this->urlStream;
    }
    
    public function getFile()
    {
        return $this->getDirectoryNoEncode().$this->getLang().".srt";
    }
    
}
