<?php 

namespace App;

class Soal1 {
    private $startWidth;
    private $maxWidth;

    public function __construct($startWidth = 1, $maxWidth = 2) {
        $this->$startWidth = $startWidth;
        $this->maxWidth = $maxWidth;
    }

    private function getOperators(): array {
        $operators = ['+', '-', '*'];
        return $operators;
    } 

    public function setStartWidth($number) {
        $this->startWidth = $number;
    }

    public function getStartWidth() {
        return $this->startWidth;
    }

    public function setMaxWidth($number) {
        $this->maxWidth = $number;
    }

    public function getMaxWidth() {
        return $this->maxWidth;
    }

    public function runSoal1() {
        $operators = $this->getOperators();
        $this->setMaxWidth(5);
        $this->setStartWidth(1);
        $startWidth = $this->getStartWidth();
        $maxWidth = $this->getMaxWidth();
        $currentWidth = $startWidth;
        $lastIndex = 0;
        $result = "";

        for ($lastIndex; $currentWidth < $maxWidth; $lastIndex++) { 
            if (count($operators) == $lastIndex) {
                    $lastIndex = 0;
            } 
            
            for ($k=0; $k < $currentWidth ; $k++) { 
                
                $result .= $operators[$lastIndex];
            }
            $result .= "<br>";
            $currentWidth++;

        }
        for ($lastIndex; $currentWidth >= $startWidth ; $lastIndex++) { 
            if (count($operators) == $lastIndex) {
                    $lastIndex = 0;
            } 
            for ($k=0; $k < $currentWidth ; $k++) { 
                $result .= $operators[$lastIndex];
            }
            $result .= "<br>";
            $currentWidth--;
        }

        return $result;

        
    }
}
 ?>