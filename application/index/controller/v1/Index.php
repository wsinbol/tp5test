<?php
/*
 * @Description: 
 * @Date: 2019-11-07 11:45:55
 * @Author: Wong Symbol
 * @LastEditors: Wong Symbol
 * @LastEditTime: 2019-11-08 09:57:52
 */


namespace app\index\controller\v1;
use think\Controller;
use think\Route;

class Index extends Controller{
    public function index(){
        return 'v1 hello';
    }

    public function read(){
        return 'v1 read';
    }
}