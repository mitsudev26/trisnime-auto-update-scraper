#!/usr/bin/php
<?php
/**
 * Cron job script untuk auto update scraper
 * 
 * Cara penggunaan:
 * 1. Buat file ini executable: chmod +x cron_auto_update.php
 * 2. Tambahkan ke crontab: 
 *    0,30 * * * * /usr/bin/php /path/to/your/project/cron_auto_update.php
 * 
 * Atau gunakan:
 *    0,30 * * * * /usr/bin/php /path/to/your/project/index.php admin/scraper/auto_update
 */

// Set path ke direktori project
$project_path = dirname(__FILE__);
chdir($project_path);

// Include CodeIgniter bootstrap
define('ENVIRONMENT', 'production');
require_once 'index.php';

// Atau bisa menggunakan curl untuk memanggil endpoint
/*
$url = 'http://yourdomain.com/admin/scraper/auto_update';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutes timeout
$result = curl_exec($ch);
curl_close($ch);

echo date('Y-m-d H:i:s') . " - Auto update result: " . $result . "\n";
*/
