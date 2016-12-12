<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends Application{
    
    function __construct(){
        parent::__construct();
        $has_access = FALSE;
        $role = $this->session->userdata('userrole');
        if($role == 'administrator') {
            $has_access = TRUE;
        }
        if($has_access == FALSE){
            redirect('index.php');
        }
    }
    
	// like all the other controllers, pulls data from the db, throws it into the view.
	// do each one individually. 
    public function index(){
        $recipesData = $this->recipes->getRecipes();
        $recipes = array();
        
        foreach($recipesData as $recipe){
            $ingredients = $this->recipes->getRecipeDescriptions($recipe->id);
            $strIngredients = "";
            foreach($ingredients as $ingredient){
                $strIngredients .= ' ' . $ingredient->name;
            }
            $recipes[] = array('name' => $recipe->name, 'description' => $strIngredients, 'id' => $recipe->id);
        }
        
        $this->data['recipesRow'] = $recipes;
        
        $stockData = $this->stock->getStock();
        $stock = array();
        
        foreach($stockData as $stok){ // ran out of names
            $stock[] = array('name' => $stok->name, 'price' => $stok->price, 'quantity' => $stok->quantity, 'id' => $stok->id);
        }
        
        $this->data['stockRow'] = $stock;
        
        $suppliesData = $this->supplies->getSupplies();
        $supplies = array();
        
        foreach($suppliesData as $supply){
            $supplies[] = array('name' => $supply->name, 'on hand' => $supply->onHand, 'containerspership' => $supply->containersPerShipment, 'containers' => $supply->containers, 'itemspercontainer' => $supply->itemsPerContainer, 'cost' => $supply->cost, 'id' => $supply->id);
        }
        
        $this->data['suppliesRow'] = $supplies;
        
        $this->data['pagetitle'] = 'Administrator';
        $this->data['pagebody'] = 'administrator';
		$this->render();
    }

    public function add_recipe(){}
    public function delete_recipe($id = null){}

    public function add_stock(){}
    public function add_stock_done(){}
    public function edit_stock($id = null){
        $item = $this->stock->get($id);
        $this->data['name'] = $item->name;
        $this->data['price'] = $item->price;
        $this->data['quantity'] = $item->quantity;
        $this->data['pagebody'] = 'admin_stock';
        $this->render();
    }
    public function edit_stock_done(){
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $price = $this->input->post('price');
        $qauntity = $this->input->post('qauntity');
        $item = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $qauntity);
        $this->stock->update();
        redirect('/administrator/');
    }
    // done
    public function delete_stock($id = null){
        $this->stock->delete($id);
        redirect('/administrator/', 'refresh');
    }


    public function add_supply(){}
    public function edit_supply($id = null){}
    public function delete_supply($id = null){}
}