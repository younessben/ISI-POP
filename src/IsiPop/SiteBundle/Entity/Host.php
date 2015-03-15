<?php
namespace IsiPop\SiteBundle\Entity;

class Host
{
    private $portHost;
    private $hostname;
    private $portStream;

    /**
     * Set portHost
     *
     * @param integer $portHost
     * @return Host
     */
    public function setPortHost($portHost)
    {
        $this->portHost = $portHost;

        return $this;
    }

    /**
     * Get portHost
     *
     * @return integer 
     */
    public function getPortHost()
    {
        return $this->portHost;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Host
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set portStream
     *
     * @param integer $portStart
     * Port start 
     * @param integer $portEnd
     * Port End
     * @return Host
     */
    public function setPortStream($portStart,$portEnd)
    {
        $this->portStream = $this->getPort($portStart, $portEnd);

        return $this;
    }

    /**
     * Get portStream
     *
     * @return integer 
     */
    public function getPortStream()
    {
        return $this->portStream;
    }
    
    public function getStreamUrl()
    {
        return 'http://'.$this->getHostname().':'.$this->getPortStream();
    }
    
    public function getHostUrl()
    {
        return 'http://'.$this->getHostname().':'.$this->getPortHost();
    }
    
    // return 
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
