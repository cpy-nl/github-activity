<?php

$pageTitle = 'Activity';

include_once 'includes/time_elapsed_string.php';

// get json using curl

$userName = ''; // enter your Github username
if ($userName) {
  $url = 'https://api.github.com/users/' . $userName . '/repos'; // api endpoint for users repos
} else {
  echo 'Please set a username';
  exit;
}

$userAgent=$_SERVER['HTTP_USER_AGENT']; // sets user agent as required by Github api

// set curl handle
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_TIMEOUT, '3');
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);

$repos = json_decode($result);

echo '<h2>' . $pageTitle . '</h2>';

// using the object to fetch and display various data

if (is_object($repos[0])) {
  foreach ($repos as $repo) {
    echo '<div class="card" style="width: 28rem;">';
    echo '<div class="row">';
    echo '<div class="col-sm-3 col-content-center col-left">';
    echo '<i class="fab fa-github"></i>';
    echo '</div>';
    echo '<div class="col-sm-9 col-right">';
    echo '<a href="' . $repo->html_url . '" target="_blank"><h5 class="card-title">' . $repo->name . '</h5></a>';
    echo '<p class="card-text">' . $repo->description . '</p>';
    echo '<div class="d-flex w-100 justify-content-between">';
    echo '<small><i class="fas fa-circle';
    switch ($repo->language) {
      case 'PHP': echo ' php'; break;
      case 'CSS': echo ' css'; break;
      case 'Vim script': echo ' vim'; break;
      case 'JavaScript': echo ' javascript'; break;
      default: echo ' default'; break;
    }
    echo '"></i> ';
    echo $repo->language . '</small>';
    echo '<small class="badge badge-secondary">Updated: ' . time_elapsed_string(substr($repo->updated_at,0,10)) . '</small>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
} else {
  echo 'No Github activity detected';
}

?>
