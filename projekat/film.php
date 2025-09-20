<?php 
class Film{
    private $ime;
    private $godina;
    private $reziser;
    private $glumci;
    private $trajanje;
    private $id;

    public function getIme(){
        return $this->ime;
    }
    public function getGodina(){
        return $this->godina;
    }
    public function getReziser(){
        return $this->reziser;
    }
    public function getGlumci(){
        return $this->glumci;
    }
    public function getTrajanje(){
        return $this->trajanje;
    }
    public function getId(){
        return $this->id;
    }

    public function __construct($ime, $godina, $reziser, $glumci, $trajanje, $id){
        $this->ime=$ime;
        $this->godina=$godina;
        $this->reziser=$reziser;
        $this->glumci=$glumci;
        $this->trajanje=$trajanje;
        $this->id=$id;
    }
}
?>