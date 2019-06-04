<?php

namespace App\Service\File;

use App\Exceptions\FileParsingException;
use App\Interfaces\FileReaderInterface;

/**
 * @resource FileService
 *
 * Class for implementing a file reader service
 */
class FileReaderService extends FileService implements FileReaderInterface
{
    /**
     * Parses the header of the stream
     *
     * @return An array with the header data
     */
    public function parseHeader()
    {
        $this->header = fgetcsv($this->handler);
    }

    /**
     * Parses and return a row of the stream
     *
     * @return An array with the row data
     */
    public function getFileRow()
    {
        return fgetcsv($this->handler);
    }
}
