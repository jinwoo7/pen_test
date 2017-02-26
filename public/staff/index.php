<?php
  require_once('../../private/initialize.php');
  //echo $_SESSION['user_id'];
  if(!is_logged_in()) {
    //echo "<h1>NOT LOGGED IN, </h1>";
    require_login();
  }
  //echo "<h1>LOGGED IN!</h1>";
?>

<?php $page_title = 'Staff: Menu'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="main-content">

  <h1>Menu</h1>
  <ul>
    <li>
      <a href="users/index.php">Users</a>
    </li>
    <li>
      <a href="salespeople/index.php">Salespeople</a>
    </li>
    <li>
      <a href="countries/index.php">Countries, States, &amp; Territories</a>
    </li>
  </ul>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
