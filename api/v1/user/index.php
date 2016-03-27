<?php
require '../.././libs/Slim/Slim.php';
require_once '../dbHelper.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();
$db = new dbHelper();

/**
 * Database Helper Function templates
 */
/*
select(table name, where clause as associative array)
insert(table name, data as associative array, mandatory column names as array)
update(table name, column names as associative array, where clause as associative array, required columns as array)
delete(table name, where clause as array)
 
*/


// Products
// update admin.sist_usu set pwd='827ccb0eea8a706c4c34a16891f84e7b'

$app->get('/users', function() { 
    global $db;
     $rows = $db->select_sql("
      select 
          u.web_padre,
          u.web_hijo,
          u.cedula,
          u.codigo,
          u.descri,
          u.user_email,
          u.reg_estatus,
          u.tipo_usuario
      from 
          admin.sist_usu u    
          ");
    echoResponse(200, $rows);
});

$app->get('/user/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    global $db;
    $rows = $db->select_sql("
      select 
          u.web_padre,
          u.web_hijo,
          u.cedula,
          u.codigo,
          u.descri,
          u.user_email,
          u.reg_estatus,
          u.tipo_usuario
      from 
          admin.sist_usu u
      where
          u.codigo = '".$id."'     
    ");

    echoResponse(200, $rows);

});

$app->post('/dologin', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array('user','pwd');
    global $db;
    
    
    $user = $data->user;
    $pwd = $data->pwd;

    $rows = $db->select_sql("
      select 
          u.web_padre,
          u.web_hijo,
          u.cedula,
          u.codigo,
          u.descri,
          u.user_email,
          u.reg_estatus,
          u.tipo_usuario
      from 
          admin.sist_usu u
      where
          codigo = '".$user."'  
      AND
          pwd ='".md5($pwd)."'   
      AND 
         reg_estatus = 1;    
    ");

    if($rows["status"]=="success")
        $rows["message"] = "User Logged successfully.";
    echoResponse(200, $rows);
});

$app->post('/user', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array();
    $pg_secuencial = '';
    global $db;
    $rows = $db->insert("admin.sist_usu", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "User added successfully.";
    echoResponse(200, $rows);
});

$app->put('/oreder/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("products", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Product information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/products/:id', function($id) { 
    global $db;
    $rows = $db->delete("products", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Product removed successfully.";
    echoResponse(200, $rows);
});

function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response,JSON_NUMERIC_CHECK);
}

$app->run();
?>