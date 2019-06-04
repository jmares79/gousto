<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Service\Recipe\RecipeService;
use App\Service\Rating\RatingService;
use App\Http\Requests\RecipeStorePost;
use App\Http\Requests\RecipeUpdatePost;
use App\Http\Requests\RateRecipePost;

/**
 * @resource Recipe
 *
 * Recipe controller to handle all needed operations for a recipe
 */
class RecipeController extends Controller
{
    protected $recipe;
    protected $rating;

    public function __construct(
        RecipeService $recipe,
        RatingService $rating
    )
    {
        $this->recipe = $recipe;
        $this->rating = $rating;
    }

   /**
    * Returns all the recipes
    *
    * @return mixed A JSON representation of all the recipes
    */
    public function getAll()
    {
        $recipes = $this->recipe->fetchAll();

        return response()->json(['recipes' => $recipes], Response::HTTP_OK);
    }

   /**
    * Returns a recipe by its id
    *
    * @param $id The recipe Id
    * @return mixed A JSON representation of the recipe, or HTTP 404 if not found
    */
    public function getRecipe($id)
    {
        $recipe = $this->recipe->fetchById($id);

        if ($recipe == null) { return response()->json(['recipe' => []], Response::HTTP_NOT_FOUND); }

        return response()->json(['recipe' => $recipe], Response::HTTP_OK);
    }

   /**
    * Rates a recipe, using a secondary file for saving the ratings
    *
    * @param Request $request The request with the payload
    * @param $id The recipe id to be rated
    *
    * @return HTTP 200 On success
    * @return HTTP 422 If payload is not automatically validated
    * @return HTTP 4XX If not successful
    */
    public function rateRecipe(RateRecipePost $request, $id)
    {
        $recipe = $this->recipe->fetchById($id);

        if ($recipe == null) { return response()->json([], Response::HTTP_NOT_FOUND); }

        $rate = $this->getRequestContent($request);

        if (!$this->rating->isValidRate($rate)) {
            response()->json(['message' => "Error while rating recipe $id, invalid rate"], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->rating->rate($id, $rate['rate']);

            return response()->json(['rate' => $rate['rate']], Response::HTTP_OK);
        } catch (RatingCreationException $e) {
            return response()->json(['message' => "Error while rating recipe $id"], Response::HTTP_BAD_REQUEST);
        }
    }

   /**
    * Stores a new recipe. A request validation was created as a RecipeStorePost type.
    *
    * @param RecipeStorePost $request The validation request with the recipe payload
    *
    * @return HTTP 201 On success
    * @return HTTP 422 If payload is not automatically validated
    * @return HTTP 400 If an error on creation arises
    */
    public function storeRecipe(RecipeStorePost $request)
    {
        $recipe = $this->getRequestContent($request);

        try {
            $this->recipe->save($recipe);

            return response()->json(['message' => "Recipe created successfully"], Response::HTTP_CREATED);
        } catch (RecipeCreationException $e) {
            return response()->json(['message' => "Error while creating recipe"], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json(['message' => "Error while creating recipe"], Response::HTTP_BAD_REQUEST);
        }
    }

   /**
    * Updates a new recipe.
    *
    * @param Request $request The request with the payload
    * @param $id The recipe id to be updated
    *
    * @return HTTP 201 On success
    * @return HTTP 422 If payload is not automatically validated
    * @return HTTP 400 If not successful
    */
    public function updateRecipe(RecipeUpdatePost $request)
    {
        $payload = $this->getRequestContent($request);
        $id = $payload['recipe']['id'];

        $recipe = $this->recipe->fetchById($id);

        if ($recipe == null) { return response()->json([], Response::HTTP_NOT_FOUND); }

        try {
            $this->recipe->update($recipe, $payload['recipe']);

            return response()->json(['message' => "Recipe updated successfully"], Response::HTTP_OK);
        } catch (RecipeUpdateException $e) {
            return response()->json(['message' => "Error while creating recipe"], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json(['message' => "Error while creating recipe"], Response::HTTP_BAD_REQUEST);
        }
    }

    protected function getRequestContent(Request $request)
    {
        return json_decode($request->getContent(), true);
    }
}
