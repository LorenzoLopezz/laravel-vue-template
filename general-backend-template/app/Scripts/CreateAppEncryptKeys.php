<?php

function generateRandomString($length = 16) {
  return bin2hex(random_bytes($length / 2));
}

$envFile = '.env';
$keys = ['APP_KEY_ENCRYPTION', 'APP_IV_ENCRYPTION'];
$newValues = array_map('generateRandomString', array_fill(0, count($keys), 16));

if (file_exists($envFile)) {
  $envContent = file_get_contents($envFile);

  foreach ($keys as $index => $key) {
    $newValue = $newValues[$index];
    $pattern = "/^$key=.*$/m";
    $replacement = "$key=$newValue";

    if (preg_match($pattern, $envContent)) {
      $envContent = preg_replace($pattern, $replacement, $envContent);
    } else {
      $envContent .= PHP_EOL . $replacement;
    }

    echo "Updated $key in $envFile to $newValue\n";
  }

  file_put_contents($envFile, $envContent);
} else {
  echo "$envFile does not exist.\n";
}
