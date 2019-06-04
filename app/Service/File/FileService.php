<?php

namespace App\Service\File;

/**
 * @resource FileService
 *
 * Abstract class for implementing a file services
 */
class FileService
{
    const STD_MODE = 'a+';

    protected $fileDirectory;
    protected $fileName;
    protected $handler;
    protected $mode;

    public function __construct()
    {
        $this->mode = self::STD_MODE;
    }

    public function __destruct()
    {
        if ($this->handler != null) { fclose($this->handler); }
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setFileDirectory($dir)
    {
        $this->fileDirectory = base_path($dir);
    }

    public function setFileName($name)
    {
        $this->fileName = $name;
    }

    /**
     * Opens a file/stream
     *
     * @param string $openMode Optional open mode
     *
     * @return void
     * @throws FileParsingException On error while opening the sile the recipe
     */
    public function openStream($openMode = null)
    {
        $mode = $openMode == null ? $this->mode : $openMode;

        if (!$this->handler = fopen($this->getFullPath(), $mode)) throw new FileParsingException();
    }

    /**
     * Closes a file/stream
     *
     * @return void
     */
    public function closeStream()
    {
        fclose($this->handler);
    }

    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set the directory and name for a file, and open it for proper use
     *
     * @param string $dir File directory
     * @param string $name File name
     * @return void
     */
    public function prepareFiles($dir, $name)
    {
        // var_dump($dir, $name);
        $this->setFileDirectory($dir);
        $this->setFileName($name);

        $this->openStream();
    }

    /**
     * Checks if the file was created
     *
     * @return boolean
     */
    public function isFileCreated()
    {
        return file_exists($this->getFullPath());
    }

    /**
     * Checks if the file is empty
     *
     * @return boolean
     */
    public function isFileEmpty()
    {
        clearstatcache();
        return filesize($this->getFullPath()) === 0;
    }

    /**
     * Returns the info in the file into an array
     *
     * @return mixed
     */
    public function fileToArray()
    {
        return file($this->getFullPath());
    }


    /**
     * Returns the full path of the file
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fileDirectory . DIRECTORY_SEPARATOR .  $this->fileName;
    }
}
