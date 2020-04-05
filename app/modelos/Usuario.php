<?php
Class Usuario extends DB{ 

	public function mostrar($where = 1){
		return $this->getDatos("usuario","*",$where);//["x"=>"id","nom"=>"nombreA"]
	}

	public function setToken($id,$token){
		$us = ["token"=>"'$token'"];
		return $this->update("usuario",$us,"idUs = $id");

	}

	public function getToken($id){
		$token = $this->getDatos("usuario","token","idUs = '$id'");
		if(count($token) > 0)
			return $token[0]->token;
		return null;
	}
}