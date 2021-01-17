<?php

require_once './vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Recipe {Ingredients}

class Rtdb {
    protected $database;
    protected $dbname = 'recipe';
    protected $ingredient = 'Ingredient';
    protected $instructions = 'How-to-make';
    
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

    public function getAllRecipeName () {
        $allRecipe = $this->getAllRecipe();
        return array_keys($allRecipe);
    }



    // Gets a specific recipe and all info of that recipe from the db
    // Id of the recipe
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

    public function search(array $ingredients) {
        $allRecipe = getAllRecipeName();

    }

    // Don't think we need this
    public function delete(int $recipeId) {
        
    }



}

$recipe = new Rtdb();

    // print_r($recipe->getAllRecipeName());

    // print_r($recipe->getRecipes('Cake'));

    var_dump($recipe->insert([
    'Cake' => 'This is the recipe for cake',
    'Rice' => 'This is the recipe for rice',
    'Donuts' => 'This is the recipe for donuts',
    'Burger' => ['Ingredients' => '1. Milk 2. Sugar',
                 'How-to-make' => '1. Add Milk 2. Add Sugar'],
    ]));

