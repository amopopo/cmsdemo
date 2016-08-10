<?php 
 /*** include the init.php file ***/
 include 'lib/classes/framework.php';

 /*** load template ***/
 $template = new template();
 /*** load router ***/
 $router = new router($template);

 /*** set the controller path ***/
 $router->setPath (CONTROLLER_PATH);

 /*** load the controller ***/
 $router->loader();

 exit; ?>
