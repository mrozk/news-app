<?php 

$products = [];
$products[] = [
      "id"       => 22,
      "name"     => 'Гусятина',
      "width"    => 10,
      "height"   => 10,
      "length"   => 10,
      "weight"   => 1,
      "price"    => 10,
      "quantity" => 1,
      "sku"      => 'wombat'
];
$cart = [
    'products' => $products,
    'discount' => 0
];
echo http_build_query($cart);

?>
