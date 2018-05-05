<?php

class Museum {

    private $nom; 
    private $description_fr;
    private $description_en; 
    private $description_it; 
    private $description_de; 
    private $images;
    private $horaire;
    private $lieu;
    private $categories; 
    private $canton; 
    private $rue;
    private $code_postal;
    private $directions; 
    private $criteres; 
    private $prix;
    private $phone; 
    private $fax;
    private $email;
    private $website;

    public function __construct(){ 
         
    }
    
    //Setters
    public function setNom($value) {
        $this->nom = $value;
    }
    public function setDescription_fr($value) {
        $this->description_fr = $value;
    }
    public function setDescription_en($value) {
        $this->description_en = $value;
    }
    public function setDescription_it($value) {
        $this->description_it = $value;
    }
    public function setDescription_de($value) {
        $this->description_de = $value;
    }
    public function setImages($value) {
        $this->images = $value;
    }
    public function setHoraire($value) {
        $this->horaire = $value;
    }
    public function setLieu($value) {
        $this->lieu = $value;
    }
    public function setCategories($value) {
        $this->categories = $value;
    }
    public function setCanton($value) {
        $this->canton = $value;
    }
    public function setRue($value) {
        $this->rue = $value;
    }
    public function setCode_postal($value) {
        $this->code_postal = $value;
    }
    public function setDirections($value) {
        $this->directions = $value;
    }
    public function setCriteres($value) {
        $this->criteres = $value;
    }
    public function setPrix($value) {
        $this->prix = $value;
    }
    public function setPhone($value) {
        $this->phone = $value;
    }
    public function setFax($value) {
        $this->fax = $value;
    }
    public function setEmail($value) {
        $this->email = $value;
    }
    public function setWebsite($value) {
        $this->website = $value;
    }

    //Getters
    public function getNom() {
        return $this->nom;
    }
    public function getDescription_fr() {
        return $this->description_fr;
    }
    public function getDescription_en() {
        return $this->description_en;
    }
    public function getDescription_it() {
        return $this->description_it;
    }
    public function getDescription_de() {
        return $this->description_de;
    }
    public function getImages() {
        return $this->images;
    }
    public function getHoraire() {
        return $this->horaire;
    }
    public function getLieu() {
        return $this->lieu;
    }
    public function getCategories() {
        return $this->categories;
    }
    public function getCanton() {
        return $this->canton;
    }
    public function getRue() {
        return $this->rue;
    }
    public function getCode_postal() {
        return $this->code_postal;
    }
    public function getDirections() {
        return $this->directions;
    }
    public function getCriteres() {
        return $this->criteres;
    }
    public function getPrix() {
        return $this->prix;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getFax() {
        return $this->fax;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getWebsite() {
        return $this->website;
    }
}

?>