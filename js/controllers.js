angular.module("app.controllers",[]).controller("loginCtrl",["$scope","userApi","$localstorage","$ionicPopup","$state","$ionicHistory","$stateParams","$pouchDB",function(e,t,o,r,a,i,n,s){i.nextViewOptions({disableBack:!0}),e.data={},e.$on("$ionicView.load",function(){}),e.login=function(){var i={user:e.data.user||"",pwd:e.data.pwd||""};if(0!=i.user.length&&0!=i.pwd.length)t.post("dologin",i).then(function(e){if("success"===e.status){var t=e.data[0];r.alert({title:"Innova Prosystem",template:"Welcome, "+e.data[0].descri});o.set("user_logged",1);var i={descri:t.descri,partner_id:t.codigo,web_hijo:t.web_hijo,user_logged:1,email:t.user_email,tipo_usuario:t.tipo_usuario,star_pwr:t.star_pwr,des_key_val:t.des_key_val,des_count:t.des_count};o.setObject("user_innova",i),a.go("app.mainMenuForm")}else{r.alert({title:"Innova Prosystem",template:"User and Password Incorrect!"})}});else{r.alert({title:"Innova Prosystem",template:"Please, enter User and Password"})}}}]).controller("sideMenuCtrl",["$scope","$localstorage","userApi","$state","$ionicSideMenuDelegate","orderApi",function(e,t,o,r,a,i){function n(){if(1==t.get("user_logged")){var o=t.getObject("user_innova"),r=o.partner_id;i.get("resumebycustomer/"+r).then(function(t){"error"!=t.status?e.resumepartner=t.data:console.log(t)})}}e.show_menu=!1,e.resumepartner=[],1==t.get("user_logged")&&(e.show_menu=!0),e.$watch(function(){return t.get("user_logged")},function(o,r){1==t.get("user_logged")?(e.show_menu=!0,n()):e.show_menu=!1}),e.toggleSideMenu=function(){a.isOpen()?a.toggleLeft(!1):a.toggleLeft()},e.logOut=function(){t.clear()}}]).controller("mainMenuFormCtrl",["$scope","$state","$localstorage",function(e,t,o){e.leu=o.getObject("user_innova"),e.partner_id=e.partner_id,0==o.get("user_logged")&&t.go("app.loginForm"),e.showOrders=function(){t.go("app.ordersList")},e.createOrder=function(){o.set("order_id","newOrder"),t.go("app.orderDetailList")},e.showProducts=function(){t.go("app.productsList")},e.showCustomers=function(){t.go("app.customersList")}}]).controller("appdeveolpedbyCtrl",["$scope","$ionicHistory",function(e,t){e.goBack=function(){t.goBack()}}]).controller("ordersListCtrl",["$scope","orderApi","$localstorage","$ionicPopup","$ionicLoading","$timeout","$ionicListDelegate","$ionicTabsDelegate","$state",function(e,t,o,r,a,i,n,s,d){e.orders_list={},e.filterOrder={};var l=o.getObject("user_innova");e.partner_id=l.partner_id,0==o.get("tipo_documento").length?e.filterOrder.tipo_estado="ACT":e.filterOrder.tipo_estado=o.get("tipo_documento"),e.clickFilterOrder=function(t){return o.set("tipo_documento",t),e.filterOrder.tipo_estado=t,e.filterOrder},e.showOrdersDetail=function(e){o.set("order_id",e),d.go("app.orderDetailList")},e.showOrders=function(o){1==l.tipo_usuario?t.get("orders").then(function(t){e.orders_list=t.data}):t.get("orderbycustomer/"+o).then(function(t){e.orders_list=t.data})},e.showOrders(e.partner_id),e.selectTabWithIndex=function(e){s.select(e)},e.add_order=function(t,o){e.items.push({amount:t,name:o})},e.del_order=function(t,o){e.confirmDelete(t,o),n.closeOptionButtons()},e.confirmDelete=function(o,a){var i=r.confirm({title:"INNOVA PROSYSTEM",template:"Are you sure you want to Delete Order ["+o.order_id+"]?"});i.then(function(i){if(i){var n={};n.order_id=o.order_id,n.tipo_estado="ANU",t.put("order/"+o.order_id,n).then(function(t){if("error"!=t.status){e.orders_list.splice(a,1);r.alert({title:"INNOVA PROSYSTEM",template:"Deleted Order: "+o.order_id})}else console.log(t)})}})},e.pay_order=function(e){var t=e.order_id;if("0"==e.total_pagar){r.alert({title:"Innova Prosystem",template:"Do you need to add items firsts to paid this order."});return n.closeOptionButtons(),!1}o.set("order_id",t),d.go("app.paidForm")},e.showLinceseList=function(e){o.set("order_id",e),d.go("app.licenseList")},e.show=function(){a.show({content:"Loading",animation:"fade-in",showBackdrop:!0,maxWidth:200,showDelay:0})},e.hide=function(){a.hide()}}]).controller("orderDetailListCtrl",["$scope","$localstorage","orderApi","orderDetailApi","$ionicPopup","$ionicLoading","$state","$timeout","$ionicListDelegate","dateFactory","$base64",function(e,t,o,r,a,i,n,s,d,l,c){e.orders_detail_list=[],e.total_base_general=0,e.total_iva_general=0,e.total_pagar_general=0,e.total_items_general=0,e.order=[],e.order_id=t.get("order_id"),e.showLinceseList=function(e){t.set("order_id",e.order_id),n.go("app.licenseList")},e.pay_order_detail=function(e){var o=e.order_id;if(0!=!e.total_pagar){a.alert({title:"Innova Prosystem",template:"Do you need add items firts to paid this order."});return d.closeOptionButtons(),!1}t.set("order_id",o),n.go("app.paidForm")},e.show_detail=function(a){o.get("order/"+a).then(function(t){"error"!=t.status?(e.order=t.data[0],e.order.fecha_documento=l.formatDate(e.order.fecha_documento),e.order.fecha_pago=l.formatDate(e.order.fecha_pago),i.show({template:" Order Loaded",noBackdrop:!0,duration:3e3})):console.log(t)}),r.get("orderdetail/"+a).then(function(o){angular.forEach(o.data,function(o){o.avatar=o.descripcion.split(" ")[1],o.order_id=t.get("order_id"),o.total_items_tmp=o.total_items,o.total_base_tmp=o.total_base,o.total_iva_tmp=o.total_iva,o.total_pagar_tmp=o.total_pagar,o.total_base=o.costo*o.total_items,o.total_iva=o.iva*o.total_items,o.total_pagar=o.precio*o.total_items,e.total_base_general=e.total_base_general+o.total_base,e.total_iva_general=e.total_iva_general+o.total_iva,e.total_pagar_general=e.total_pagar_general+o.total_pagar,e.total_items_general=e.total_items_general+o.total_items,e.orders_detail_list.push(o)})})};var p=t.getObject("user_innova");e.new_order=function(){e.order={web_hijo:"INNOVA01",partner_id:p.partner_id,tipo_documento:"ORD",tipo_operacion:"DOC",tipo_estado:"ACT",fecha_documento:l.getToday(),fecha_vencimiento:l.getToday(),descripcion:"",total_base:0,total_iva:0,total_pagar:0,observacion:""}},e.new_order(),"newOrder"==e.order_id?o.post("order",e.order).then(function(o){"error"!=o.status?(e.order_id=o.last_id,t.set("order_id",o.last_id),e.show_detail(e.order_id),i.show({template:"New Order Created",noBackdrop:!0,duration:3e3})):console.log(o)}):(e.order.order_id=e.order_id,e.show_detail(e.order_id)),e.add_item=function(t,o){e.calculate(t,1)},e.sust_item=function(t,o){e.calculate(t,-1)},e.calculate=function(t,o){if(t.total_items||1==o){var r=t.total_pagar;t.total_items=t.total_items+o,t.total_base=t.costo*t.total_items,t.total_iva=t.iva*t.total_items,t.total_pagar=t.precio*t.total_items;var a=t.total_pagar-r;e.total_pagar_general=e.total_pagar_general+a,e.total_items_general=e.total_items_general+o,t.total_items<0&&(t.total_items=0)}},e.save_order_detail=function(s){var l=!1,p=t.getObject("user_innova");if(angular.forEach(e.orders_detail_list,function(e){oder_detail={},oder_detail.partner_id=p.partner_id,oder_detail.order_id=t.get("order_id"),oder_detail.tipo_documento="ORD",oder_detail.web_hijo=p.web_hijo,oder_detail.product_id=e.product_id,oder_detail.descripcion=e.descripcion,oder_detail.total_items=e.total_items,oder_detail.total_base=e.total_base,oder_detail.total_iva=e.total_iva,oder_detail.total_pagar=e.total_pagar,oder_detail.observacion=e.observacion,null==e.order_detail_id?e.total_items&&(l=!0,r.post("orderdetail",oder_detail).then(function(t){"error"!=t.status?(e.order_detail_id=t.last_id,e.total_base_tmp=e.total_base,e.total_iva_tmp=e.total_iva,e.total_pagar_tmp=e.total_pagar,i.show({template:"Item Save",noBackdrop:!0,duration:3e3})):console.log(t)})):e.total_pagar!=e.total_pagar_tmp&&(l=!0,r.put("orderdetail/"+e.order_detail_id,oder_detail).then(function(t){"error"!=t.status?(e.total_base_tmp=e.total_base,e.total_iva_tmp=e.total_iva,e.total_pagar_tmp=e.total_pagar):console.log(t)}))}),l){var u={};u.order_id=e.order_id,u.total_base=e.total_base_general,u.total_iva=e.total_iva_general,u.total_pagar=e.total_pagar_general,o.put("order/"+e.order_id,u).then(function(e){if("error"!=e.status){i.show({template:"Order Updated",noBackdrop:!0,duration:3e3});var r=t.getObject("user_innova"),l="n3H{J%xPnCF|"+r.descri+"|"+r.email+"|"+u.order_id+"|"+u.total_pagar;if(o.send_order_mail("send-email/"+c.encode(l)).then(function(e){"error"!=e.status}),s){if(d.closeOptionButtons(),0!=u.total_pagar)return t.set("order_id",u.order_id),n.go("app.paidForm"),!1;a.alert({title:"Innova Prosystem",template:"Do you need add items firts to paid this order."});return!1}n.go("app.ordersList")}else console.log(e)})}else n.go("app.paidForm")}}]).controller("paidFormCtrl",["$scope","orderApi","orderDetailApi","orderPayApi","orderLicenseApi","$localstorage","dateFactory","$ionicPopup","$q","componetFactory","$state",function(e,t,o,r,a,n,s,d,l,c,p){e.order={},e.order_id=n.get("order_id"),e.pago={},e.lic={},e.newPay=function(){e.pago={web_hijo:"",partner_id:"",order_id:e.order_id,tipo_pago:"",tipo_estado:"ACT",fecha_pago:s.getToday(),banco:"",cuenta:"",referencia:"",total:0,observacion:""}},e.load_order=function(o){t.get("order/"+o,e.order).then(function(t){"error"!=t.status?(e.order=t.data[0],e.pago.order_id=e.order.order_id,e.newPay(),e.pago.order_id=e.order.order_id,e.pago.partner_id=e.order.partner_id,e.pago.web_hijo=e.order.web_hijo,e.pago.total=e.order.total_pagar,e.pago.fecha_documento=s.formatDate(e.pago.fecha_documento),e.pago.fecha_pago=s.formatDate(e.pago.fecha_pago),e.newLicense()):console.log(t)})},e.load_order(e.order_id),e.newLicense=function(){e.lic={web_hijo:e.pago.web_hijo,partner_id:e.pago.partner_id,order_id:e.pago.order_id,product_id:"",fecha_licencia:s.getToday(),serial:"",nombre:"",siglas:"",direccion1:"",direccion2:"",empresa_rif:"",empresa_contacto:"",empresa_nombre:"",empresa_email:"",usada:0,observacion:""}},e.add_lisences=function(t){o.get("orderdetailitems/"+t).then(function(t){if("error"!=t.status){var o=[];angular.forEach(t.data,function(t){for(e.newLicense(),e.lic.product_id=t.product_id,i=1;i<=t.total_items;i++)var r=a.post("orderlicense",e.lic).then(function(e){"error"!=e.status?o.push(r):console.log(e)})}),l.all(o).then(function(t){d.alert({title:"Innova Prosystem",template:"Pay to Order No.["+e.order_id+"] Saved!."});p.go("app.ordersList")})}else console.log(t)})},e.save_order_pay=function(o){delete o.fecha_documento,o.fecha_pago=s.getToday(),r.post("orderpay",o).then(function(r){if("error"!=r.status){var a={};a.order_id=o.order_id,a.tipo_estado="PAG",t.put("order/"+o.order_id,a).then(function(t){"error"!=t.status?e.add_lisences(o.order_id):console.log(t)})}else console.log(r)})}}]).controller("licenseFormCtrl",["$scope","orderLicenseApi","$localstorage","$state",function(e,t,o,r){var a=o.getObject("user_innova");e.activar_serial=!0,1==a.tipo_usuario&&(e.activar_serial=!1),e.newLicense=function(t){e.lic=t},e.newLicense(o.getObject("license")),e.save_order_lic=function(e){e.usada=1,e.nombre=e.descripcion,delete e.descripcion,t.put("orderlicense/"+e.order_license_id,e).then(function(e){"error"!=e.status?r.go("app.licenseList"):console.log(e)})}}]).controller("licenseListCtrl",["$scope","orderApi","orderLicenseApi","$localstorage","$state",function(e,t,o,r,a){e.order_id=r.get("order_id"),e.license_list={},e.showLicenseForm=function(e){r.setObject("license",e),a.go("app.licenseForm")},e.showOrdersList=function(t){o.get("orderlisence/"+t).then(function(t){e.license_list=t.data})},e.showOrdersList(e.order_id)}]).controller("partnerFormCtrl",["$scope","userApi","$ionicPopup","$state",function(e,t,o,r){e.data={},e.newCustomer=function(){e.data={cedula:"",codigo:"",descri:"",user_email:"",user_telefono:"",pwd:"",repwd:""}},e.newCustomer(),e.cancel_save=function(){e.newCustomer()},e.save_cust=function(){if(0!==e.data.pwd.length&&0!==e.data.repwd.length)if(e.data.pwd===e.data.repwd){var a={web_hijo:"INNOVA01",cedula:e.data.cedula||"",codigo:e.data.cedula||"",descri:e.data.descri||"",user_email:e.data.user_email||"",user_tlf:e.data.user_telefono||"",pwd:e.data.pwd,tipo_usuario:"2",pregunta1:"null",pregunta2:"null",respuesta1:"null",respuesta2:"null"};t.post("user",a).then(function(e){if("success"===e.status){o.alert({title:"Innova Prosystem",template:"Thank you, for Registry, we will send to you an email, once we validate your information."});r.go("app.loginForm")}else{o.alert({title:"Innova Prosystem",template:"Sorry. there is a Error."})}})}else{o.alert({title:"Innova Prosystem",template:"Diferent Password and Confirm Password!"})}else{o.alert({title:"Innova Prosystem",template:"Insert Password and Confirm Password"})}}}]).controller("productsListCtrl",["$scope","productsApi","$localstorage","$ionicPopup","$ionicLoading","$timeout","$ionicListDelegate","$ionicTabsDelegate","$state",function(e,t,o,r,a,i,n,s,d){e.products_list=[],e.filterproduct={},o.removeKey("product"),e.showProductsDetail=function(e){o.removeKey("product"),e.new_item=!1,o.setObject("product",e),d.go("app.productForm")},e.showProducts=function(){t.get("products").then(function(t){"error"!=t.status?(angular.forEach(t.data,function(e){console.log(typeof e.descripcion),"string"==typeof e.descripcion&&(e.avatar=e.descripcion.split(" ")[1])}),e.products_list=t.data):console.log(t)})},e.showProducts(),e.selectTabWithIndex=function(e){s.select(e)},e.addProducts=function(){o.removeKey("product"),d.go("app.productForm")},e.del_product=function(t,o){e.confirmDelete(t,o),n.closeOptionButtons()},e.confirmDelete=function(o,a){var i=r.confirm({title:"INNOVA PROSYSTEM",template:"Are you sure you want to Delete this product ["+o.descripcion+"]?"});i.then(function(i){if(i){var n={};n.product_id=o.product_id,t["delete"]("products/"+o.product_id).then(function(t){if("error"!=t.status){e.products_list.splice(a,1);r.alert({title:"INNOVA PROSYSTEM",template:"Deleted product: "+o.descripcion})}else console.log(t)})}})}}]).controller("productFormCtrl",["$scope","productsApi","$localstorage","$ionicPopup","$ionicLoading","$timeout","$ionicListDelegate","$ionicTabsDelegate","$state",function(e,t,o,r,a,i,n,s,d){e.data={},e.newProducts=function(){e.data=o.getObject("product"),e.data.new_item=!1,e.data||(e.data={web_hijo:"INNOVA01",product_id:"",descripcion:"",costo:"",iva:"",precio:"",activo_vender:0},e.data.new_item=!0),e.data.activo_vender=1==e.data.activo_vender?!0:!1},e.cancel_save=function(){e.newProducts()},e.newProducts(),e.cancel_save=function(){e.newProducts()},e.save_item=function(){if(0!==e.data.product_id.length)if(0!==e.data.descripcion.length){var a=angular.copy(e.data),i=e.data.product_id,n=a.new_item;delete a.new_item,delete a.avatar,a.activo_vender=1==a.activo_vender?1:0,n?t.post("product",a).then(function(e){"error"!=e.status?(o.removeKey("product"),d.go("app.productsList")):console.log(e)}):(delete a.product_id,t.put("product/"+i,a).then(function(e){"error"!=e.status?(o.removeKey("product"),d.go("app.productsList")):console.log(e)}))}else{r.alert({title:"Innova Prosystem",template:"Insert Description Product"})}else{r.alert({title:"Innova Prosystem",template:"Insert ID Product"})}}}]).controller("customersListCtrl",["$scope","userApi","$localstorage","$ionicPopup","$ionicLoading","$timeout","$ionicListDelegate","$ionicTabsDelegate","$state",function(e,t,o,r,a,i,n,s,d){e.partners_list=[],e.filterpartner={},o.removeKey("partner"),e.showpartnersDetail=function(e){o.removeKey("partner"),e.new_item=!1,o.setObject("partner",e),d.go("app.customerForm")},e.showPartners=function(o){t.getAll("users").then(function(t){"error"!=t.status?e.partners_list=t.data:console.log(t)})},e.showPartners(e.partner_id),e.selectTabWithIndex=function(e){s.select(e)},e.addPartners=function(){o.removeKey("partner"),d.go("app.customerForm")},e.edit_partner=function(e){d.go("app.customerForm")},e.del_partner=function(t,o){e.confirmDelete(t,o),n.closeOptionButtons()},e.confirmDelete=function(o,a){var i=r.confirm({title:"INNOVA PROSYSTEM",template:"Are you sure you want to Delete partner ["+o.descri+"]?"});i.then(function(i){if(i){var n={};n.partner_id=o.partner_id,t["delete"]("user/"+o.codigo).then(function(t){if("error"!=t.status){e.partners_list.splice(a,1);r.alert({title:"INNOVA PROSYSTEM",template:"Deleted partner: "+o.descri})}else console.log(t)})}})}}]).controller("customerFormCtrl",["$scope","userApi","$localstorage","$ionicPopup","$ionicLoading","$timeout","$ionicListDelegate","$ionicTabsDelegate","$state",function(e,t,o,r,a,i,n,s,d){e.data={},e.show_password=!1,e.newPartner=function(){e.data=o.getObject("partner"),e.data.new_item=!1,e.tipo_partners=[{value:1,descri:"Partner Regional",selected:!1},{value:2,descri:"Partner Exclusivo",selected:!1},{value:3,descri:"Partner No Exclusivo",selected:!1},{value:4,descri:"Partner Independiente",selected:!1}],e.data||(e.data={web_hijo:"INNOVA01",cedula:"",codigo:"",descri:"",user_email:"",user_tlf:"",pwd:"",repwd:"",tipo_usuario:"2",tipo_partner:"",pregunta1:"",pregunta2:"",respuesta1:"",respuesta2:"",reg_estatus:0},e.data.new_item=!0),e.data.reg_estatus=1==e.data.reg_estatus?!0:!1},e.newPartner(),e.cancel_save=function(){e.newPartner()},e.save_cust=function(){if(e.show_password){if(0===e.data.pwd.length||0===e.data.repwd.length){r.alert({title:"Innova Prosystem",template:"Insert Password and Confirm Password"});return}if(e.data.pwd!==e.data.repwd){r.alert({title:"Innova Prosystem",template:"Diferent Password and Confirm Password!"});return}}var a=angular.copy(e.data),i=a.codigo,n=a.new_item;delete a.new_item,delete a.web_padre,delete a.repwd,a.conectado=0,a.des_count=0,a.des_key_val=0,a.stat_pwr=0,a.reg_estatus=1==e.data.reg_estatus?1:0,n?t.post("user",a).then(function(e){"error"!=e.status?(o.removeKey("partner"),d.go("app.customersList")):console.log(e)}):(delete a.codigo,t.put("user/"+i,a).then(function(e){"error"!=e.status?(o.removeKey("partner"),d.go("app.customersList")):console.log(e)}))}}]).controller("partnertCommentCtrl",["$scope","messageApi","$base64","$ionicPopup","$localstorage",function(e,t,o,r,a){var i={message:"",rating:5};e.comment=angular.copy(i),e.sendComments=function(){var n=a.getObject("user_innova"),s="n3H{J%xPnCF|"+n.descri+"|"+n.email+"|"+e.comment.message+"|"+e.comment.rating;t.send_mail("send-email/"+o.encode(s)).then(function(t){if(e.comment=angular.copy(i),"error"!=t.status){r.alert({title:"Innova Prosystem",template:"Thank you so Much... Your opinion is very important to us!"})}})}}]).controller("backButtonCtrl",["$scope",function(e){e.goBack=function(){$ionicHistory.goBack()}}]).filter("custom",function(){return function(e,t){if(!e)return e;if(!t)return e;var o={};return angular.forEach(e,function(e,r){e.tipo_estado==t.tipo_estado&&(o[r]=e)}),o}});