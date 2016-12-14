<?php

namespace EHSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="EHSBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=10)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=255)
     */
    private $originalName;

    /**
     * article images
     *
     * @ORM\ManyToMany(targetEntity="EHSBundle\Entity\Article", mappedBy="images")
     */
    private $articles;


    /**
     * image events
     *
     * @ORM\ManyToMany(targetEntity="EHSBundle\Entity\Event", mappedBy="images")
     */
    private $events;


    private $file;

    /**
     * @var string
     */
    private $fileToDelete;

    /**
     * Image constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return Image
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFileToDelete()
    {
        return $this->fileToDelete;
    }

    /**
     * @param mixed $fileToDelete
     */
    public function setFileToDelete($fileToDelete)
    {
        $this->fileToDelete = $fileToDelete;
    }



    protected function getUploadRootDir(){
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir(){
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document/image dans la vue.
        return 'uploads/images/';
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     */
    public function preUpload(){
        if (NULL === $this->file){
            return;
        }
        //le nom du fichier est son id, on stock juste son extension
        $this->extension=$this->file->getClientOriginalExtension();
        $this->originalName=$this->file->getClientOriginalName();
        $this->name='image';
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(){
        if (NULL === $this->file){
            return;
        }
        // on déplace le fichier envoyé dans le répertoire d'upload
        $this->file->move($this->getUploadRootDir(),$this->id.'.'.$this->extension);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        $this->fileToDelete = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(){
        //en postRemove on n'a pas accès à l'id
        if(file_exists($this->fileToDelete)){
            // on supprime le fichier
            unlink($this->fileToDelete);
        }
    }

}
