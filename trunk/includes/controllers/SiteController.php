<?php
class SiteController extends BaseController
{
    protected   $_model;
    protected   $_verifyCode;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }
    //展示首页
    public function actionIndex()
    {
        
    }
    //安装程序
    public function actionInstall()
    {
        
    }
    //显示验证码
    public function actionCaptcha()
    {
        
    }
}