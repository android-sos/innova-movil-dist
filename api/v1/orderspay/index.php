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


$app->get('/ordespays', function() { 
    global $db;
    $rows = $db->select_sql("");

    echoResponse(200, $rows);
    //ACT FALTA POR PAGO
    //PAGADO FALTA POR LICENCIA
    //PEN FALTAN LICENCIAS POR PERSONALIZAR
    //CON COMPLETADO NO TIENE LICENCIAS
    // HACER EL FILTRO POR LA PANTALLA DEPENDIENDO DEL CASO
});

$app->get('/orderpay/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("");

    echoResponse(200, $rows);

});



$app->post('/orderpay', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array();
    $pg_secuencial = 'admin.inn_orders_pay_order_pay_id_seq';
    global $db;
    $rows = $db->insert("admin.inn_orders_pay", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "Pay added successfully.";
    echoResponse(200, $rows);
});

$app->put('/orderpay/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('order_id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("admin.inn_orders_pay", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Pay information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/orderpay/:id', function($id) { 
    global $db;
    $rows = $db->delete("admin.inn_orders_pay", array('order_id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Pay removed successfully.";
    echoResponse(200, $rows);
});

function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response);
}

$app->run();
?>