<?php

if(validate_email('bwalia@tenthmatrix.co.uk')){
echo 'OK';
} else {
echo 'Not OK';
}

function validate_email($email){

   $exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";

   if(eregi($exp,$email)){

      if(checkdnsrr(array_pop(explode("@",$email)),"MX")){
        return true;
      }else{
        return false;
      }

   }else{

      return false;

   }    
}

?>