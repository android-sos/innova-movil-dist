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

$app->get('/ordersdetails', function() { 
    global $db;

    $rows = $db->select("admin.inn_orders_detail",
        "web_hijo, 
        order_id, 
        order_detail_id,
        art_cod,
        partner_id, 
        tipo_documento,
        descripcion,
        total_items,
        total_base,
        total_iva,
        total_pagar,
        observacion",
        array());
    echoResponse(200, $rows);
});

$app->get('/orderdetail/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("
    select  
        od.order_id,
        p.product_id,
        p.descripcion,
        p.costo,
        p.iva,
        p.precio,
        p.utilidad,
        p.activo_lista,
        p.activo_vender,
        p.activo,
        od.order_detail_id,
        od.total_items,
        od.total_base,
        od.total_iva,
        od.total_pagar,
        od.observacion
    from 
        admin.inn_products p
    left join 
         admin.inn_orders_detail od 
    on 
        od.product_id = p.product_id 
    and 
        od.order_id = ".$id." 
    where 
        p.activo = 1
    order by p.descripcion
    ");

    echoResponse(200, $rows);

});


$app->get('/orderdetailitems/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("
    select  
        p.product_id, 
        p.descripcion,
        od.order_id,
        od.web_hijo,
        od.partner_id,
        od.total_items
    from 
        admin.inn_orders_detail od      
    left join 
        admin.inn_products p 
    on 
        od.product_id = p.product_id 
    and 
        od.order_id = ".$id."
    where 
        p.activo = 1
    order by p.descripcion
    ");

    echoResponse(200, $rows);

});

$app->post('/orderdetail', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array('descripcion');
    $pg_secuencial = 'admin.inn_orders_detail_order_detail_id_seq';

    global $db;

    $rows = $db->insert("admin.inn_orders_detail", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "Detail added successfully.";
    echoResponse(200, $rows);
});

$app->put('/orderdetail/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('order_detail_id'=>$id);
    $mandatory = array();

    global $db;
    $rows = $db->update("admin.inn_orders_detail", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Detail information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/orderdetail/:id', function($id) { 
    global $db;
    $rows = $db->delete("admin.inn_orders_detail", array('order_detail_id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Detail removed successfully.";
    echoResponse(200, $rows);
});

function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    
    echo json_encode($response, JSON_NUMERIC_CHECK);
}

$app->run();
?>
