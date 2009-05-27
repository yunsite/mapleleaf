<?php
/**
 * 第一步: 初始配置.
 */
define('DRUPAL_BOOTSTRAP_CONFIGURATION', 0);

/**
 * 第二步: 初始化 session 处理器.
 */
define('DRUPAL_BOOTSTRAP_SESSION', 1);

/**
 * 第三步: 载入 bootstrap.inc 和 module.inc, 开始变量系统并尝试从缓存中提供页面.
 */
define('DRUPAL_BOOTSTRAP_LATE_PAGE_CACHE', 2);

/**
 * 第四步: 找出页面语言.
 */
define('DRUPAL_BOOTSTRAP_LANGUAGE', 3);

/**
 * 第五步: 设置 $_GET['q'] .
 */
define('DRUPAL_BOOTSTRAP_PATH', 4);

/**
 * 最后一步: Drupal 被完全载入; 验证并确定 input 数据.
 */
define('DRUPAL_BOOTSTRAP_FULL', 5);

function drupal_bootstrap($phase) {
  static $phases = array(DRUPAL_BOOTSTRAP_CONFIGURATION, DRUPAL_BOOTSTRAP_SESSION, DRUPAL_BOOTSTRAP_LATE_PAGE_CACHE, DRUPAL_BOOTSTRAP_LANGUAGE, DRUPAL_BOOTSTRAP_PATH, DRUPAL_BOOTSTRAP_FULL), $phase_index = 0;
  while ($phase >= $phase_index && isset($phases[$phase_index])) {
    $current_phase = $phases[$phase_index];
    unset($phases[$phase_index++]);
    _drupal_bootstrap($current_phase);
  }
}

function _drupal_bootstrap($phase) {
  global $conf;

  switch ($phase) {

    case DRUPAL_BOOTSTRAP_CONFIGURATION:
      drupal_unset_globals();
      // Initialize the configuration
      conf_init();
      break;

    case DRUPAL_BOOTSTRAP_SESSION:
      require_once variable_get('session_inc', './includes/session.inc');
      session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy_sid', 'sess_gc');
      session_start();
      break;

    case DRUPAL_BOOTSTRAP_LATE_PAGE_CACHE:
      // Initialize configuration variables, using values from settings.php if available.
      $conf = variable_init(isset($conf) ? $conf : array());
      $cache_mode = variable_get('cache', CACHE_DISABLED);
      // Get the page from the cache.
      $cache = $cache_mode == CACHE_DISABLED ? '' : page_get_cache();
      // If the skipping of the bootstrap hooks is not enforced, call hook_boot.
      if (!$cache || $cache_mode != CACHE_AGGRESSIVE) {
        // Load module handling.
        require_once './includes/module.inc';
        bootstrap_invoke_all('boot');
      }
      // If there is a cached page, display it.
      if ($cache) {
        drupal_page_cache_header($cache);
        // If the skipping of the bootstrap hooks is not enforced, call hook_exit.
        if ($cache_mode != CACHE_AGGRESSIVE) {
          bootstrap_invoke_all('exit');
        }
        // We are done.
        exit;
      }
      // Prepare for non-cached page workflow.
      drupal_page_header();
      break;

    case DRUPAL_BOOTSTRAP_LANGUAGE:
      drupal_init_language();
      break;

    case DRUPAL_BOOTSTRAP_PATH:
      require_once './includes/path.inc';
      // Initialize $_GET['q'] prior to loading modules and invoking hook_init().
      drupal_init_path();
      break;

    case DRUPAL_BOOTSTRAP_FULL:
      require_once './includes/common.inc';
      _drupal_bootstrap_full();
      break;
  }
}
/**
 * 反设置所有被禁止的全局变量. See $allowed for what's allowed.
 */
function drupal_unset_globals() {
  if (ini_get('register_globals')) {
    $allowed = array('_ENV' => 1, '_GET' => 1, '_POST' => 1, '_COOKIE' => 1, '_FILES' => 1, '_SERVER' => 1, '_REQUEST' => 1, 'GLOBALS' => 1);
    foreach ($GLOBALS as $key => $value) {
      if (!isset($allowed[$key])) {
        unset($GLOBALS[$key]);
      }
    }
  }
}

function conf_init() {
  global $base_url, $base_path, $base_root;

  // Export the following settings.php variables to the global namespace
  global $cookie_domain, $conf, $installed_profile, $update_free_access;
  $conf = array();

  if (isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
    if (!drupal_valid_http_host($_SERVER['HTTP_HOST'])) {
      // HTTP_HOST is invalid, e.g. if containing slashes it may be an attack.
      header('HTTP/1.1 400 Bad Request');
      exit;
    }
  }
  else {
    $_SERVER['HTTP_HOST'] = '';
  }

  if (file_exists('./'. conf_path() .'/settings.php')) {
    include_once './'. conf_path() .'/settings.php';
  }


  if (isset($base_url)) {
    // Parse fixed base URL from settings.php.
    $parts = parse_url($base_url);
    if (!isset($parts['path'])) {
      $parts['path'] = '';
    }
    $base_path = $parts['path'] .'/';
    // Build $base_root (everything until first slash after "scheme://").
    $base_root = substr($base_url, 0, strlen($base_url) - strlen($parts['path']));
  }
  else {
    // Create base URL
    $base_root = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

    $base_url = $base_root .= '://'. $_SERVER['HTTP_HOST'];

    // $_SERVER['SCRIPT_NAME'] can, in contrast to $_SERVER['PHP_SELF'], not
    // be modified by a visitor.
    if ($dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\,/')) {
      $base_path = "/$dir";
      $base_url .= $base_path;
      $base_path .= '/';
    }
    else {
      $base_path = '/';
    }
  }
}
?>