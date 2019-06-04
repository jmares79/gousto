<?php

namespace App\Interfaces;

interface StreamHandlerInterface
{
    public function fetchAllRows();
    public function fetchRow($id);
}
