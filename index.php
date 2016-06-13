<?php
include_once "core.php";
$linksIn = array(
	"http://www.lojadojoao.com.br/",
	"http://www.lojadojoao.com.br/produto-de-teste-1-16599221",
	"http://www.lojadojoao.com.br/categoria-teste",
	"http://www.lojadojoao.com.br/search/helloword",
	"http://www.lojadojoao.com.br/produto-de-teste-1-16599221?utm_teste=testando&tt=aaa"
);
$classLink = new Link;
$link = $classLink->trataLink($linksIn[1]);
$produtos = $classLink->buscaProduto($link['dominio'],$link['produto']);

if(!$produtos->count()) {
	echo '{"success": false, "message": "Nenhum Produto encontrado"}';
} else {
	$json = '{"success": true, "produtos_id": [';
	foreach($produtos as $produto) {
		$json .= $produto['item']['id']. ",";
		//var_dump($produto['item']['id']);
	}
	$json = substr($json, 0, -1);
	$json .= "]}";
	echo $json;
}