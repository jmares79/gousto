<?php

namespace App\Service\Rating;

use App\Interfaces\RatingInterface;
use App\Interfaces\FileReaderInterface;
use App\Interfaces\FileWriterInterface;
use App\Exceptions\RatingCreationException;

/**
 * @resource RatingService
 *
 * Services for providing rating a recipe capabilities to RecipeController
 */
class RatingService implements RatingInterface
{
    protected $header = array('id', 'rate');
    protected $reader;
    protected $writer;

    public function __construct(
        FileReaderInterface $reader,
        FileWriterInterface $writer
    )
    {
        $this->reader = $reader;
        $this->writer = $writer;

        $this->reader->prepareFiles(env('DATA_DIR'), env('RATE_FILE'));
        $this->writer->prepareFiles(env('DATA_DIR'), env('RATE_FILE'));

        if ($this->writer->isFileEmpty()) { $this->writer->writeRow($this->header); }
    }

   /**
    * Rates a recipe
    *
    * @param integer $id The id of the recipe to rate
    * @param integer $rate The given rate for a recipe
    *
    * @return void
    * @throws RatingCreationException On error while rating the recipe
    */
    public function rate($id, $rate)
    {
        $res = $this->writer->writeRow(array($id, $rate));

        if (false == $res) { throw new RatingCreationException("Error rating the recipe", 1); }
    }

   /**
    * Gets a rate row from the data model
    *
    * @param integer $id The id of the recipe to rate
    *
    * @return mixed $row The rate row from data model
    */
    public function getRateRow($id)
    {
        $row = null;

        while ($row = $this->reader->getFileRow()) {
            if ($id == $row[0]) { return $row; }
        }

        return $row;
    }

    /**
     * Checks whether the rate payload has minimum needed data
     *
     * @param mixed $rate The rate to be checked
     *
     * @return boolean
     */
    public function isValidRate($rate)
    {
        return !(null == $rate || !isset($rate['rate']));
    }
}
