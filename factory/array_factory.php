<?php
  class ArrayFactory {

    public static $instance = null;
    private $container;


    private function __construct() 
    {
    	$this->container = Array();
    }



    public static function create() 
    {

      if(!isset(self::$instance)) 
      {
        self::$instance = new ArrayFactory();
      }

      return self::$instance;
    }



    public function add($key, $value)
    {
    	$this->container[$key] = $value;
    }



    public function get()
    {
    	return $this->container;
    }


    public function reset()
    {
      $this->container = Array();  
    }

  }
?>