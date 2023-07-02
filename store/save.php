<?php
/*
 * サーバにデータを保存するREST API
 *
 * endpoint: /api/store/save.php
 * method: POST
 * request:
 *  id:   G999G9999
 *  data: 保存するデータ
 * response:
 *   {
 *     "result": true
 *   }
 */

//-----------------------------------------------
// ライブラリ
//-----------------------------------------------
require_once('lib.php');

//-----------------------------------------------
// リクエストパラメータの取得
//-----------------------------------------------
$id   = empty($_POST['id'])?   null : $_POST['id'];
$data = empty($_POST['data'])? null : $_POST['data'];

//-----------------------------------------------
// validation
//-----------------------------------------------
// $id
if ( $id === null || !preg_match('/^G[0-9]{3}G[0-9]{4}$/', $id) ){
  sendResult(false, 'invalid id'.$id);
  exit;
}
// $idが G999G9999 の場合はREMOTE_HOSTをチェック
if ( $id === 'G999G9999' ){
  $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  if( !preg_match('/\.vectant\.ne\.jp$/', $hostname) ){
    sendResult(false, 'invalid remote host '.$hostname);
    exit;
  }
}
// $data
if ( $data === null || strlen($data) > DATA_MAX ){
  sendResult(false, 'invalid data');
  exit;
}

//-----------------------------------------------
// データを保存する
//-----------------------------------------------
$result = saveData($id, $data);
if($result === true){
  sendResult(true);
}
else{
  sendResult(false, 'save data error');
}


/**
 * データを保存する
 *
 * @param string $id
 * @param string $data
 * @return boolean
 */
function saveData(string $id, string $data){
  // 一時領域のファイル名を生成
  $tmpfile = createDataFilePath($id);

  // ファイルを開く
  $fp = fopen($tmpfile, 'a');
  if ( $fp === false ){
    return false;
  }

  // ファイルに書き込み
  flock($fp, LOCK_EX);  // ロックをかける
  ftruncate($fp, 0);    // ファイルを0バイトに
  fseek($fp, 0);        // ファイルポインタを先頭に戻す
  fwrite($fp, $data);    // ファイルに書き込み
  flock($fp, LOCK_UN);  // ロック解除
  fclose($fp);

  return(true);
}
