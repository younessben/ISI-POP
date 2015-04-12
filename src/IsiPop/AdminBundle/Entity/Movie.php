<?php

namespace IsiPop\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     */
    private $urlMovie;

    /**
     * @var string
     */
    private $imdbCode;

    /**
     * @var string
     */
    private $urlCover;


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
     * Set urlMovie
     *
     * @param string $urlMovie
     * @return Movie
     */
    public function setUrlMovie($urlMovie)
    {
        $this->urlMovie = $urlMovie;

        return $this;
    }

    /**
     * Get urlMovie
     *
     * @return string 
     */
    public function getUrlMovie()
    {
        return $this->urlMovie;
    }

    /**
     * Set imdbCode
     *
     * @param string $imdbCode
     * @return Movie
     */
    public function setImdbCode($imdbCode)
    {
        $this->imdbCode = $imdbCode;

        return $this;
    }

    /**
     * Get imdbCode
     *
     * @return string 
     */
    public function getImdbCode()
    {
        return $this->imdbCode;
    }

    /**
     * Set urlCover
     *
     * @param string $urlCover
     * @return Movie
     */
    public function setUrlCover($urlCover)
    {
        $this->urlCover = $urlCover;

        return $this;
    }

    /**
     * Get urlCover
     *
     * @return string 
     */
    public function getUrlCover()
    {
        return $this->urlCover;
    }
}
