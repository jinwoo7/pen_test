<?php

  // Will perform all actions necessary to log in the user
  // Also protects user from session fixation.
  function log_in_user($user) {
    session_regenerate_id();
    // Store user's ID in session
    $_SESSION['user_id'] = $user['id'];
    // saving time stamp
    $_SESSION['last_login'] = time();
    // renewing session
    $_SESSION['logged_in'] = true;
    // saving user's browser information
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    return true;
  }

  function after_successful_login() {
    session_regenerate_id();
    // saving time stamp
    $_SESSION['last_login'] = time();
    // renewing session
    $_SESSION['logged_in'] = true;
    // saving user's browser information
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
  }

  // A one-step function to destroy the current session
  function destroy_current_session() {
    // TODO destroy the session file completely
    session_unset();
    session_destroy();
  }

  // Performs all actions necessary to log out a user
  function log_out_user() {
    unset($_SESSION['user_id']);
    destroy_current_session();
    return true;
  }

  // Determines if the request should be considered a "recent"
  // request by comparing it to the user's last login time.
  function last_login_is_recent() {
    // time difference in minutes
    $recent_limit = 60 * 60 * 24 * 1; // 1 day

    // check if user logged in in the past
    if(!isset($_SESSION['last_login'])) {
      return false; 
    }

    // checking to see if the session is expired
    return (($_SESSION['last_login'] + $recent_limit) >= time());
  }

  // Checks to see if the user-agent string of the current request
  // matches the user-agent string used when the user last logged in.
  function user_agent_matches_session() {
    if(!isset($_SESSION['user_agent'])) { return false; }
    if(!isset($_SERVER['HTTP_USER_AGENT'])) { return false; }
    return ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']);
  }

  // Inspects the session to see if it should be considered valid.
  function session_is_valid() {
    if(!last_login_is_recent()) { return false; }
    if(!user_agent_matches_session()) { return false; }
    return true;
  }

  // is_logged_in() contains all the logic for determining if a
  // request should be considered a "logged in" request or not.
  // It is the core of require_login() but it can also be called
  // on its own in other contexts (e.g. display one link if a user
  // is logged in and display another link if they are not)
  function is_logged_in() {
    // Having a user_id in the session serves a dual-purpose:
    // - Its presence indicates the user is logged in.
    // - Its value tells which user for looking up their record.

    //echo "<h1>-- IN IS_LOGGED_IN --, </h1>";
    if(!isset($_SESSION['user_id'])) { return false; }
    //echo "<h1>-- found session --, </h1>";
    if(!session_is_valid()) { return false; }
    //echo "<h1>-- session is valid --, </h1>";
    return true;
  }

  // Call require_login() at the top of any page which needs to
  // require a valid login before granting acccess to the page.
  function require_login() {
    if(!is_logged_in()) {
      destroy_current_session();
      redirect_to(url_for('/staff/login.php'));
    } else {
      // Do nothing, let the rest of the page proceed
    }
  }

?>
