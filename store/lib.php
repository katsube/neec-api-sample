<?php
/*
 * ライブラリ
 *
 */

//-----------------------------------------------
// 定数
//-----------------------------------------------
define('DATA_MAX', 256); // 保存できるデータの最大byte数


/**
 * データファイルのパスを生成する
 *
 * @param string $id
 * @return string
 */
function createDataFilePath(string $id){
  return sprintf('%s/api_store_%s.dat', sys_get_temp_dir(), $id);
}

/**
 * 処理結果を返す
 *
 * @param boolean $status
 * @param string $data
 * @return void
 */
function sendResult(bool $status, string $data=null){
  $result = [
    'status' => $status,
  ];

  // エラー時はエラーメッセージとして返す
  if($status === false){
    $result['error'] = $data;
  }
  // 正常時はデータとして返す
  else if($data !== null){
    $result['data'] = $data;
  }

  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo json_encode($result);
}