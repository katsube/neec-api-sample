<?php
/*
 * 現在時間を返却するAPI
 *
 */
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode([
  'format'   => date('Y-m-d H:i:s'),
  'unixtime' => time()
]);
