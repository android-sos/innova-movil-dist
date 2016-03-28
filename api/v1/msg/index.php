<?php
require '../.././libs/Slim/Slim.php';
require_once '../dbHelper.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();
$db = new dbHelper();

error_reporting(E_ALL);

function auth (){
    $app = \Slim\Slim::getInstance();
    $headers = $app->request()->headers();
}

$app->get('/send-comment/:cadena', function($cadena) use ($app) { 
    $data = json_decode($app->request->getBody());
    
    $mandatory = array();
    global $db;
    $desencriptar = base64_decode($cadena);
    $data = explode("|", $desencriptar);
    
    $pase = $data[0];
    $nombre = $data[1];
    $mensaje = $data[2] .', '. $data[3] ;

    //echo $desencriptar;

    if ($pase === 'n3H{J%xPnCF'){
        $rows = send_mail('samuel.ospina36@gmail.com', $nombre, $mensaje , '0');
        $response["status"] = "Bad Request";
        echoResponse($rows["code"], $rows); 
    }else{
       $response["status"] = "Bad Request";
       echoResponse(400, $response);                 
    } 
});




function send_mail($correo, $nombre, $order_id, $order_total) {
        $para = $correo;
        $asunto = 'Nueva Orden en Linea';
        $copia = 'canales@innovaprosystem.com'; 
        $copia1 = 'ventas@innovaprosystem.com'; 

        // canales@innovaprosystem.com
        // ventas@innovaprosystem.com
  

        $cuerpo='<table style="table-layout:fixed" bgcolor="#C1C1C1" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr>
                <td valign="top">
                  <table align="center" bgcolor="#C1C1C1" border="0" cellpadding="0" cellspacing="0" width="600">
                    <tbody><tr>
                      <td width="11">&nbsp;
                        </td>
                      <td valign="top" width="579">
                        <table border="0" cellpadding="0" cellspacing="0" width="579">
                          <tbody><tr>
                            <td style="font-size:2px" height="5">
                            </td>
                          </tr>
                         
                          <tr>
                            <td style="font-size:2px" height="12">
                            </td>
                          </tr>
                          <tr>
                            <td height="14" valign="top">
                              <table style="line-height:0" height="14" border="0" cellpadding="0" cellspacing="0" width="579">
                                <tbody><tr height="14">
                                  <td height="14" valign="top">
                                    
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                          <tr>
                            <td bgcolor="#C1C1C1" valign="top">
                              <table border="0" cellpadding="0" cellspacing="0" width="579">
                                <tbody><tr>
                                  <td width="33">&nbsp;
                                    </td>
                                  <td valign="top" width="510">
                                    <table border="0" cellpadding="0" cellspacing="0" width="510"  bgcolor="#C1C1C1">
                                      <tbody><tr>
                                        <td style="font-size:2px" height="10">
                                        </td>
                                      </tr>
                                         
                                      <tr>
                                        <td align="center" valign="top"> <a href="http://innovaprosystem.com/" target="_blank"><img class="CToWUd" alt="Innova Prosystem" src="http://138.117.47.13/innova-movil/www/img/innova_logo.png" width="120"></a>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-size:2px" height="30"></td>
                                      </tr>
                    <tr>
                                        <td valign="top" style="text-align:center;">
                                          <p><font style="font-family:Helvetica Neue,Arial,Helvetica,sans-serif;font-size:22px;line-height:22px;color:#000000;font-weight:bold;"> INNOVA PROSYSTEM</font></p>
                                          <p><font style="font-family:Helvetica Neue,Arial,Helvetica,sans-serif;font-size:16px;line-height:18px;color:#000000;font-weight:bold;">SISTEMA DE ORDENES EN LINEA<br>
                                          <br>
                                          </font></p>
                                      </td></tr><tr>
                    </tr><tr>
                                        <td valign="top">
                                          <font style="font-family:HelveticaNeue,Arial,Helvetica,sans-serif;font-size:13px;line-height:18px;color:#000000">
                                            Muchas Gracias <b> '.$nombre.' </b> estamos contentos con su Orden No. <b> '.$order_id.' </b> por un un total de <b> '.$order_total.' </b> lo invitamos a que de usted realice el pago correspondiente lo antes posible para activar sus licencias.</font>
                                        </td>
                                      </tr>
                               
                                     
                                      <tr>
                                        <td style="font-size:2px" height="20">
                                        </td>
                                      </tr>
                                      
                                      <tr>
                                        <td style="font-size:2px" height="20">
                                        </td>
                                      </tr>
                                      
                                      <tr>
                                       
                                      </tr>

                                      <tr>
                                        <td style="font-size:2px" height="20">
                                        </td>
                                      </tr>
                                      
                                         
                                      <tr>
                                        <td style="font-size:2px" height="20">
                                        </td>
                                      </tr>
                                      
                                      <tr>
                                        <td valign="top">
                                          <font style="font-family:Helvetica Neue,Arial,Helvetica,sans-serif;font-size:13px;line-height:18px;color:#000000">
                                           Si usted presenta alg&uacute;n incoveniente con la aplicaci&oacute;n por favor puede escribirnos al siguente correo y con gusto le atenderemos a la brevedad posible&nbsp;<a href="mailto:ventas@innovaprosystem.com" target="_blank">ventas@innovaprosystem.com</a>. </font>
                                        </td>
                                      </tr>
                                      
                                      <tr>
                                        <td style="font-size:2px" height="20">
                                        </td>
                                      </tr>                              
                                      
                                      <tr>
                                        <td style="font-size:2px" height="24">
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                  <td width="36">&nbsp;
                                    </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                            
                            <tr>
                                <td align="center" valign="top">
                                    <table style="height:57px;table-layout:fixed" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody><tr>
                                            <td style="height:57px;width:27px" valign="top">&nbsp;</td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:104px" align="left" valign="top">&nbsp;</td>
                                            <td style="font-size:1px;height:57px;line-height:1px;width:277px" height="57" valign="top" width="277">
                                                
                                          </td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:29px" align="left" valign="top">
                                                <a href="http://easydancelatino.com/#/noticias" style="color:#ffffff;font-weight:normal;text-decoration:underline" target="_blank">
                                                   
                                                </a>
                                            </td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:40px" align="left" valign="top">
                                               
                                            </td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:26px" align="left" valign="top">
                                                <a href="https://twitter.com/EasyDanceLatino" target="_blank">
                                                    
                                                </a>
                                            </td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:40px" align="left" valign="top">
                                                
                                            </td>
                                            <td style="font-size:1px;line-height:1px;font-family:Helvetica Neue,Helvetica,arial,sans-serif;color:#ffffff;text-align:left;height:57px;width:10px" align="left" valign="top">
                                                <a href="https://www.facebook.com/Easydancelatino/" style="color:#ffffff;font-weight:normal;text-decoration:underline" target="_blank">
                                                    
                                                </a>
                                            </td>
                                            <td style="height:57px;width:27px" valign="top">
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                                              
                          <tr>
                            <td valign="top">
                              <table border="0" cellpadding="0" cellspacing="0" width="579">
                                <tbody><tr>
                                  <td style="font-size:2px" height="24">
                                  </td>
                                </tr>
                                <tr>
                                  <td style="font-size:2px" height="22">
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td width="10">&nbsp;
                        </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>';
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        // Cabeceras adicionales
        $cabeceras .= 'To: '.$nombre.'<'.$para.'>' . "\r\n";
        $cabeceras .= 'From: Innova Ordenes en Linea <'.$copia.'>' . "\r\n";
        $cabeceras .= 'Cc: '.$copia1.' ' . "\r\n";
        //$cabeceras .= 'Bcc: '.$copia2.' ' . "\r\n";

        if(mail($para, $asunto, $cuerpo, $cabeceras)){ 
            $mensajeStatus = true;
        }else{
            $mensajeStatus = false;
        }

         if(!$mensajeStatus){
                $response["code"] = 422;
                $response["status"] = "warning";
                $response["message"] = "Unprocessable Entity.";
            }else{
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Entity Found";
            }
      return $response;
};

$app->run();
?>