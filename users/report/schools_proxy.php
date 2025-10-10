<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$q   = isset($_GET['q']) ? trim($_GET['q']) : '';
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

if ($q === '' || mb_strlen($q) < 2) {
  echo json_encode([]); exit;
}

$params = [
  'q'               => $q,
  'format'          => 'json',
  'addressdetails'  => 1,
  'limit'           => 10,
  'countrycodes'    => 'id',
  'accept-language' => 'id'
];

if ($lat !== null && $lng !== null) {
  $params['viewbox'] = ($lng - 0.5) . ',' . ($lat + 0.5) . ',' . ($lng + 0.5) . ',' . ($lat - 0.5);
  $params['bounded'] = 1;
}

$url = 'https://nominatim.openstreetmap.org/search?' . http_build_query($params);

$opts = [
  'http' => [
    'method'  => 'GET',
    'header'  => [
      'User-Agent: DidikaraSearch/1.0 (+https://didikara.com; contact@didikara.com)'
    ],
    'timeout' => 10
  ]
];
$ctx = stream_context_create($opts);
$res = @file_get_contents($url, false, $ctx);
if ($res === false) { echo json_encode([]); exit; }

$data = json_decode($res, true);
if (!is_array($data)) { echo json_encode([]); exit; }

$pick = function(array $src = null, array $keys = []) {
  if (!$src) return '';
  foreach ($keys as $k) {
    if (isset($src[$k]) && trim((string)$src[$k]) !== '') {
      return (string)$src[$k];
    }
  }
  return '';
};

$out  = [];
$seen = []; // untuk dedup berdasarkan osm_type+osm_id

foreach ($data as $it) {
  $addr = $it['address'] ?? [];
  $idKey = ($it['osm_type'] ?? '') . ':' . ($it['osm_id'] ?? '');

  if ($idKey && isset($seen[$idKey])) {
    continue; // skip duplikat
  }
  if ($idKey) $seen[$idKey] = true;

  // Name: name -> address.{school|college|university} -> display_name -> "Sekolah"
  $name = $it['name'] ?? '';
  if ($name === '') {
    $name = $pick($addr, ['school','college','university']);
    if ($name === '') $name = $it['display_name'] ?? 'Sekolah';
  }

  // Provinsi: state -> region -> province
  $provinsi = $pick($addr, ['state','region','province']);

  // Kab/Kota: city (Kota), county (Kabupaten), town, municipality (hati-hati: di sebagian kota besar municipality bisa = kecamatan)
  $kab_kota = $pick($addr, ['city','county','town','municipality']);

  // Khusus DKI: jika kab_kota mengandung "Jakarta" dan provinsi kosong, set "DKI Jakarta"
  if ($provinsi === '' && preg_match('/Jakarta/i', $kab_kota)) {
    $provinsi = 'DKI Jakarta';
  }

  // Kecamatan: prioritas kuat ke district / city_district / subdistrict / municipality / borough / ward
  // (Hindari pakai suburb untuk kecamatan karena sering nama komplek/perumahan)
  $kecamatan = $pick($addr, ['district','city_district','subdistrict','municipality','borough','ward']);

  // Kelurahan/Desa (opsional, berguna saat kecamatan kosong)
  $kelurahan = $pick($addr, ['village','suburb','neighbourhood','quarter']);

  // Kode pos
  $kode_pos  = $pick($addr, ['postcode']);

  $out[] = [
    'name'       => $name,
    'address'    => $it['display_name'] ?? '',
    'lat'        => isset($it['lat']) ? (float)$it['lat'] : null,
    'lng'        => isset($it['lon']) ? (float)$it['lon'] : null,
    'provinsi'   => $provinsi,
    'kab_kota'   => $kab_kota,
    'kecamatan'  => $kecamatan,
    'kelurahan'  => $kelurahan,   // <— tambahan opsional
    'kode_pos'   => $kode_pos,
    // (opsional untuk debugging/dedup)
    // 'osm_type' => $it['osm_type'] ?? null,
    // 'osm_id'   => $it['osm_id'] ?? null,
  ];
}

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
