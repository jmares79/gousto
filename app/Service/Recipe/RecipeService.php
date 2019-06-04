<?php

namespace App\Service\Recipe;

use App\Interfaces\DataTransactionInterface;
use App\Interfaces\StreamHandlerInterface;
use App\Service\Stream\StreamHandlerService;
use App\Interfaces\RatingInterface;
use App\Exceptions\RecipeCreationException;
use App\Exceptions\RecipeUpdateException;

/**
 * @resource RecipeService
 *
 * Services for providing data access and managing capabilities to RecipeController
 */
class RecipeService implements DataTransactionInterface
{
   /**
    * @var $stream
    *
    * Stream Handler service for accesing low level data stream
    */
    protected $stream;

   /**
    * @var $rating
    *
    * Stream Rating service for rating recipes
    */
    protected $rating;

    public function __construct(
        StreamHandlerService $stream,
        RatingInterface $rating
    )
    {
        $this->stream = $stream;
        $this->rating = $rating;

        $this->stream->setStreamDirectory(env('DATA_DIR'));
        $this->stream->setStreamName(env('RECIPE_FILE'));
    }

   /**
    * Get all the recipes from a data model
    *
    * @return mixed
    */
    public function fetchAll()
    {
        return $this->stream->fetchAllRows();
    }

   /**
    * Gets a recipe by its Id
    *
    * @return mixed|null An array representation of a recipe, or null if the recipe does not exists
    */
    public function fetchById($id)
    {
        $recipe = isset($this->stream->fetchRow($id)[0]) ? $this->stream->fetchRow($id)[0] : null;

        return $recipe;
    }

   /**
    * Saves a recipe in a data stream, using a writer service
    *
    * @param mixed $recipe Array with recipe data
    *
    * @return void
    * @throws RecipeCreationException On creation error
    */
    public function save($recipe)
    {
        $recipe = $recipe['recipe'];

        $id = $this->getNextIdForCreation();
        $recipe = array('id' => (string)$id) + $recipe;

        if ($this->stream->setRow($recipe) == false) throw new RecipeCreationException();
    }

   /**
    * Updates a recipe
    *
    * @param mixed $recipe Array with recipe data
    * @param mixed $payload Array with fields with new values to be updated
    *
    * @return void
    */
    public function update($recipe, $payload)
    {
        $updatedRecipe = $this->updateRowValues($recipe, $payload);
        $recipes = $this->fetchAll();

        foreach ($recipes as $key => $recipe) {
            if ($recipe['id'] == $updatedRecipe['id']) {
                $recipes[$key] = $updatedRecipe;
            }
        }

        $this->stream->updateData($recipes);
    }

   /**
    * Calculates the following Id for creating a new row in the recipes file
    *
    * @return integer The new Id for inserting in the recipe model
    */
    protected function getNextIdForCreation()
    {
        $maxId = 0;
        $rows = $this->fetchAll();

        foreach ($rows as $key => $recipe) {
            if ((int)$recipe['id'] > $maxId) { $maxId = (int)$recipe['id']; }
        }

        return ++$maxId;
    }

   /**
    * Updates a recipe row with the new values passed
    *
    * @param mixed $recipe Array with recipe data
    * @param mixed $newValues Array with values to be updated
    *
    * @return mixed An updated array as the row with updated values
    */
    protected function updateRowValues($recipe, $newValues)
    {
        return array_replace($recipe, $newValues);
    }
}
