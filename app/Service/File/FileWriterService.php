<?php

namespace App\Service\File;

use App\Interfaces\FileWriterInterface;

/**
 * @resource FileService
 *
 * Class for implementing a file writer service
 */
class FileWriterService extends FileService implements FileWriterInterface
{
    /**
     * Writes a data row to the configured file
     *
     * @param string|mixed $data A row to be written to file, either in array format to CSV or a string
     *
     * @return integer|boolean Length of written data or false on error
     */
    public function writeRow($data)
    {
        return fputcsv($this->handler, $data);
    }
}
