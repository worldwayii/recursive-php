<?php require('partials/head.php'); ?>

<h3>Search Directory Name </h3>

<form action="/search" method="get">
  <input type="search" name="search" placeholder="Search folder name...">
  <button>Search</button>
</form>

<?php if (isset($_REQUEST['search'])) : ?>
  <?php foreach ($result as $value) { ?>
  <pre><?php echo $value ?></pre>
  <?php } ?>

<?php endif; ?>

<?php require('partials/footer.php'); ?>