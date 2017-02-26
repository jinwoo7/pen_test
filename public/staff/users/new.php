<?php
  require_once('../../../private/initialize.php');
  if(!is_logged_in()) {
    require_login();
  }

  // Set default values for all variables the page needs.
  $errors = array();
  $user = array(
    'id' => null,
    'first_name' => '',
    'last_name' => '',
    'username' => '',
    'email' => ''
);

if(is_post_request()) {
  // check for valid token and for it's validitiy
  if (!csrf_token_is_valid() || !csrf_token_is_recent()) {
    $errors[] = "Error: invalid request detected";
  }

  // Confirm that values are present before accessing them.
  if(isset($_POST['first_name'])) { $user['first_name'] = sql_clean (h($_POST['first_name'] )); }
  if(isset($_POST['last_name']))  { $user['last_name']  = sql_clean (h($_POST['last_name']  )); }
  if(isset($_POST['username']))   { $user['username']   = sql_clean (h($_POST['username']   )); }
  if(isset($_POST['email']))      { $user['email']      = sql_clean (h($_POST['email']      )); }

  $result = insert_user($user);
  if($result === true) {
    $new_id = db_insert_id($db);
    redirect_to('show.php?id=' . $new_id);
  } else {
    array_merge($errors, $result);
  }
}
?>
<?php $page_title = 'Staff: New User'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Users List</a><br />

  <h1>New User</h1>

  <?php echo display_errors($errors); ?>

  <form action="new.php" method="post">
    First name:<br />
    <input type="text" name="first_name" value="<?php echo h($user['first_name']); ?>" /><br />
    Last name:<br />
    <input type="text" name="last_name" value="<?php echo h($user['last_name']); ?>" /><br />
    Username:<br />
    <input type="text" name="username" value="<?php echo h($user['username']); ?>" /><br />
    Email:<br />
    <input type="text" name="email" value="<?php echo h($user['email']); ?>" /><br />
    <br />
    <?php echo csrf_token_tag(); ?>
    <input type="submit" name="submit" value="Create"  />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
