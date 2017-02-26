<?php
require_once('../../../private/initialize.php');
if(!is_logged_in()) {
    require_login();
  }

// Set default values for all variables the page needs.
$errors = array();
$salesperson = array(
  'first_name' => '',
  'last_name' => '',
  'phone' => '',
  'email' => ''
);

if(is_post_request()) {

  // check for valid token and for it's validitiy
  if (!csrf_token_is_valid() || !csrf_token_is_recent()) {
    $errors[] = "Error: invalid request detected";
  }

  // Confirm that values are present before accessing them.
  if(isset($_POST['first_name'])) { $salesperson['first_name'] = sql_clean (h($_POST['first_name'])); }
  if(isset($_POST['last_name'])) { $salesperson['last_name'] = sql_clean (h($_POST['last_name'])); }
  if(isset($_POST['phone'])) { $salesperson['phone'] = sql_clean (h($_POST['phone'])); }
  if(isset($_POST['email'])) { $salesperson['email'] = sql_clean (h($_POST['email'])); }

  $result = insert_salesperson($salesperson);
  if($result === true) {
    $new_id = db_insert_id($db);
    redirect_to('show.php?id=' . $new_id);
  } else {
    array_merge($errors, $result);
  }
}
?>
<?php $page_title = 'Staff: New Salesperson'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Salespeople List</a><br />

  <h1>New Salesperson</h1>

  <?php echo display_errors($errors); ?>

  <form action="new.php" method="post">
    First name:<br />
    <input type="text" name="first_name" value="<?php echo h($salesperson['first_name']); ?>" /><br />
    Last name:<br />
    <input type="text" name="last_name" value="<?php echo h($salesperson['last_name']); ?>" /><br />
    Phone:<br />
    <input type="text" name="phone" value="<?php echo h($salesperson['phone']); ?>" /><br />
    Email:<br />
    <input type="text" name="email" value="<?php echo h($salesperson['email']); ?>" /><br />
    <br />
    <?php echo csrf_token_tag(); ?>
    <input type="submit" name="submit" value="Create"  />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
