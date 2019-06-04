<?php

namespace Tests\Feature;

use Tests\RecipeTest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;

class RecipeControllerTest extends RecipeTest
{
    /**
     * Tests the HTTP GET all recipes request
     *
     * @dataProvider getAllRecipesProvider
     *
     * @return void
     */
    public function testGetAllRecipes($httpStatus, $returnedResponseStructure)
    {
        $response = $this->get(route('get-all-recipes'));

        $this->assertActualResponse($response, $httpStatus, $returnedResponseStructure);
    }

    /**
     * Tests the HTTP GET a recipe by Id request
     *
     * @dataProvider getRecipeProvider
     *
     * @return void
     */
    public function testGetRecipe($id, $httpStatus, $returnedResponseStructure)
    {
        $response = $this->get(route('get-recipe', ['id' => $id]));

        $this->assertActualResponse($response, $httpStatus, $returnedResponseStructure);
    }

    /**
     * Tests the HTTP POST rate a recipe
     *
     * @dataProvider getRateRecipeProvider
     *
     * @return void
     */
    public function testRateRecipe($id, $payload, $httpStatus, $returnedResponseStructure)
    {
        //Alternatively, you can use this request, as both with JSON or AJAX calls the form validator will work
        // $response = $this->post(self::HTTP_RATE_RECIPE. $id, $payload, ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']);
        $response = $this->json('POST', route('rate-recipe', ['id' => $id]), $payload);

        $this->assertActualResponse($response, $httpStatus, $returnedResponseStructure);
    }

    /**
     * Tests the HTTP POST store of a recipe
     *
     * @dataProvider storeRecipeProvider
     *
     * @return void
     */
    public function testStoreRecipe($payload, $httpStatus, $expectedResponse)
    {
        $response = $this->json('POST', route('store-recipe'), $payload);

        $this->assertActualResponse($response, $httpStatus, $expectedResponse);
    }

    /**
     * Tests the HTTP POST update of a recipe
     *
     * @dataProvider updateRecipeProvider
     *
     * @return void
     */
    public function testUpdateRecipe($payload, $httpStatus, $expectedResponse)
    {
        $response = $this->json('PUT', route('update-recipe'), $payload);

        $this->assertActualResponse($response, $httpStatus, $expectedResponse);
    }

    protected function assertActualResponse($response, $httpStatus, $expectedResponse)
    {
        $response->assertStatus($httpStatus);
        $response->assertJsonStructure($expectedResponse);
    }

    public function getAllRecipesProvider()
    {
        return array(
            array(Response::HTTP_OK, $this->getHttpGetAllRecipesResponseStructure())
        );
    }

    public function getRecipeProvider()
    {
        return array(
            array(self::EXISTENT_ID, Response::HTTP_OK, $this->getHttpGetRecipeByIdResponseStructure()),
            array(self::NONEXISTENT_ID, Response::HTTP_NOT_FOUND, $this->notFoundHttpRateResponse)
        );
    }

    public function getRateRecipeProvider()
    {
        return array(
            array(self::EXISTENT_ID, ['rate' => self::VALID_RATE], Response::HTTP_OK, $this->validHttpRateResponse),
            array(self::NONEXISTENT_ID, ['rate' => self::VALID_RATE], Response::HTTP_NOT_FOUND, $this->notFoundHttpRateResponse),
            array(self::EXISTENT_ID, ['rate' => self::INVALID_RATE], Response::HTTP_UNPROCESSABLE_ENTITY, $this->notFoundHttpRateResponse),
            array(self::EXISTENT_ID, ['rate' => self::INVALID_RATE_TYPE], Response::HTTP_UNPROCESSABLE_ENTITY, $this->notFoundHttpRateResponse),
        );
    }

    public function storeRecipeProvider()
    {
        return array(
            array($this->validRecipeStorePayload, Response::HTTP_CREATED, ['message']),
            array($this->invalidRecipeStorePayload, Response::HTTP_UNPROCESSABLE_ENTITY, ['recipe.updated_at']),
        );
    }

    public function updateRecipeProvider()
    {
        return array(
            array($this->validRecipeUpdatePayload, Response::HTTP_OK, ['message']),
            array($this->invalidRecipeUpdatePayload, Response::HTTP_UNPROCESSABLE_ENTITY, ['recipe.id']),
        );
    }
}
