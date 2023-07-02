<?php
/*
 * サーバに保存したデータを返却するREST API
 *
 * endpoint: /api/store/load.php
 * method: GET
 * request:
 *  id:   G999G9999
 * response:
 *   {
 *     "data": "保存したデータ"
 *   }
 */

//-----------------------------------------------
// ライブラリ
//-----------------------------------------------
require_once('lib.php');

//-----------------------------------------------
// リクエストパラメータの取得
//-----------------------------------------------
$id = empty($_REQUEST['id'])?   null : $_REQUEST['id'];

//-----------------------------------------------
// validation
//-----------------------------------------------
// $id
if ( $id === null || !preg_match('/^G[0-9]{3}G[0-9]{4}$/', $id) ){
  sendResult(false, 'invalid id'.$id);
  exit;
}

//-----------------------------------------------
// データを取得する
//-----------------------------------------------
$result = loadData($id);
if( $result !== false ){
  sendResult(true, $result);
}
else{
  sendResult(false, 'load data error');
}

/**
 * データを取得する
 *
 * @param string $id
 * @return string
 */
function loadData(string $id){
  $path = createDataFilePath($id);

  // ファイルの存在チェック
  if(!file_exists($path)){
    return('');
  }

  // ファイルの読み込み
  $fp = fopen($path, 'r');
  if($fp === false){
    return(false);
  }
  flock($fp, LOCK_SH);
  $data = fread($fp, DATA_MAX);
  flock($fp, LOCK_UN);
  fclose($fp);

  return($data);
}