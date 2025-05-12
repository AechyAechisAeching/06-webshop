
<?php

@include_once(__DIR__.'/src/Helpers/Auth.php');
@include_once(__DIR__.'/src/Helpers/Message.php');
@include_once(__DIR__.'/src/Database/Database.php');

setLastVisitedPage();

@include_once(__DIR__ . '/template/head.inc.php');

// Get all the categories first
Database::query("SELECT * FROM `categories`");
$categories = Database::getAll();

$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];

if (!empty($selectedCategories)) {
   $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
   Database::query("SELECT * FROM `products` WHERE `category` IN ($placeholders)", $selectedCategories);
} else {
   Database::query("SELECT * FROM `products`");
}

$products = Database::getAll();


?>

      <!-- -- Css Styles -- -->
         <style>
            
.product-image {
  width: 100%;
  height: 200px;
  object-fit: contain;
  border-bottom: 2px solid black;
}
.category-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 30px;
  align-items: center;
}

.category-filters input[type="checkbox"] {
  display: none;
}

.category-filters label {
  padding: 10px 18px;
  border: 2px solid  rgb(201, 201, 201);
  border-radius: 999px;
  background-color: #f0f0f0;
  color: #333;
  cursor: pointer;
  transition: all 0.25s ease-in-out;
  font-weight: 500;
  user-select: none;
}

.category-filters input[type="checkbox"]:checked + label {
  background-color:  rgba(0, 118, 253, 0.96);
  color: #fff;
  border-color: 3px solid black;
}

.category-filters label:hover {
  background-color:  rgb(225, 230, 255);
  color: #000;
  border-color: #000;
  transform: translateY(-1px) scale(1.04);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}



.product-card {
  min-width: 400px;
  max-width: 400px;
  width: 100%;
  border: 2px solid grey;
  border-radius: 0.5rem;
  transition: 0.3s ease-in-out;
}

.product-card:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 15px rgba(65, 65, 65, 0.41);
}


.product-card-title {
  font-size: 1.4rem;
  font-weight: bold;
  margin-left: 10px;
  margin-bottom: 10px;
  border-bottom: 4px solid black;
}

.product-card-p {
  margin: 5px 0;
}


.product-price {
  font-size: 1.2rem;
  font-weight: bold;
  color:rgb(255, 0, 0);
  margin-top: 10px;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  width: 100%;
}


</style>

      <!-- -- Css Styles -- -->

      <?php if (hasMessage('success')): ?>
         <div class="uk-alert-success" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getMessage('success') ?></p>
         </div>
      <?php endif; ?>

      <?php if (hasError('failed')) : ?>
         <div class="uk-alert-danger" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getError('failed') ?></p>
         </div>
      <?php endif; ?>

      <div class="uk-grid">
         <section class="uk-width-1-1">
  <form method="GET" id="categoryForm" class="category-filters">
    <?php foreach ($categories as $category): 
      $value = strtolower($category->name);
    ?>
      <input 
        class="category-checkbox" 
        type="checkbox" 
        name="categories[]" 
        id="<?= $value ?>" 
        value="<?= $value ?>" 
        onchange="document.getElementById('categoryForm').submit()" 
        <?= in_array($value, $selectedCategories ?? []) ? 'checked' : '' ?>
      />
      <label for="<?= $value ?>"><?= ucfirst($value) ?></label>
    <?php endforeach; ?>

   <h3>Categorieën</h3>
   <hr class="uk-divider" />
   <div>
      <input class="uk-checkbox" id="hoodies" type="checkbox" name="categories[]" value="hoodies"
         onchange="document.getElementById('categoryForm').submit()" 
         <?= in_array('hoodies', $selectedCategories ?? []) ? 'checked' : '' ?> />
      <label for="hoodies">Hoodies</label>
   </div>

   <div>
      <input class="uk-checkbox" id="pants" type="checkbox" name="categories[]" value="pants"
         onchange="document.getElementById('categoryForm').submit()" 
         <?= in_array('pants', $selectedCategories ?? []) ? 'checked' : '' ?> />
      <label for="pants">Pants</label>
   </div>
   <div>
      <input class="uk-checkbox" id="accessoires" type="checkbox" name="categories[]" value="accessoires"
         onchange="document.getElementById('categoryForm').submit()" 
         <?= in_array('accessoires', $selectedCategories ?? []) ? 'checked' : '' ?> />
      <label for="accessoires">Accessoires</label>
   </div>

   <div>
      <input class="uk-checkbox" id="shoes" type="checkbox" name="categories[]" value="shoes"
         onchange="document.getElementById('categoryForm').submit()" 
         <?= in_array('shoes', $selectedCategories ?? []) ? 'checked' : '' ?> />
      <label for="shoes">Shoes</label>
   </div>
</form>
 </section>

         <section class="uk-width-5-8">
           <h4 style="padding: 5px;" class="uk-text-muted uk-text-small">
    Gekozen categorieën: 
    <?php  
        if (!empty($selectedCategories)) {
            echo implode(', ', array_map('ucfirst', $selectedCategories)); 
        } else {
            echo 'Alle';
        }
    ?>
</h4>
            <div class="product-grid">
               <?php foreach ($products as $product) : ?>
                <!-- PRODUCT KAART 1 -->
               <a class="product-card uk-card uk-card-home uk-card-default uk-card-small" href="product.php?product_id=<?= $product->productID ?>">
  <div class="uk-card-media-top uk-align-center">
    <img src="data:image/jpeg;base64,<?= base64_encode($product->image) ?>" alt="Product image" class="product-image">
  </div>
  <div class="uk-card-body uk-card-body-home">
    <h4 class="product-card-title"><?= htmlspecialchars($product->productname) ?></h4>
    <p class="product-card-p"><?= substr($product->description, 0, 89) . '...' ?></p>
    <p class="product-card-p product-price">&euro; <?= $product->price ?></p>
  </div>
</a>


                
               

                  <!-- EINDE PRODUCT KAART 1 -->
               <?php endforeach; ?>
            </div>
         </section>
      </div>

<?php
include_once(__DIR__ . '/template/foot.inc.php');
