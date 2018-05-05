<?php

class Exposition {
    private $nom;
    private $description;
    private $images;
    private $start;
    private $finish;
    private $museum_id;

    public function __construct(){ 
         
    }
    
    //Setters
    public function setNom($value) {
        $this->nom = $value;
    }

    public function setDescription($value) {
        $this->description = $value;
    }

    public function setImages($value) {
        $this->images = $value;
    }

    public function setStart($value) {
        $this->start = $value;
    }

    public function setFinish($value) {
        $this->finish = $value;
    }

    public function setMuseumid($value) {
        $this->museum_id = $value;
    }

    //Getters
    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImages() {
        return $this->images;
    }

    public function getStart() {
        return $this->start;
    }

    public function getFinish() {
        return $this->finish;
    }

    public function getMuseumid() {
        return $this->museum_id;
    }
}

?>