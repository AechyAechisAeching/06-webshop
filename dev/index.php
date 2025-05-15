<?php

@include_once(__DIR__.'/src/Helpers/Auth.php');
@include_once(__DIR__.'/src/Helpers/Message.php');
@include_once(__DIR__.'/src/Database/Database.php');

setLastVisitedPage();

@include_once(__DIR__ . '/template/head.inc.php');

Database::query("SELECT * FROM `categories`");
$categories = Database::getAll();
$selectedCategories = $_GET['categories'] ?? [];
$itemsPerPage = 6;
$currentPage = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;
$whereClause = "";
$params = [];

if (!empty($selectedCategories)) {
   $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
   $whereClause = "WHERE `category` IN ($placeholders)";
   $params = $selectedCategories;
}

Database::query("SELECT COUNT(*) AS total FROM `products` $whereClause", $params);
$totalProducts = Database::get()->total;
$totalPages = ceil($totalProducts / $itemsPerPage);

Database::query("SELECT * FROM `products` $whereClause LIMIT $itemsPerPage OFFSET $offset", $params);
$products = Database::getAll();
?>

      <!-- -- Css Styles -- -->
       <style>

.product-image {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 0.75rem 0.75rem 0 0;
}


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
  border-bottom: 2px solid rgb(0, 42, 90);
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

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 30px;
  padding: 2px 0;
}

.pagination-container {
   text-align: center;
   margin-top: 15px;
}

.pagination-button {
   margin: 0 5px;
   padding: 8px 25px;
   border-radius: 0.5rem;
   text-decoration: none;
   transition: background-color 0.3s, color 0.3s;
}

.pagination-button:hover {
   background-color:rgb(115, 155, 255);
   color:rgb(255, 255, 255);
   font-weight: bold;
   border: 2px solid black;
   
}

.active-page {
   background-color: #1e87f0;
   color: white;
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

         <?php
         // Manual category options
         $manualCategories = ['hoodies', 'pants', 'accessoires', 'shoes', 'tshirts'];
         foreach ($manualCategories as $manual) {
         ?>
            <div>
               <input class="uk-checkbox" id="<?= $manual ?>" type="checkbox" name="categories[]" value="<?= $manual ?>"
                  onchange="document.getElementById('categoryForm').submit()" 
                  <?= in_array($manual, $selectedCategories ?? []) ? 'checked' : '' ?> />
               <label for="<?= $manual ?>"><?= ucfirst($manual) ?></label>
            </div>
         <?php } ?>
      </form>
   </section>

   <section class="uk-width-5-8">
      <h4 style="padding: 5px;" class="uk-text-muted uk-text-small">
         Gekozen categorieën: 
         <?= !empty($selectedCategories) ? implode(', ', array_map('ucfirst', $selectedCategories)) : 'Alle' ?>
      </h4>

      <div class="product-grid">
         <?php foreach ($products as $product): ?>
            <a class="product-card uk-card uk-card-home uk-card-default uk-card-small" href="product.php?product_id=<?= $product->productID ?>">
               <div class="uk-card-media-top uk-align-center">
                  <img src="data:image/jpeg;base64,<?= base64_encode($product->image) ?>" alt="Product image" class="product-image">
               </div>
               <div class="uk-card-body uk-card-body-home">
                  <h4 class="product-card-title"><?= htmlspecialchars($product->productname) ?></h4>
                  <p class="product-card-p"><?= substr($product->description, 0, 89) . '...' ?></p>
                  <p class="product-card-p product-price">&euro; <?= number_format($product->price, 2, ',', '') ?></p>
               </div>
            </a>
         <?php endforeach; ?>
      </div>

    <!-- Pagination Controls -->
<div class="pagination-container">
   <?php
   $queryStringBase = '';
   foreach ($selectedCategories as $cat) {
      $queryStringBase .= 'categories[]=' . urlencode($cat) . '&';
   }

   if ($currentPage > 1) {
      echo '<a href="?' . $queryStringBase . 'page=' . ($currentPage - 1) . '" class="pagination-button uk-button uk-button-default">← Vorige</a>';
   }

   for ($i = 1; $i <= $totalPages; $i++) {
      $active = $i === $currentPage ? 'uk-button-primary active-page' : 'uk-button-default';
      echo '<a href="?' . $queryStringBase . 'page=' . $i . '" class="pagination-button uk-button ' . $active . '">' . $i . '</a>';
   }

   if ($currentPage < $totalPages) {
      echo '<a href="?' . $queryStringBase . 'page=' . ($currentPage + 1) . '" class="pagination-button uk-button uk-button-default">Volgende</a>';
   }
   ?>
</div>

<?php include_once(__DIR__ . '/template/foot.inc.php'); ?>