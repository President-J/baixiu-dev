<?php
require_once '../../functions.php';
$page = empty($_GET['page'])?1:intval($_GET['page']);

$length = 11;
$offset = ($page-1)*$length;
$sql = sprintf('select 
comments.*,
posts.title as post_title
 from comments
 inner join posts on comments.post_id = posts.id
 order by comments.created desc 
 limit %d, %d;',$offset, $length);


//将数据转为json字符串,decode,将字符串转为数据
$comments =xiu_fetch_all($sql);
$total_count = xiu_fetch_one('select count(1) as count
from comments
inner join posts on comments.post_id = posts.id')['count'];
$total_pages=ceil($total_count/$length);
  $json = json_encode(array( 
    'total_pages'=>$total_pages,
  'comments'=> $comments)
);
  

//设置响应体是json格式.jquery会自动转换
  header('Content-Type: application/json');
 echo $json;
?>