<?php

@include_once(__DIR__.'/src/Helpers/Auth.php');
@include_once(__DIR__.'/src/Helpers/Message.php');
@include_once(__DIR__.'/src/Database/Database.php');

setLastVisitedPage();

@include_once(__DIR__ . '/template/head.inc.php');

if (!isset($_GET['product_id'])) {
   setError('failed', 'Geen ID van het product ontvangen.');
   header('Location: index.php');
   exit(0);
}


$product_id = $_GET['product_id'];

// Now get all the products
Database::query("SELECT * FROM `products` WHERE `products`.`productID` = :id", [':id' => $product_id]);
$product = Database::get();
?>

<!-- -- Css Style -- -->
<style>
  .product-image {
    width: 100%;
    height: 500px;
    object-fit: contain;
    padding-bottom: 70px;
  }
  
</style>

 <button class="btn" onclick="history.back()">Go Back</button>

<!-- -- Css Styles -- -->

<div class="uk-grid">
   <section class="uk-width-1">
      <div class="uk-grid uk-card uk-card-default">
         <section class="uk-width-1-2 uk-card-media-left">
            <img src="data:AnimeHoodie/jpg;base64,<?= base64_encode($product->image) ?>" alt="Product image" class="product-image uk-align-center uk-width-1-1 uk-height-auto uk-object-cover">
         </section>
         <section class="uk-width-1-2 uk-card-body uk-flex uk-flex-column uk-flex-between">
            <div>
               <h1 style="font-weight: bold; border-bottom: 1px solid grey; padding-bottom: 15px;"><?= $product->productname ?></h1>
               <p style="font-size: large;">
                  <?= $product->description ?>
               </p>
            </div>
            <div class="uk-flex uk-flex-between uk-flex-middle">
               <div class="price-block">
                  
                     <?php
                     $formattedPrice = number_format($product->price, 2, ',', '')?>
                     <p class="product-view__price uk-text-bold uk-text-danger uk-text-left uk-text-bolder">
                     &euro; <?= $formattedPrice ?>
                  </p>
               </div>
               <div>
                  <?php if (isLoggedIn()) : ?>
                     <form method="POST" action="src/Formhandlers/addtocart.php">
                        <input type="hidden" name="product_id" value="<?= $product->id ?>" />
                        <button type="submit" class="uk-button uk-button-primary">
                           <span uk-icon="icon: cart"></span>
                           In winkelwagen
                        </button>
                         <button type="submit" class="uk-button uk-button-default favorite-button">
                     <span uk-icon="icon: heart"></span>
                     Favoriet
                     </button>
                     </form>
                        
                           
                     </form>
                  <?php else : ?>
                     <a href="javascript:void" class="uk-button uk-button-primary" onclick="event.preventDefault(); alert('Om te kunnen bestellen dient u geregistreerd en ingelogd te zijn.');">
                        <span uk-icon="icon: cart"></span>
                        In winkelwagen
                     </a>
                     <form method="POST" action="src/Formhandlers/favorite.php" class="favorite-form">
                     <input type="hidden" name="product_id" value="<?= $product->id ?>" />
                  <?php endif; ?>
               </div>
            </div>
         </section>
      </div>
   </section>
</div>

<?php
@include_once(__DIR__ . '/template/foot.inc.php');
