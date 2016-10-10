<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Production extends Application{
    
    function __construct(){
        parent::__construct();
    }
    
	// like all the other controllers, pulls data from the db, throws it into the view.
    public function index(){
        
        $source = $this->recipes->getRecipes();
        
        $recipes = array();
        
        foreach ($source as $recipe)
        {
            $recipes[] = array ('id' => $recipe['id'], 'name' => $recipe['name'], 'description' => $recipe['description'], 'ingredients' => $recipe['ingredients']);
        }
        $this->data['recipes'] = $recipes;
        
        $this->data['pagetitle'] = 'Production';
        $this->data['pagebody'] = 'production';
		$this->render();
    }
}