<?php
class SearchController extends BaseController{
    protected $_db;
    public function  __construct() {
        global $db_url;
        $this->_db=YDB::factory($db_url);
    }
    public function actionIndex(){
        if(! empty($_REQUEST['s'])){
            $s=trim($_REQUEST['s']);
            $data=get_all_data(true, true, false, true);
            $result_array=array();
            foreach($data as $_data){
                if(strpos($_data['user'],$s)!==false || strpos($_data['post_content'], $s)!==false || strpos($_data['reply_content'], $s)!==false || strpos($_data['b_username'], $s)!==false)
                    $result_array[]=$_data;
            }
            if (defined('API_MODE')) {
                $json_array=array('messages'=>$result_array,'nums'=>  count($result_array));
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }
            $nums=count($result_array);
            $this->render('search_result',array(
            'data'=>$result_array,
            'nums'=>$nums,
            ));
        }
        elseif (defined('API_MODE')) {
            $json_array=array('error_msg'=>ZFramework::t('NO_SEARCH_PARAM'));
            die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
        }else{
            header("Location:index.php");
        }
    }
}