<html>
<head>
<link href='https://fonts.googleapis.com/css?family=Quicksand:400,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="style.css">

<?php 
if (isset($_GET['cat'])) {
    $cat_filter = $_GET['cat'];
}else{
    $cat_filter = "none";  
}

$categories = ['woodwork', 'shape', 'electronics', 'software'];
$categories_links = ['woodwork' => 'Woodwork', 'shape'=>'Surfboard Shaping', 'electronics'=>'Electronics', 'software'=>'Software'];
echo "<div class='header'>";
echo "<div class='logo'>";
echo "<div class='title font9'><a href='/blog/minimalist-blog/'>ROAST</a></div>";
echo "<div class='subtitle font3'><a href='/blog/minimalist-blog/'>RO & SOME TOOLS</a></div>";
echo "</div>";
foreach ($categories as $cat){
    echo "<div class='header-link'><a href='?cat=".$cat."'>".$categories_links[$cat]."</a></div>";
}
echo "</div>";
?>
</head>
<body>
<?php
$directory = './articles/';
$posts = array_diff(scandir($directory), array('..', '.', '~'));
rsort($posts);
foreach ($posts as $p){
    if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
        $post = json_decode(file_get_contents($directory.$p), true);
        if ($cat_filter == 'none'){
            echo $post['title'];
            echo '<br>';
            echo $post['id'];
            echo "<br><img width=100px src='".$post['thumb']."'><br>";
            echo $post['article'] ;
            echo '<br>';
        }
        else{
            if ($post['category'] == $cat_filter){
                echo $post['title'];
                echo "<br><img width=100px src='".$post['thumb']."'><br>";
                echo $post['article'] ;
            }
        }
    }
}
?>
</body>
</html>
