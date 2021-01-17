<?php

require_once './vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// make ingredients a list

class Rtdb {
    protected $database;
    protected $dbname = 'recipe';
    protected $ingredientKey = 'Ingredients';
    protected $instructionsKey = 'How-to-make';
    static $searchResult = [];
    
    // Get the list from the front-end
    
    public function __construct(){
        $factory = (new Factory)->withServiceAccount(__DIR__.'/hackathon-rtdb-firebase-adminsdk-sbm0f-0382ae8bc4.json');
        $this->database = $factory->createDatabase();
    }

    public function signInUser () {
        
    }

    // Gets all the recipes from the db
    public function getAllRecipe () {
        return $this->database->getReference()->getChild($this->dbname)->getValue();
    }

    // Gets the name of all the recipes in the db
    public function getAllRecipeName () {
        $allRecipe = $this->getAllRecipe();
        return array_keys($allRecipe);
    }

    // Gets 10 recipes from the startIndex from the db
    public function getRecipeRange(int $startIndex = NULL, $ingredients = []) {
        
        // Run the search algorithm the first time to populate the searchResult Array
        // if((count(self::$searchResult) == 0) && (count($ingredients) > 0)) {
        //     $this->search();
        // }
        $this->search($ingredients);
        if(count(self::$searchResult) >= 10){
            return array_slice(self::$searchResult, $startIndex, 9);
        } else {
            return self::$searchResult;
        }

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

    public function search($ingredientsArr = []) {
        $allRecipe = $this->getAllRecipe();
        $tempArr = [];
        
        foreach($ingredientsArr as $ingredient) {
            foreach ($allRecipe as $recipe) {

                // If any of the ingredients match, add it to the list
                if(str_contains($recipe[$this->ingredientKey], $ingredient)) {
                    array_push($tempArr, $recipe);
                }
                // print_r($recipe[$this->ingredientKey]);
            }
        }
        self::$searchResult = $tempArr;
        // print_r(self::$searchResult);
        return TRUE;
    }

    // Don't think we need this
    public function delete(int $recipeId) {
        
    }



}

$recipe = new Rtdb();

    // print_r($recipe->getRecipeRange(0,['Spice']));

    // $recipe->search(['Spice']);

    // print_r($recipe->getAllRecipeName());

    // print_r($recipe->getRecipes('Cake'));

    // Writing to the db
    // var_dump($recipe->insert([

    //             'Rice' => ['Ingredients' => '1. Milk 2. Sugar',
    //              'How-to-make' => 'Rice'],

    //             'Donut' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //               'How-to-make' => 'Donut'],

    //             'Burger' => ['Ingredients' => '1. Milk 2. Sugar',
    //              'How-to-make' => 'Burger'],
    
    //              'Test1' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test1'],


    //              'Test2' => ['Ingredients' => '1. Milk 2. Sugar',
    //              'How-to-make' => 'Test2'],


    //              'Test3' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test3'],

    //              'Test4' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test4'],

    //              'Test5' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test5'],

    //              'Test6' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test6'],

    //              'Test7' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test7'],

    //              'Test8' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test8'],

    //              'Test9' => ['Ingredients' => '1. Milk 2. Sugar Spice',
    //              'How-to-make' => 'Test9'],

    // ]));

