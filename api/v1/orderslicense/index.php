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

$app->get('/orderlisences', function() { 
    global $db;
    $rows = $db->select_sql("
        
        ");

    echoResponse(200, $rows);
    //ACT FALTA POR PAGO
    //PAGADO FALTA POR LICENCIA
    //PEN FALTAN LICENCIAS POR PERSONALIZAR
    //CON COMPLETADO NO TIENE LICENCIAS
    // HACER EL FILTRO POR LA PANTALLA DEPENDIENDO DEL CASO
});

$app->get('/orderlisence/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("        
        select
            ol.web_hijo ,
            ol.partner_id ,
            ol.order_id ,
            ol.order_license_id ,
            ol.product_id ,
            ol.serial ,
            ol.nombre ,
            ol.siglas ,
            ol.direccion1 ,
            ol.direccion2 ,
            ol.empresa_rif ,
            ol.empresa_contacto ,
            ol.empresa_nombre ,
            ol.empresa_email ,
            ol.usada,
            ol.observacion,
            p.descripcion 
        from 
            admin.inn_orders_license ol
        left join 
            admin.inn_products p 
        on 
            p.product_id = ol.product_id   
            and 
        ol.order_id = ".$id." 
        where 
            p.activo = 1
        order by p.descripcion DESC, order_license_id
        ");

    echoResponse(200, $rows);

});


$app->post('/orderlicense', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array();
    $pg_secuencial = 'admin.inn_orders_license_order_license_id_seq';
    global $db;
    $rows = $db->insert("admin.inn_orders_license", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "License added successfully.";
    echoResponse(200, $rows);
});

$app->put('/orderlicense/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('order_license_id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("admin.inn_orders_license", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "License information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/orderlicense/:id', function($id) { 
    global $db;
    $rows = $db->delete("admin.inn_orders_license", array('order_license_id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "License removed successfully.";
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