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

  web_hijo,
  product_id,
  descripcion,
  costo,
  iva,
  precio,
  utilidad

*/

$app->get('/products', function() { 
    
    global $db;
    $rows = $db->select_sql("
        select 
            p.web_hijo,      
            p.product_id,
            p.descripcion,
            p.costo,
            p.iva,
            p.precio,
            p.utilidad,
            p.activo_lista,
            p.activo_vender,
            p.activo
        from 
            admin.inn_products p
        order by
            p.descripcion
        ");

        echoResponse(200, $rows);
});

  


$app->get('/product/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    global $db;

    $rows = $db->select("admin.inn_products",
        "web_hijo,      
        product_id,
        descripcion,
        costo,
        iva,
        precio,
        utilidad,
        activo_lista,
        activo_vender,
        activo",
        'product_id = '. $id ,'', '');

    echoResponse(200, $rows);
});

$app->post('/product', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array('descripcion');
    $pg_secuencial = '';
    global $db;
    $rows = $db->insert("admin.inn_products", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "Product added successfully.";
    echoResponse(200, $rows);
});

$app->put('/product/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('product_id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("admin.inn_products", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Product information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/products/:id', function($id) { 
    global $db;
    $rows = $db->delete("admin.inn_products", array('id'=>$id));
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