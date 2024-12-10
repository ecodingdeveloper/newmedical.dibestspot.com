<?php
  /** @var string $theme */
  /** @var string $module */
  /** @var string $page */
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  $currentUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

  //file_put_contents('url_log.txt', $currentUrl . PHP_EOL, FILE_APPEND);

  $url=htmlspecialchars($currentUrl);

$path = parse_url($url, PHP_URL_PATH);
// echo $path;

$segments = explode('/', trim($path, '/'));

if (isset($segments[0])) {
    $desiredSegment = $segments[0];
} else {
    $desiredSegment = 'Segment not found';
}
// echo $segments[2];
  // echo $theme;
  // echo '<br>';
  // echo $module;
  // echo '<br>';
  // echo $page;
  // echo '<br>';
  //  echo ($theme . '/modules/' . $module . '/' . $page);
  //  echo $this->session->userdata['team_member_id'];
  //  echo '<br>';
  //  echo $desiredSegment;
    // if($desiredSegment=='mywarmembrace')
    // {
    //   // echo "inside";
      
    //   $this->load->view($theme . '/includes/header_mywarmembrace');
    //   $this->load->view($theme . '/modules/' . $module . '/' . $page);
    //   $this->load->view($theme . '/includes/footer_mywarmembrace');
     
    // }
    // else{

    
    $this->load->view($theme . '/includes/header');
    $this->load->view($theme . '/includes/sidebar');
    if($desiredSegment=='mywarmembrace') $this->load->view($theme . '/includes/header_mywarmembrace');
    $this->load->view($theme . '/modules/' . $module . '/' . $page);
    if($desiredSegment=='mywarmembrace') $this->load->view($theme . '/includes/footer_mywarmembrace');
    if($desiredSegment=='mywarmembrace'){
      
        return;
    } 
    else{
      $this->load->view($theme . '/includes/footer');
    }
  
    