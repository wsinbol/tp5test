<?php
/*
 * @Description: token测试
 * @Date: 2019-10-21 09:30:44
 * @Author: Wong Symbol
 * @LastEditors: Wong Symbol
 * @LastEditTime: 2019-11-18 20:52:43
 */
namespace app\index\controller;

use think\Controller;
use think\Validate;
use think\Session;

class TestToken extends Controller{
    /**
     * 表单渲染页面
     * 渲染的同时生成一个时间戳，并存储在session中
     * 注意前端表单页面的书写方式！
     */
    public function form(){
        $form_verify = mt_rand(0,1000000);
        Session::set('form_verify', $form_verify);
        $this->assign('form_verify', $form_verify);
        return $this->fetch();
    }
    
    /**
     * 用控制器验证法校验数据，表单令牌token用来防止csrf攻击,form_verify用来防止表单重复提交
     * 验证成功后一定要清除session('form_verify')的值，不成功不修改该值
     * token令牌的值可以一直保持更新
     * 最佳的验证方式是采用验证器来实现，同时封装底层的异常处理层
     */
    public function check(){
        if(request()->isPost()){
            $form_data = input();
            $validate = new Validate([
                'user_name' => 'require|min:3',
                'password' => 'require|min:3',
                '__token__' => 'require|token',
                '__form_verify__' => 'formVerify'
            ]);

            $validate->extend('formVerify', function($value){
                return Session::get('form_verify') == $value ? true : '表单来源非法';
            });
            
            // batch 批量验证，返回错误信息的数组
            if(!$validate->batch()->check($form_data)){
            	// 不推荐使用tp5的跳转方式
            	// $this->error($validate->getError(), null, ['token' => $this->request->token()]);
                return ['msg' => $validate->getError(),'token' => request()->token()];
            }else{
                Session::delete('form_verify');
                return ['msg' => 'Success'];
            }
        }
    }
}