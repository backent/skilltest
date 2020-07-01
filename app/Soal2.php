<?php 
namespace App;

class Soal2 {

    private $startBalance;

    public function __construct($startBalance = 0) {
        $this->startBalance = $startBalance;
    }

    public function getStartBalance() {
        return $this->startBalance;
    }

    public function setStartBalance($number) {
        $this->startBalance = $number;
    }

    public function getAllowedMoney(): array {
        $allowedMoney = [100000, 50000, 20000, 5000, 100, 50];
        return $allowedMoney;
    }

    public function runSoal2() {
        $currentBalance = $this->getStartBalance();
        $allowedMoney = $this->getAllowedMoney();
        $currentIndex = 0;
        $result = [];
        while ($currentIndex < count($allowedMoney))  {
            $count = 0;
            while ($currentBalance - $allowedMoney[$currentIndex] >= 0 ) {
                $currentBalance -= $allowedMoney[$currentIndex];
                $count++;
            }
            $result[] = ["money" => $allowedMoney[$currentIndex], "count" => $count];
            $currentIndex++;
        }


        return $result;
    }

} 
 ?>