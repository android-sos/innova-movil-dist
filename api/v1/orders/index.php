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
function auth (){
    $app = \Slim\Slim::getInstance();
    $headers = $app->request()->headers();
}

$app->get('/orders', 'auth' , function() use ($app){ 

  //print_r($app->request()->headers()) ;
  //return;

    global $db;
    $rows = $db->select_sql("
        select 
            o.web_hijo, 
            o.order_id, 
            o.partner_id, 
            o.tipo_documento, 
            o.tipo_operacion,
            o.tipo_estado,
            o.fecha_documento,
            o.fecha_vencimiento,
            o.descripcion,
            o.total_base,
            o.total_iva,
            o.total_pagar,
            o.observacion,
            sum(od.total_items) as total_items 
        from 
            admin.inn_orders o
        left join 
            admin.inn_orders_detail od on od.order_id = o.order_id 
        where 
            tipo_estado ='ACT' OR
             tipo_estado='PAG' OR 
             tipo_estado='PEN' 
        group by 
            o.order_id
        order by
            o.order_id desc
        ");

    echoResponse(200, $rows);
    //ACT FALTA POR PAGO
    //PAGADO FALTA POR LICENCIA
    //PEN FALTAN LICENCIAS POR PERSONALIZAR
    //CON COMPLETADO NO TIENE LICENCIAS
    // HACER EL FILTRO POR LA PANTALLA DEPENDIENDO DEL CASO
});

$app->get('/orderbycustomer/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("
        select 
            o.web_hijo, 
            o.order_id, 
            o.partner_id, 
            o.tipo_documento, 
            o.tipo_operacion,
            o.tipo_estado,
            o.fecha_documento,
            o.fecha_vencimiento,
            o.descripcion,
            o.total_base,
            o.total_iva,
            o.total_pagar,
            o.observacion,
            sum(od.total_items) as total_items 
        from 
            admin.inn_orders o
        left join 
            admin.inn_orders_detail od on od.order_id = o.order_id 
        where 
             o.partner_id = '".$id."'  AND  
             tipo_estado ='ACT' OR
             tipo_estado='PAG' OR 
             tipo_estado='PEN' 
        group by 
            o.order_id
        order by
            o.order_id desc
        ");

    echoResponse(200, $rows);
    //ACT FALTA POR PAGO
    //PAGADO FALTA POR LICENCIA
    //PEN FALTAN LICENCIAS POR PERSONALIZAR
    //CON COMPLETADO NO TIENE LICENCIAS
    // HACER EL FILTRO POR LA PANTALLA DEPENDIENDO DEL CASO
});


$app->get('/order/:id', function($id) use ($app) { 

    
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;

    $rows = $db->select_sql("
        select 
            o.web_hijo, 
            o.order_id, 
            o.partner_id, 
            o.tipo_documento, 
            o.tipo_operacion,
            o.tipo_estado,
            o.fecha_documento,
            o.fecha_vencimiento,
            o.descripcion,
            o.total_base,
            o.total_iva,
            o.total_pagar,
            o.observacion,
            sum(od.total_items) as total_items 
        from 
            admin.inn_orders o
        left join 
            admin.inn_orders_detail od on od.order_id = o.order_id 
        where 
             o.order_id = ".$id."  AND 
             (o.tipo_estado ='ACT' OR o.tipo_estado ='PAG' OR o.tipo_estado ='ANU') 
        group by 
            o.order_id
        order by
             o.order_id desc
        ");

    echoResponse(200, $rows);

});



$app->post('/order', function() use ($app) { 
    


    $data = json_decode($app->request->getBody());
    $mandatory = array();
    $pg_secuencial = 'admin.inn_orders_order_id_seq';
    global $db;
    $rows = $db->insert("admin.inn_orders", $data, $mandatory, $pg_secuencial);
    if($rows["status"]=="success")
        $rows["message"] = "Order added successfully.";
    echoResponse(200, $rows);
});

$app->put('/order/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('order_id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("admin.inn_orders", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Order information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/order/:id', function($id) { 
    global $db;
    $rows = $db->delete("admin.inn_orders", array('order_id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Order removed successfully.";
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