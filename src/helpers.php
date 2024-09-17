<?php
if (!function_exists('settings')) {
    function settings()
    {
       $root = "http://localhost/POLY/Batch01/Class20-17-92024Project/tigercommerce/"; 
        return [
            'root'  => $root,
            'companyname'=> 'Gold Digger Enterprise',
            'logo'=>$root."admin/assets/img/logo.svg",
            'homepage'=> $root,
            'adminpage'=>$root.'admin/',
            'vendorpage'=>$root.'vendor/',
            'hostname'=> 'localhost',
            'user'=> 'root',
            'password'=> '',
            'database'=> 'lioncommerce'
        ];
    }
}
if (!function_exists('testfunc')) {
    function testfunc()
    {
        return "<h3>testing common functions</h3>";
    }
}
if (!function_exists('config')) {
    function config($param)
    {        
      $parts = explode(".",$param);
      $inc = include(__DIR__."/../config/".$parts[0].".php");
      return $inc[$parts[1]];
    }
}
