<?php

namespace App\Interfaces;

interface DataTransactionInterface
{
    public function fetchAll();
    public function fetchById($id);
    public function save($recipe);
}
