<?php

namespace App\Service\Stream;

use App\Interfaces\StreamHandlerInterface;
use App\Service\File\FileReaderService;
use App\Service\File\FileWriterService;

/**
 * @resource StreamHandlerService
 *
 * Services for handling stream data IO
 */
class StreamHandlerService implements StreamHandlerInterface
{
    protected $reader;
    protected $writer;
    protected $data = array();
    protected $lastPositionRead;

    public function __construct(
        FileReaderService $reader,
        FileWriterService $writer
    )
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function setStreamDirectory($dir)
    {
        $this->reader->setFileDirectory($dir);
        $this->writer->setFileDirectory($dir);
    }

    public function setStreamName($name)
    {
        $this->reader->setFileName($name);
        $this->writer->setFileName($name);
    }

    public function getLastReadPosition()
    {
        return $this->lastPositionRead;
    }

    /**
     * Fetch all recipes from a stream
     *
     * @param internal $reader Service
     *
     * @return An array with all the data to be shown
     */
    public function fetchAllRows()
    {
        $this->reader->openStream();

        $this->reader->parseHeader();
        $data = $this->getData();

        return $data;
    }

    /**
     * Fetch all recipes from a stream
     *
     * @param int $id Recipe Id
     * @param internal $reader Service
     *
     * @return An array with all the data to be shown
     */
    public function fetchRow($id)
    {
        $this->reader->openStream();

        $this->reader->parseHeader();
        $data = $this->getData($id);

        return $data;
    }

    /**
     * Writes a row in the file via its service
     *
     * @param string|mixed $row
     *
     * @return integer|boolean Length of written data or false on error
     */
    public function setRow($row, $mode = null)
    {
        $this->writer->openStream();

        return $this->writer->writeRow($row);
    }

    public function getFileAsArray()
    {
        return $this->reader->fileToArray();
    }

    /**
     * Updates a row in the data file
     *
     * @param mixed $updatedRows The array with the updated rows
     *
     * @return void
     * @throws RecipeUpdateException On update error
     */
    public function updateData($updatedRows)
    {
        $this->writer->openStream('w');

        $this->writer->writeRow($this->reader->getHeader());

        foreach ($updatedRows as $recipe) {
            $this->writer->writeRow($recipe);
        }
    }

   /**
     * Get recipe information from a file row
     *
     * @param int $id
     *
     * @return An array with the header and recipes
     */
    public function getData($id = null)
    {
        $this->data = [];
        $header = $this->reader->getHeader();

        while ($row = $this->reader->getFileRow()) {
            if ($id == null || $id == $row[0]) {
                $this->data[] = array_combine($header, $row);
            }
        }

        return $this->data;
    }
}

