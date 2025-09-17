<?php
require_once __DIR__ . '/../../admin/classes/Category.php';
$catObj = new Category();
$categories = $catObj->getCategoriesWithSubcategories();
?>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #1a1a1a;">
  <a class="navbar-brand" href="index.php">Freelancer Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" 
          data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
          aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="your_proposals.php">Your Proposals</a></li>
      <li class="nav-item"><a class="nav-link" href="offers_from_clients.php">Offers From Clients</a></li>

      <!-- Categories Dropdown -->
      <?php foreach ($categories as $cat): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown<?= $cat['id'] ?>" 
             role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php if (!empty($cat['subcategories'])): ?>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown<?= $cat['id'] ?>">
              <?php foreach ($cat['subcategories'] as $sub): ?>
                <a class="dropdown-item" href="browse.php?subcategory_id=<?= $sub['id'] ?>">
                  <?= htmlspecialchars($sub['name']) ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a></li>
    </ul>
  </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
