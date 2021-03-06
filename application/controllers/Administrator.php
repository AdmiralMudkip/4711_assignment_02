<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends Application{
    
    function __construct(){
        parent::__construct();
        $has_access = FALSE;
        $role = $this->session->userdata('userrole');
            $has_access = TRUE;

    }
    
	// like all the other controllers, pulls data from the db, throws it into the view.
	// do each one individually. 
    public function index(){
        $recipesData = $this->recipes->getRecipes();
        $recipes = array();
        
        foreach($recipesData as $recipe){
            $ingredients = $this->recipes->getIngredients($recipe->id);
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

    public function add_stock(){
        $this->data['id'] = null;
        $this->data['name'] = null;
        $this->data['price'] = null;
        $this->data['quantity'] = null;
        $this->data['pagetitle'] = 'Add Stock';
        $this->data['pagebody'] = 'edit_stock';
        $this->render();
    }
    public function edit_stock($id = null){
        $item = $this->stock->get($id);
        $this->data['id'] = $id;
        $this->data['name'] = $item->name;
        $this->data['price'] = $item->price;
        $this->data['quantity'] = $item->quantity;
        $this->data['pagetitle'] = 'Edit Stock';
        $this->data['pagebody'] = 'edit_stock';
        $this->render();
    }
    public function save_stock(){
        $item = new stock();
        $item->id = $this->input->post('id');
        $item->name = $this->input->post('name');
        $item->price = $this->input->post('price');
        $item->quantity = $this->input->post('quantity');
        if ($item->id == null) {
            $this->stock->create($item);
        } else {
            $this->stock->update($item);
        }
        redirect('/administrator/');
    }
    public function delete_stock($id = null){
        $this->stock->delete($id);
        redirect('/administrator/', 'refresh');
    }

    public function add_supply(){
        $this->data['id'] = null;
        $this->data['name'] = null;
        $this->data['onHand'] = null;
        $this->data['containersPerShipment'] = null;
        $this->data['containers'] = null;
        $this->data['itemsPerContainer'] = null;
        $this->data['cost'] = null;
        $this->data['pagetitle'] = 'Add Supply';
        $this->data['pagebody'] = 'edit_supply';
        $this->render();
    }
    public function edit_supply($id = null){
        $item = $this->supplies->get($id);
        $this->data['id'] = $id;
        $this->data['name'] = $item->name;
        $this->data['onHand'] = $item->onHand;
        $this->data['containersPerShipment'] = $item->containersPerShipment;
        $this->data['containers'] = $item->containers;
        $this->data['itemsPerContainer'] = $item->itemsPerContainer;
        $this->data['cost'] = $item->cost;
        $this->data['pagetitle'] = 'Edit Supply';
        $this->data['pagebody'] = 'edit_supply';
        $this->render();
    }
    public function save_supply(){
        $item = new supplies();
        $item->id = $this->input->post('id');
        $item->name = $this->input->post('name');
        $item->onHand = $this->input->post('onHand');
        $item->containersPerShipment = $this->input->post('containersPerShipment');
        $item->containers = $this->input->post('containers');
        $item->itemsPerContainer = $this->input->post('itemsPerContainer');
        $item->cost = $this->input->post('cost');
        if ($item->id == null) {
            $this->supplies->create($item);
        } else {
            $this->supplies->update($item);
        }
        redirect('/administrator/');
    }
    public function delete_supply($id = null){
        $this->supplies->delete($id);
        redirect('/administrator/', 'refresh');
    }
}