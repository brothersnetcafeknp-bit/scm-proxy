<?php
// fetch.php
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

$id = $_POST['id'] ?? '';
$month = $_POST['month'] ?? '07';

if (!$id) {
    echo json_encode(['rows'=>[]]);
    exit;
}

$url = "https://scm.up.gov.in/Food/EposAutomation/EPOSRC_Search.aspx/BindTransactionSearchDetails";
$data = [
  "ID"=>$id,
  "Flag"=>"CD",
  "District"=>164,
  "Month"=>$month,
  "Year"=>"2025-26",
  "Cycle"=>"1",
  "Area"=>"U",
  "UserId"=>"792334"
];

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => ["Content-Type: application/json; charset=UTF-8","Accept: application/json, text/javascript, */*; q=0.01"],
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
  echo json_encode(['rows'=>[],'error'=>$error]);
  exit;
}

$json = json_decode($response, true);
$inner = json_decode($json['d'] ?? '[]', true);
$rows = [];

if (is_array($inner)) {
  foreach ($inner as $r) {
    if (isset($r['Commodity']) && trim($r['Commodity']) === 'गेहूँ') {
      // Normalize keys and numeric qty
      if (isset($r['quantity_lifted'])) {
        // ensure numeric
        $r['quantity_lifted'] = str_replace(',', '', $r['quantity_lifted']);
        $r['quantity_lifted'] = is_numeric($r['quantity_lifted']) ? floatval($r['quantity_lifted']) : $r['quantity_lifted'];
      }
      $rows[] = $r;
    }
  }
}

echo json_encode(['rows'=>array_values($rows)]);
