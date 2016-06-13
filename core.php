<?php

class Link {

	protected $tag = array("www.", ".com", ".br");
	protected $mongodb;
	
	function __construct() {
       $m = new MongoClient();
	   $this->mongodb = $m->produtos;
   }
	
	function trataDominio($dominio) {
		
		foreach($this->tag as $tag) {
			$dominio = str_replace($tag, "", $dominio);
		}
		
		return $dominio;
		
	}
	
	function trataLink($link = false) {
		
		if(!$link)
			return false;
			
		$url = parse_url($link);
		$retorno["dominio"] = $this->trataDominio($url['host']);
		
		if(isset($url['path']) && $url['path'] != "/") {
			$retorno["path"] = explode("/",$url['path']);
			$retorno["produto"] = end($retorno["path"]);
		}
		
		return $retorno;
	}
	
	function buscaProduto($dominio, $produto) {
		
		//$m = new MongoClient();
		// select a database
		$db = $this->mongodb;
		//var_dump($db);
		$collection = $db->$dominio;
		//$collection->ensureIndex(array('item.link' => 'text'));
		$string = "produto-de-teste-1-16599221";
		$produtos = $collection->find(
			array('$text' => array('$search' => $produto)),
			array('score' => array('$meta' => 'textScore'))
		);
		
		$produtos = $produtos->sort(
			array('score' => array('$meta' => 'textScore'))
		);
		
		return $produtos;
		
	}
	
	function insertProduto($dominio, $produto) {
		$db = $this->mongodb;
		//var_dump($db);
		$collection = $db->$dominio;
		
		$collection->insert(array('item' => array('id' => $produto['id'], 'title' => $produto['title'], 'price' => $produto['price'], 'link' => $produto['link'])));
	}

}