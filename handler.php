<?php
/**
 * handler.php
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

if(isset($_POST["code"])){
  foreach($_POST as $key => $value){
    $new_product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }
  $statement = $mysqli_conn->prepare("SELECT s_products.name, s_products.price FROM s_products WHERE s_products.id=? LIMIT 1");
  $statement->bind_param('s', $new_product['code']);
  $statement->execute();
  $statement->bind_result($name, $price);

  while($statement->fetch()){
    $new_product["name"] = $name;
    $new_product["price"] = $price;
    $new_product['qty'] += $_SESSION['products'][$new_product['code']]['qty']; //
    if(isset($_SESSION["products"])){
      if(isset($_SESSION["products"][$new_product['code']])){
        unset($_SESSION["products"][$new_product['code']]);
      }
    }
    $_SESSION["products"][$new_product['code']] = $new_product;
  }
  $total = 0;
  foreach($_SESSION["products"] as $product){
    $product_price = $product["price"];
    $product_qty = $product["qty"];
    $subtotal = ($product_price * $product_qty);
    $total = ($total + $subtotal);
  }
  $total_items = count($_SESSION["products"]);
  $total_sum = sprintf("%01.2f",$total);
  die(json_encode(array('items'=>$total_items,'sum'=>$total_sum)));
}

if(isset($_POST["load_cart"]) && $_POST["load_cart"]==1){
  if(isset($_SESSION["products"]) && count($_SESSION["products"])>0){
    $cart_box = "<ul class=\"cart-products-loaded\">\n";
    $total = 0;
    foreach($_SESSION["products"] as $product){
      $product_name  = $product["name"]; 
      $product_price = $product["price"];
      $product_code  = $product["code"];
      $product_qty   = $product["qty"];

      $cart_box .=  "<li>".$product_name." x ".$product_qty." &mdash; ".sprintf("%01.0f", ($product_price * $product_qty)).$currency." <a href=\"#\" class=\"remove-item\" data-code=\"".$product_code."\" title=\"Удалить\">&times;</a></li>\n";
      $subtotal = ($product_price * $product_qty);
      $total = ($total + $subtotal);
    }
    $cart_box .= "</ul>\n";
    $cart_box .= "<div class=\"cart-products-total\">Сумма: ".sprintf("%01.2f",$total).$currency." <u><a href=\"index.php?page=cart\" title=\"Перейти в корзину\">Оформить заказ</a></u></div>\n";
    die($cart_box);
  }else{
    die("Ваша корзина пуста");
  }
}

if(isset($_GET["remove_code"]) && isset($_SESSION["products"])){
  $product_code   = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING);

  if(isset($_SESSION["products"][$product_code])){
    unset($_SESSION["products"][$product_code]);
  }
	
  $total = 0;
  foreach($_SESSION["products"] as $product){
    $product_price = $product["price"];
    $product_qty = $product["qty"];
    $subtotal = ($product_price * $product_qty);
    $total = ($total + $subtotal);
  }
  $total_items = count($_SESSION["products"]);
  $total_sum = sprintf("%01.0f",$total);
  die(json_encode(array('items'=>$total_items,'sum'=>$total_sum)));
}

if(isset($_POST["load_product"])){
  $id = (intval($_POST["load_product"]) > 0) ? intval($_POST["load_product"]) : 0;
  $valid = $mysqli_conn->query("SELECT s_products.name, s_products.body, s_products.price FROM s_products WHERE s_products.id = '".$mysqli_conn->escape_string($id)."' AND s_products.visible = '1'");

  $products_item = "<div id=\"products-item\">\n";
  while($row = $valid->fetch_assoc()){
    $products_item.= "<h1>".$row['name']."</h1>";
    $products_item.= "<p>".$row['body']."</p>";
    $products_item.= "<p>".sprintf("%01.0f",$row['price']).$currency."</p>";
  }
  echo $products_item;
}
