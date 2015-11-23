<?php
/**
 * index.php
 * 
 *
 * @author Evgeniy Artemyev
 * @version 0.0.1
 */

define('IMS', 1);

session_start();

if(!include('config.php')){
  die("Не удается подключить конфигурацию.");
}

@header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT');
@header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Тестовое задание</title>

<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
body{margin:0px; padding:20px; background: url(https://static.beget.ru/img/bg.png) repeat-x;}
h1, p, a, td, th {font-family: Arial, sans-serif;}
p, a, td, th {font-size: 13px;}
a:hover{text-decoration:none;}
a {color: black;font-weight: bold;}
#vspl {background: #FFFFFF; display: none; height: 400px; left: 50%; margin: -200px 0 0 -300px; position: fixed; top: 50%; width: 600px; z-index: 999; padding: 20px;}
#korzina {position: fixed; top: 20px; right: 20px; background: #ccdff7; width: 150px; height: 60px; padding: 10px;cursor:pointer;font-size: 14px;}
.overlay{background: rgba(0,0,0,0.4); display: none; position: fixed; height: 100%; width: 100%; z-index: 998; left: 0; top: 0;}
#products-item { display: block; margin-left: auto; margin-right: auto; background: #fff; padding: 15px 15px 15px 25px; border: 1px solid #ECECEC; border-radius: 4px; width: 600px; }
#products-list { display: table; margin-left: auto; margin-right: auto; text-align: center; background: #fff; padding: 15px 15px 15px 25px; border: 1px solid #ECECEC; border-radius: 4px; }
#products-list .row { display:table-row; }
#products-list .cell { display:table-cell; padding:5px; }
#products-list button {background:none!important; border:none; padding:0!important; font: inherit; border-bottom:1px solid #444; cursor: pointer; }
#products-list button[disabled=disabled] { background: #FC84A8; }
.view-korzina { position: absolute; right:20px; margin-left:auto; margin-right:auto; max-width: 450px; color: #000; background: #ccdff7; padding: 10px; margin-top: 90px; display:none; }
.view-korzina:after { content: ''; position: absolute; bottom: 100%; right: 20%; margin-left: -8px; width: 0; height: 0;  border-bottom: 8px solid rgba(255, 0, 97, 1); border-right: 8px solid transparent; border-left: 8px solid transparent; }
.view-korzina ul.cart-products-loaded { margin: 0; padding: 0; list-style: none; }
.view-korzina .close-korzina { float: right; }
#korzina-results ul.cart-products-loaded li { margin-bottom: 1px;  padding: 6px 4px 6px 10px; }
.view-korzina .remove-item { float:right;  text-decoration:none; }
.view-korzina .cart-products-total { font-weight: bold;  text-align: right;  padding: 5px 0px 0px 5px; }
ul.view-cart { width: 600px; margin-left: auto; margin-right: auto; background: #fff; padding: 15px 15px 15px 25px; list-style: none; }
ul.view-cart { width: 600px; margin-left: auto; margin-right: auto; background: #fff; padding: 15px 15px 15px 25px; list-style: none; }
ul.view-cart li span { float: right; }
ul.view-cart li.view-cart-total { border-top: 1px solid #ddd; padding-top: 5px; margin-top: 5px; text-align: right; }
hr { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
.show-content #products-item { width: 100%; margin-left: 0; margin-right: 0; padding:0; border:0; }
.close-btn { position: absolute; top:0; right:0; cursor: pointer;  border: 0px; padding1: 2% 2%; }
.close-btn:hover { background: #ccc; }
</style>

</head>

<body>
<div id="korzina" class="cart-box" title="Посмотреть корзину">В корзине: 
<?php 
if(isset($_SESSION["products"])){
  echo count($_SESSION["products"]); 
}else{
  echo 0; 
}
?> товаров.
<?php
if(isset($_SESSION["products"]) && count($_SESSION["products"]) > 0){
  $total = 0;
  foreach($_SESSION["products"] as $product){
    $product_price = $product["price"];
    $product_qty = $product["qty"];
    $subtotal = ($product_price * $product_qty);
    $total = ($total + $subtotal);
  }
  echo " На сумму: ".sprintf("%01.0f",$total).$currency;
}
?>
</div>
<div class="view-korzina">
  <a href="#" class="close-korzina">Закрыть</a>
  <div id="korzina-results"></div>
</div>

<?php
$legal_pages = array('index','product','cart');

$page = (isset($_REQUEST['page']) && in_array($_REQUEST['page'],$legal_pages)) ? $_REQUEST['page'] : 'index';

if($page == 'index'){
  $results = $mysqli_conn->query("SELECT s_products.id, s_products.url, s_products.name, s_products.price, s_products.annotation FROM s_products WHERE s_products.visible = '1' ORDER BY s_products.created ASC");

  $products_list = "<div id=\"products-list\">\n"
                  ."  <div class=\"row\">\n"
                  ."    <div class=\"cell\">Название товара</div>\n"
                  ."    <div class=\"cell\">Краткое описание</div>\n"
                  ."    <div class=\"cell\">Цена</div>\n"
                  ."    <div class=\"cell\">Купить</div>\n"
                  ."    <div class=\"cell\"> </div>\n"
                  ."  </div>\n";

  while($row = $results->fetch_assoc()){
    $products_list.= "  <div class=\"row\">\n"
                    ."    <div class=\"cell\"><a href=\"index.php?page=product&amp;id={$row["id"]}&amp;cpu={$row["url"]}\">{$row["name"]}</a></div>\n"
                    ."    <div class=\"cell\">{$row["annotation"]}</div>\n"
                    ."    <div class=\"cell\">{$row["price"]}{$currency}</div>\n"
                    ."    <div class=\"cell\"><form class=\"add-item\" method=\"post\" action_=\"handler.php\"><input name=\"qty\" type=\"hidden\" value=\"1\" /><input name=\"code\" type=\"hidden\" value=\"{$row["id"]}\" /><button type=\"submit\">Купить</button></form></div>\n"
                    ."    <div class=\"cell\"><a href=\"#\" class=\"popup\" data-product=\"{$row["id"]}\"\" >Быстрый просмотр</a></div>\n"
                    ."  </div>\n";
  }
  $products_list .= "</div>\n";

  echo $products_list;
}

if($page == 'product'){
  $id = (intval($_REQUEST['id']) > 0) ? intval($_REQUEST['id']) : 0;
  if(!empty($cpu) && $config['seourl'] == 'Y'){
    $valid = $mysqli_conn->query("SELECT s_products.* FROM s_products WHERE s_products.url = '".$mysqli_conn->escape_string($cpu)."' AND s_products.visible = '1'");
    $v = 0;
  }else{
    $valid = $mysqli_conn->query("SELECT s_products.* FROM s_products WHERE s_products.id = '".$mysqli_conn->escape_string($id)."' AND s_products.visible = '1'");
    $v = 1;
  }

  $products_item = "<div id=\"products-item\">\n";
  while($row = $valid->fetch_assoc()){
    $products_item.= "<h1>".$row['name']."</h1>";
    $products_item.= "<p>".$row['body']."</p>";
    $products_item.= "<p>".sprintf("%01.0f",$row['price']).$currency."</p>";
    $products_item.= "<p><form class=\"add-item\" method=\"post\" action_=\"handler.php\"><input name=\"qty\" type=\"hidden\" value=\"1\" /><input name=\"code\" type=\"hidden\" value=\"{$row["id"]}\" /><button type=\"submit\">Купить</button></form></p>";
  }
  $products_item.= "</div>\n";
  echo $products_item;
}

if($page == 'cart'){
  if(isset($_SESSION["products"]) && count($_SESSION["products"])>0){
    $total      = 0;
    $cart_box     = "<ul class=\"view-cart\">\n";
    foreach($_SESSION["products"] as $product){
      $product_name  = $product["name"];
      $product_qty   = $product["qty"];
      $product_price = $product["price"];
      $product_code  = $product["code"];
      $item_price    = sprintf("%01.0f",($product_price * $product_qty));
      $cart_box     .= "<li>#".$product_code." &ndash; ".$product_name." x ".$product_qty."шт. <span>".$item_price.$currency."</span></li>";
      $subtotal      = ($product_price * $product_qty);
      $total         = ($total + $subtotal);
    }

    $grand_total = $total + $shipping;
    $shipping = ($shipping) ? "Доставка: ". sprintf("%01.0f", $shipping).$currency."<br />" : "";
    $cart_box .= "<li class=\"view-cart-total\">".$shipping." <hr>Итог:  ".sprintf("%01.0f", $grand_total).$currency."</li>\n";
    $cart_box .= "</ul>\n";

    echo $cart_box;
  }else{
    echo "Ваша корзина пуста";
  }
}

if($page != 'index'){
	echo "<a href=\"/\" style=\"margin-left: auto; margin-right: auto;\">Вернуться на главную";
}
?>
<div class="overlay"></div>
<div id="vspl"><div class="show-content"></div><button class="close-btn">Закрыть</button></div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".add-item").submit(function(e){
    var form_data = $(this).serialize();
    var button_content = $(this).find('button[type=submit]');
    button_content.html('Добавление...');

    $.ajax({
      url: "handler.php",
      type: "POST",
      dataType:"json",
      data: form_data
    }).done(function(data){
      $sum = (data.sum > 0) ? ' На сумму: '+data.sum+'<?php echo $currency; ?>' : '';
      $("#korzina").html('В корзине: '+data.items+' товаров.' + $sum);
      button_content.html('Купить');
      //alert("Товар добавлен в корзину!");
      if($(".view-korzina").css("display") == "block"){
        $(".cart-box").trigger( "click" );
      }
    })
    e.preventDefault();
  });

  // Быстрый просмотр корзины
  $( ".cart-box").click(function(e) {
    e.preventDefault(); 
    $(".view-korzina").fadeIn();
    $("#korzina-results" ).load("handler.php", {"load_cart":"1"});
  });

  // Закрыть корзину
  $( ".close-korzina").click(function(e){
    e.preventDefault(); 
    $(".view-korzina").fadeOut();
  });

  // Удалить из корзины
  $("#korzina-results").on('click', 'a.remove-item', function(e) {
    e.preventDefault(); 
    var pcode = $(this).attr("data-code");
    $(this).parent().fadeOut();
    $.getJSON("handler.php", {"remove_code":pcode} , function(data){
      $sum = (data.sum > 0) ? ' На сумму: '+data.sum+'<?php echo $currency; ?>' : '';
      $("#korzina").html('В корзине: '+data.items+' товаров.' + $sum);
      $(".cart-box").trigger("click");
    });
  });

  // Быстрый просмотр товара
  $(".popup").click(function(e) {
    var productId = $(this).data('product');
    e.preventDefault(); 
    $(".overlay, #vspl").fadeIn();
    $(".show-content").load("handler.php", {"load_product":productId});
  });

  function closePopup() {
    $('.overlay, #vspl').hide();
  }
  $('.close-btn').click(function() {
    closePopup();
  });

  $(document).keyup(function(e) {
    if (e.keyCode == 27) {
      closePopup();
    }
  });
});
</script>

</body>
</html>
