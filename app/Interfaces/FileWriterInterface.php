<?php

namespace App\Interfaces;

/**
 *  Interface for a concrete file writer
 */
interface FileWriterInterface
{
    public function writeRow($data);
}
