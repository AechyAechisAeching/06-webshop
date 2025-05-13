
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
/* Product Image */
.product-image {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 0.75rem 0.75rem 0 0;
}

/* Filters */
.category-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 2rem;
  align-items: center;
  justify-content: center;
}

.category-filters input[type="checkbox"] {
  display: none;
}

.category-filters label {
  padding: 10px 18px;
  border: 2px solid #d0d0d0;
  border-radius: 50px;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(8px);
  color: #333;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease-in-out;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.category-filters input[type="checkbox"]:checked + label {
  background: linear-gradient(to right, #0076fd, #3b82f6);
  color: white;
  border-color: #0076fd;
}

.category-filters label:hover {
  background-color: rgba(225, 230, 255, 0.9);
  transform: scale(1.05);
  color: #000;
}

/* Product Card */
.product-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: 1rem;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(200, 200, 200, 0.3);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-decoration: none;
  width: 400px;
  
}

.product-card:hover {
  transform: translateY(-5px) scale(1.03);
}

.uk-card-body-home {
  padding: 1.2rem;
  text-align: center;
}

.product-card-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 0.6rem;
  border-bottom: 2px solid #0076fd;
  margin-left: 65px;
  padding-bottom: 4px;
}

.product-card-p {
  margin: 6px 0;
  margin-left: 50px;
  font-size: 0.95rem;
  color: #333;

}

.product-price {
  font-size: 23.2px;
  font-weight: 700;
  color: #ef4444;
  margin-top: 12px;
}

/* Product Grid */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 30px;
  padding: 2px 0;
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
    <?php 
      $formattedPrice = number_format($product->price, 2, ',', '');
    ?>
    <p class="product-card-p product-price">&euro; <?= $formattedPrice ?></p>
</div>


                
               

                  <!-- EINDE PRODUCT KAART 1 -->
               <?php endforeach; ?>
            </div>
         </section>
      </div>

<?php
include_once(__DIR__ . '/template/foot.inc.php');
