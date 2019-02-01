<?php
namespace App\Api\V1\Interfaces;

interface CheckBoardDupplicationInterface {

    public function getModelType();
    public function getUniqueId();
    public function getScanner();
    public function getBoard();
    public function checkBoardDupplication();
}