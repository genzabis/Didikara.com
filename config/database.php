<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__));
}

$envFile = BASE_PATH . '/.env';
if (is_readable($envFile)) {
  $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if ($line[0] === '#' || !str_contains($line, '=')) continue;
    [$k, $v] = array_map('trim', explode('=', $line, 2));
    $v = trim($v, "\"'");
    if ($k !== '' && !array_key_exists($k, $_ENV)) {
      $_ENV[$k] = $v;
      putenv("$k=$v");
    }
  }
}

$DB = [
  'driver'    => 'mysql',
  'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
  'port'      => (int)($_ENV['DB_PORT'] ?? 3306),
  'name'      => $_ENV['DB_NAME'] ?? 'db_didikara',
  'user'      => $_ENV['DB_USER'] ?? 'root',
  'pass'      => $_ENV['DB_PASS'] ?? '',
  'charset'   => 'utf8mb4',
  'collation' => 'utf8mb4_unicode_ci',
  'timezone'  => '+07:00',
  'persistent'=> false,
];

$dsn = sprintf(
  '%s:host=%s;port=%d;dbname=%s;charset=%s',
  $DB['driver'], $DB['host'], $DB['port'], $DB['name'], $DB['charset']
);

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
  PDO::MYSQL_ATTR_INIT_COMMAND => sprintf(
    "SET NAMES %s COLLATE %s, time_zone = '%s'",
    $DB['charset'], $DB['collation'], $DB['timezone']
  ),
];
if ($DB['persistent']) {
  $options[PDO::ATTR_PERSISTENT] = true;
}

try {
  /** @var PDO $pdo */
  $pdo = new PDO($dsn, $DB['user'], $DB['pass'], $options);
  $pdo->exec("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
} catch (PDOException $e) {
  http_response_code(500);
  error_log('[PDO] ' . $e->getMessage());
  exit('Database connection failed.');
}
