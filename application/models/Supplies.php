<?php

/**
 * Supplies, and accessors.  Also, ways to update the database
 */


class Supplies extends CI_Model {
    
    	// Constructor
	public function __construct()
	{
		parent::__construct();
        $this->load->library(['curl', 'format', 'rest']);
	}
    
	// increments the containers by the amount of containers in a pallet.
	// should also do something with cost, but dont worry about it for now
    public function orderSupplies($itemID, $amount){
        $this->rest->initialize(array('server'=>REST_SERVER));
        $this->rest->option(CURLOPT_PORT, REST_PORT);
        return $this->rest->post('/recieve/id/' . $itemID . '/amount/' . $amount);
	}
	    
    // decrement the amount of containers of a supply, and increase the onhand
    public function openContainer($supplyID){
        $sql = sprintf("UPDATE SUPPLIES set onHand = onHand + itemsPerContainer, containers = containers - 1 where id = %d", $supplyID);
        $this->db->query($sql);
    }

	// retrieve a single supply
	public function get($which)
	{
        $this->rest->initialize(array('server'=>REST_SERVER));
        $this->rest->option(CURLOPT_PORT, REST_PORT);
        return $this->rest->get('/supplies/id/' . $which);
        // $sql = sprintf("SELECT * from SUPPLIES where ID = %d", $which);
        // $query = $this->db->query($sql);
        // $result = $query->result();
        // $reset = reset($result);
        // return $reset;
	}

	// retrieve all of the supplies
	public function getSupplies()
	{
		$this->rest->initialize(array('server'=>REST_SERVER));
        $this->rest->option(CURLOPT_PORT, REST_PORT);
        return $this->rest->get('/supplies');
	}
    
    public function create($supply){
        $this->rest->initialize(array('server'=>REST_SERVER));
        $this->rest->option(CURLOPT_PORT, REST_PORT);
        $this->rest->post('/supplies/id/' . $supply->id, json_encode($supply));
        echo '<script>';
        echo 'alert.log('. $supply .')';
        echo '</script>';
    }
    
    public function update($supply){
        $this->rest->initialize(array('server'=>REST_SERVER));
        $this->rest->option(CURLOPT_PORT, REST_PORT);
        $this->rest->put('/supplies/id/' . $supply->id, json_encode($supply));
        echo '<script>';
        echo 'alert.log('. $supply .')';
        echo '</script>';
    }
    
    public function delete($id){
        $sql = sprintf("DELETE from SUPPLIES where id = %d", $id);
        $this->db->query($sql);
    }
}