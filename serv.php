<?php

/**
 * 乐视登陆请求
 */
class Leshi{
	
	/**
	 * 用户登录
	 * @param  string $username [用户账号]
	 * @param  string $password [密码]
	 * @return [type]           [description]
	 */
	public function login($username='',$password=''){
		
		$devid = md5(rand(10000,999999));
		$sign = md5('8.9.9'.$username.$password.$devid."e3F5gIfT3zj43MAc3F");

		$url = "http://dynamic.user.app.m.letv.com/android/dynamic.php?mod=passport&ctl=index&act=newLogin&pcode=010110000&version=8.9.9&loginname=".$username."&sign=".$sign."&devid=".$devid."&password=".$password;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式  
		curl_setopt($ch, CURLOPT_PROXY, $_SERVER['REMOTE_ADDR']); //代理服务器地址   
		// curl_setopt($ch, CURLOPT_PROXYPORT,80); //代理服务器端口
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts   
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);      
        $res = curl_exec($ch);
        var_dump($res);
        curl_close($ch);
        if ($res['status']) {
        	$this->success("恭喜：登录测试成功，可在官网或APP直接登录了");
        }else{
        	$this->error($res['message']);
        }
	}

	/**
	 * 错误提示
	 * @param  string $msg [提示信息]
	 * @return [type]      [description]
	 */
	public function error($msg=''){
		die('{"status":0,"msg":"'.$msg.'"}');
	}

	/**
	 * 正确提示
	 * @param  string $msg [提示信息]
	 * @return [type]      [description]
	 */
	public function success($msg=''){
		die('{"status":1,"msg":"'.$msg.'"}');
	}
}

$leshi = new Leshi();

!empty($_POST['username']) or $leshi->error("账号不能为空");
!empty($_POST['password']) or $leshi->error("密码不能为空");

$username = $_POST['username'];
$password = $_POST['password'];
$t = $_POST['t'];
$token = $_POST['token'];

if ($token != md5($t."leshi_login")) {
	$leshi->error("请通过小程序‘共享会员账号’进行登录测试");
}

$leshi->login($username,$password);