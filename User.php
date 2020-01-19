<?php

class User {
   private $id;
   private $email;
   private $phone;
   private $token;
   private $ip;

   private $country_name;
   private $country_code;
   private $country_image;

   function __construct($id,$email,$phone,$token,$ip){
       $this->id=$id;
       $this->email = $email;
       $this->phone = $phone;
       $this->token = $token;
       $this->ip = $ip;
   }

   function set_email($email){
        $this->email = $email;
   }

   function get_email(){
        return $this->email;
    }

   function set_phone($phone){
        $this->phone = $phone;
    }

    function get_phone(){
        return $this->phone;
    }

    function set_token($token){
        $this->token = $token;
    }

    function set_ip($ip){
        $this->ip = $ip;
    }


    function get_ip(){
        return $this->ip;
    }

    function get_id(){
        return $this->id;
    }


    
    function set_country_name($country_name){
        $this->country_name = $country_name;
    }


    function get_country_name(){
        return $this->country_name;
    }

    function set_country_code($country_code){
        $this->country_code = $country_code;
    }


    function get_country_code(){
        return $this->country_code;
    }

    function set_country_image($country_image){
        $this->country_image = $country_image;
    }


    function get_country_image(){
        return $this->country_image;
    }



}




?>