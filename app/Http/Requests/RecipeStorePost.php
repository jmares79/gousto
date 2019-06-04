<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeStorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "recipe.id" => "nullable|integer",
            "recipe.created_at" => "required|date_format:d/m/Y H:i:s",
            "recipe.updated_at" => "required|date_format:d/m/Y H:i:s",
            "recipe.box_type" => "required|string|max:255",
            "recipe.title" => "required|string",
            "recipe.slug" => "required|string|max:255",
            "recipe.short_title" => "nullable|string",
            "recipe.marketing_description" => "required|string",
            "recipe.calories_kcal" => "required|integer",
            "recipe.protein_grams" => "required|integer",
            "recipe.fat_grams" => "required|integer",
            "recipe.carbs_grams" => "required|integer",
            "recipe.bulletpoint1" => "nullable|string",
            "recipe.bulletpoint2" => "nullable|string",
            "recipe.bulletpoint3" => "nullable|string",
            "recipe.recipe_diet_type_id" => "required|string",
            "recipe.season" => "required|string",
            "recipe.base" => "required|string",
            "recipe.protein_source" => "required|string",
            "recipe.preparation_time_minutes" => "required|integer",
            "recipe.shelf_life_days" => "required|integer",
            "recipe.equipment_needed" => "required|string",
            "recipe.origin_country" => "required|string",
            "recipe.recipe_cuisine" => "required|string",
            "recipe.in_your_box" => "nullable|string",
            "recipe.gousto_reference" => "required|integer",
        ];
    }
}
