<?php
/**
 * @copyright Copyright(c) 2014 http://www.webzhu.com
 * @breif 
 * @author Tiny
 * @date 2014-12-16
 * @version 0.6
 */
/**
 * Weixin 第三方登录Oauth
 * 
 * @author Tiny
 * @package WeixinOAuth
 */
class WeixinOAuth extends OAuth{
    
     /**
      * requestCodeURL 地址
      * 
      * @access protected
      * @var string
      */
    protected $requestCodeURL = 'https://open.weixin.qq.com/connect/qrconnect';
    
     /**
      * access_token的URL地址
      * 
      * @access protected
      * @var string
      */
    protected $accessTokenURL = 'https://api.weixin.qq.com/cgi-bin/token';
    
     /**
      * request_code的额外参数,URL查询字符串格式
      * 
      * @access protected
      * @var string
      */
    protected $authorize = 'scope=snsapi_login';
    
     /**
      * API根路径URL
      * 
      * @access protected
      * @var string
      */
    protected $apiBase = 'https://api.weixin.qq.com/';
    
    public function getRequestCodeURL(){
        //Oauth 标准参数
        $params = array(
            'appid'     => $this->appKey,
            'redirect_uri'  => $this->callBack,
            'response_type' => $this->responseType,
        );
        
        //获取额外参数
        if($this->authorize){
            parse_str($this->authorize, $_param);
            if(is_array($_param)){
                $params = array_merge($params, $_param);
            } else {
                throw new Exception('authorize配置不正确！');
            }
        }
        return $this->requestCodeURL . '?' . http_build_query($params);
    }
    /**
      * 组装接口调用参数 并调用接口
      * 
      * @access protected
      * @param mixed $api 第三方开方的API
      * @param string $param  请求参数
      * @param string $method 请求的方式 get/post
      * @param bool $multi 
      * @return mixed
      */
    public function call($api, $param = '', $method = 'GET', $multi = false){
        /* 腾讯QQ调用公共参数 */
        $params = array(
            'oauth_consumer_key' => $this->appKey,
            'access_token'       => $this->token['access_token'],
            'openid'             => $this->openid(),
            'format'             => 'json'
        );
        
        $data = $this->http($this->url($api), $this->param($params, $param), $method);
        return json_decode($data, true);
    }
    
    /**
      * 解析access_token方法请求后的返回值
      * 
      * @access protected
      * @param mixed $result token返回值
      * @param mixed $extend 扩展参数
      * @return array
      */
    protected function parsetoken($result, $extend){
        parse_str($result, $data);
        if($data['access_token'] && $data['expires_in']){
            $this->token    = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new Exception("获取腾讯QQ ACCESS_token 出错：{$result}");
    }
    
    /**
      *  用户的openID
      * 
      * @access public
      * @return string
      */
    public function openid(){
        $data = $this->token;
        if(isset($data['openid']))
            return $data['openid'];
        elseif($data['access_token']){
            $data = $this->http($this->url('oauth2.0/me'), array('access_token' => $data['access_token']));
            $data = json_decode(trim(substr($data, 9), " );\n"), true);
            if(isset($data['openid']))
                return $data['openid'];
            else
                throw new Exception("获取用户openid出错：{$data['error_description']}");
        } else {
            throw new Exception('没有获取到openid！');
        }
    }
    /**
     * 获取用户信息
     * 
     * @access public
     * @return array
     */
    public function getUserInfo(){
        
        $data = $this->call('/sns/userinfo');
        $userInfo =  array();
        if(!isset($data['ret']) || $data['ret'] == 0){
            $userInfo['type'] = 'Weixin';
            $userInfo['name'] = $data['nickname'];
            $userInfo['open_name'] = $data['nickname'];
            $userInfo['head'] = $data['figureurl_2'];
            return $userInfo;
        } else {
            throw_exception("获取微信用户信息失败：{$data['msg']}");
        }
    }
}