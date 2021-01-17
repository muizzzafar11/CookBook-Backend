<?php

require_once './vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// make ingredients a list

class Rtdb {
    protected $database;
    protected $dbname = 'recipe';
    protected $ingredientKey = 'Ingredient';
    protected $instructionsKey = 'How-to-make';
    static $searchResult = [];
    
    public function __construct(){
        $factory = (new Factory)->withServiceAccount(__DIR__.'/hackathon-rtdb-firebase-adminsdk-sbm0f-0382ae8bc4.json');
        $this->database = $factory->createDatabase();
    }

    public function signInUser () {
        
    }

    // Gets all the recipeies from the db
    public function getAllRecipe () {
        return $this->database->getReference()->getChild($this->dbname)->getValue();
    }

    // Gets the name of all the recipes in the db
    public function getAllRecipeName () {
        $allRecipe = $this->getAllRecipe();
        return array_keys($allRecipe);
    }

    // Gets 10 recipes from the startIndex from the db
    public function getRecipeRange(int $startIndex = NULL) {
        $recipeArr = $this->getAllRecipe();
        return array_slice($recipeArr, $startIndex, 10);

    }

    // Gets a specific recipe and all info of that recipe from the db
    public function getRecipe(string $recipeId = NULL){
        if (empty($recipeId) || !isset($recipeId)) { return FALSE; }

        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($recipeId)){
            return $this->database->getReference($this->dbname)->getChild($recipeId)->getValue();
        } else {
            return FALSE;
        }
        
    }

    // Add a new recipe to the db
    public function insert(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }

        foreach ($data as $key => $value){
            $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
        }
        return TRUE; 
    }

    public function search($ingredientsArr) {
        $allRecipe = $this->getAllRecipe();
        $tempArr = [];
        
        foreach($ingredientsArr as $ingredient) {
            foreach ($allRecipe as $recipe) {
                // print_r($recipe[$this->instructions]);
                // If any of the ingredients match, at it to the list
                if(str_contains($recipe[$this->ingredientKey], $ingredient)) {
                    array_push($tempArr, $recipe);
                    array_push($this->$searchResult, $recipe);
                    break;
                }
            }
        }

        return $tempArr;

    }

    // Don't think we need this
    public function delete(int $recipeId) {
        
    }



}

$recipe = new Rtdb();

    // $recipe->search();

    // print_r($recipe->getAllRecipeName());

    // print_r($recipe->getRecipes('Cake'));

    // Writing to the db
    // var_dump($recipe->insert([
    // 'Cake' => ['Ingredients' => ['milk' => True, 
    //                              'meat' => True],
    //             'How-to-make' => 'Cake'],

    // 'Rice' => ['Ingredients' => '1. Milk 2. Sugar',
    //              'How-to-make' => 'Rice'],

    // 'Donut' => ['Ingredients' => '1. Milk 2. Sugar',
    //               'How-to-make' => 'Donut'],

    // 'Burger' => ['Ingredients' => '1. Milk 2. Sugar',
    //              'How-to-make' => 'Burger'],
    // ]));

