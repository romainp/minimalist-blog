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

$categories = ['woodwork', 'surfboard shaping', 'electronics', 'software'];
echo "<div class='header'>";
echo "<div class='title font5'>RO & SOME TOOLS</div>";
foreach ($categories as $cat){
    echo "<div class='header-link'>".$cat."</div>";
}
echo "</div>";
?>
</head>
<body>
<?php
$di = new RecursiveDirectoryIterator('./articles/');
foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
    if (pathinfo($filename, PATHINFO_EXTENSION)  == 'json'){
        $post = json_decode(file_get_contents($filename), true);
        if ($cat_filter == 'none'){
            echo $post['title'];
            echo "<br><img width=100px src='".$post['thumb']."'><br>";
            echo $post['article'] ;
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
