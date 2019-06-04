<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class RecipeTest extends TestCase
{
    const EXISTENT_ID = 1;
    const NONEXISTENT_ID = 1000;
    const VALID_RATE = 1;
    const INVALID_RATE = 6;
    const INVALID_RATE_TYPE = 'X';

    protected $commonGetHttpStructure = [
        'id',
        'created_at',
        'updated_at',
        'box_type',
        'title',
        'slug',
        'short_title',
        'marketing_description',
        'calories_kcal' ,
        'protein_grams',
        'fat_grams',
        'carbs_grams',
        'bulletpoint1',
        'bulletpoint2',
        'bulletpoint3',
        'recipe_diet_type_id',
        'season' ,
        'base',
        'protein_source',
        'preparation_time_minutes',
        'shelf_life_days',
        'equipment_needed',
        'origin_country',
        'recipe_cuisine',
        'in_your_box',
        'gousto_reference'
    ];

    protected $validHttpRateResponse = ['rate'];
    protected $notFoundHttpRateResponse = [];
    protected $validRecipeStorePayload = [
        "recipe" => [
            "created_at" => "30/06/2015 17:58:00",
            "updated_at" => "30/06/2015 17:58:00",
            "box_type" => "vegetarian",
            "title" => "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
            "slug" => "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
            "short_title" => "",
            "marketing_description" => "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
            "calories_kcal" => "401",
            "protein_grams" => "12",
            "fat_grams" => "35",
            "carbs_grams" => "0",
            "bulletpoint1" => "",
            "bulletpoint2" => "",
            "bulletpoint3" => "",
            "recipe_diet_type_id" => "meat",
            "season" => "all",
            "base" => "noodles",
            "protein_source" => "beef",
            "preparation_time_minutes" => "35",
            "shelf_life_days" => "4",
            "equipment_needed" => "Appetite",
            "origin_country" => "Great Britain",
            "recipe_cuisine" => "asian",
            "in_your_box" => "",
            "gousto_reference" => "59"
        ]
    ];
    protected $invalidRecipeStorePayload = [
        "recipe" => [
            "created_at" => "30/06/2015 17:58:00"
        ]
    ];
    protected $validRecipeUpdatePayload = [
        "recipe" => [
            "id" => "1",
            "box_type" => "mixed fusion",
            "short_title" => "An asian fusion box with a little bit of everything"
        ]
    ];
    protected $invalidRecipeUpdatePayload = [
        "recipe" => [
            "box_type" => "mixed fusion",
            "short_title" => "An asian fusion box with a little bit of everything"
        ]
    ];

    protected function getCommonStructure()
    {
        return $this->commonGetHttpStructure;
    }

    protected function getHttpGetAllRecipesResponseStructure()
    {
        return [
            'recipes' => [
                '*' => $this->getCommonStructure()
            ]
        ];
    }

    protected function getHttpGetRecipeByIdResponseStructure()
    {
        return [
            'recipe' => $this->getCommonStructure()
        ];
    }
}
