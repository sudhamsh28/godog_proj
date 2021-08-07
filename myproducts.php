
<?php
session_start();
require_once("dbcontroller.php");

$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tb1product WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<HTML>
<HEAD>
	<TITLE>Simple PHP Shopping Cart</TITLE>
		<link href="css/myproductsstyle.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="shortcut icon" href="img/lifestyleStore.png" />
        
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
        <!-- jquery library -->
        <script type="text/javascript" src="bootstrap/js/jquery-3.2.1.min.js"></script>
        <!-- Latest compiled and minified javascript -->
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</HEAD>
<BODY>
	<div>
		<?php
            require 'header.php';
        ?>
	</div>
	

	<div id="product-grid">
		<div class="txt-heading">Products</div>
		<?php
		$product_array = $db_handle->runQuery("SELECT * FROM tb1product ORDER BY id ASC");
		if (!empty($product_array)) { 
			foreach($product_array as $key=>$value){
		?>
			<div class="product-item">
				<form method="post" action="cart_add.php?id=<?php echo $product_array[$key]["id"]; ?>">
				<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
				<div class="product-tile-footer">
				<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
				<div class="product-price"><?php echo "Rs.".$product_array[$key]["price"]; ?></div>
				<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
				</div>
				</form>
			</div>
		<?php
			}
		}
		?>
	</div>
</BODY>
</HTML>