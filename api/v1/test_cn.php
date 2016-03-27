<?php
require_once 'dbHelper.php';
$db = new dbHelper();

/*CREATE TABLE admin.inn_orders
(
  web_hijo admin.codigo NOT NULL,
  documento_id admin.codigo NOT NULL,
  partner_id admin.codigo NOT NULL,
  tipo_documento character varying NOT NULL,
  tipo_operacion character varying NOT NULL,
  tipo_estado character varying NOT NULL,
  fecha_documento admin.fecha_registro,
  fecha_vencimiento admin.fecha_registro,
  descripcion admin.descripcion,
  total_base admin.num_decimal,
  total_iva admin.num_decimal,
  total_pagar admin.num_decimal,
  observacion admin.observacion
)*/
 /*$rows = $db->select("inn_orders",
        "web_hijo, 
        documento_id, 
        partner_id, 
        tipo_documento, //ORD/ORD
        tipo_operacion, //DOG//PAG
        tipo_estado, //ACT//PAG
        fecha_documento,
        fecha_vencimiento,
        descripcion,
        total_base,
        total_iva,
        total_pagar,
        observacion",
        array());*/

  $rows = $db->sql_test('Select * from admin.gen_noticias');
    
  print_r ($rows);
?>