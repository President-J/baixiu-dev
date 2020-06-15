<?php
require_once '../config.php';
//给用户找个箱子
session_start();
function login(){
if(empty($_POST['email'])){
  $GLOBALS['message'] ='请填写邮箱';
  return;
}
if(empty($_POST['password'])){
  $GLOBALS['message'] ='请填写密码';
  return;
}
$email =$_POST['email'];
$password =$_POST['password'];
//对客户端提交过来的表单信息进行校验
$conn =mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
if(!$conn){
  exit('<h1>连接数据库失败</h1>');

}
$query = mysqli_query($conn,"select*from users where email = '{$email}'limit 1;");
if(!$query){
  $GLOBALS['message'] = '登录失败，请重试';
}
//获取登录用户
$user = mysqli_fetch_assoc($query);
if(!$user){
  $GLOBALS['message']='邮箱与密码不匹配';
  return;
}
//一般密码是加密储存的
if($user['password']!=md5($password)){
  //
  $GLOBALS['message']='邮箱与密码不匹配';

}
//为了后续可以直接获取当前登录用户的信息，这里直接将用户信息放到session中
$_SESSION['current_login_user'] = $user;
//一切OK，跳转
header('Location:/admin/');
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  login();
}
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <!-- 可以通过在 form 上添加 novalidate 取消浏览器自带的校验功能 -->
    <!-- autocomplete="off" 关闭客户端的自动完成功能 -->
    <!--通过shake animated控制表单移动  -->
    <form class="login-wrap<?php echo isset($message) ? ' shake animated' : '' ?>" 
    action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"  autocomplete="off" novalidate>
      <img class="avatar" src="/static/assets/img/default.png">
     
      <!-- 有错误信息时展示 -->
     <?php if(isset($message)):?>
     <div class="alert alert-danger">
<strong>错误!</strong><?php echo $message ; ?>
     </div>
     <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <!-- 表单的状态保持，当密码错误时，应该讲用户名保存下来 -->
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱"
         autofocus value="<?php echo empty($_POST['email']) ? '' : $_POST['email'] ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
</body>
</html>
