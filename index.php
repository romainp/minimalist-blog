<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Quicksand:400,700,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Rajdhani' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="style.css">

<?php 
if (isset($_GET['cat'])) {
    $cat_filter = $_GET['cat'];
}else{
    $cat_filter = "none";  
}
if (isset($_GET['post'])) {
    $post_filter = $_GET['post'];
}else{
    $post_filter = "none";  
}

$categories = ['woodwork', 'shape', 'electronics', 'software'];
$categories_links = ['woodwork' => 'Woodwork', 'shape'=>'Surfboard Shaping', 'electronics'=>'Electronics', 'software'=>'Software'];
echo "<div id='header' class='header'>";
echo "<div id='logo' class='logo'>";
echo "<div id='title' class='title font9'><a href='/blog/minimalist-blog/'>ROAST</a></div>";
echo "<div id='subtitle' class='subtitle'><a href='/blog/minimalist-blog/'>RO & SOME TOOLS</a></div>";
echo "</div>";
echo "<div id='header-links' class='header-links'>";
echo "<div class='top-link'><a href='.'>Home</a></div>";
foreach ($categories as $cat){
        if ($cat_filter == $cat){
            echo "<div class='top-link italic'><a href='?cat=".$cat."'>".$categories_links[$cat]."</a></div>";
        }
        else{
            echo "<div class='top-link'><a href='?cat=".$cat."'>".$categories_links[$cat]."</a></div>";
        }
}
echo "</div>";
echo "</div>";
?>
</head>
<body>
<?php
$first_post = "yes";
$directory = './articles/';
$posts = array_diff(scandir($directory), array('..', '.', '~'));
rsort($posts);
$posts_number = count($posts);
$close = "no";

$p=$posts[0];
if (($cat_filter == 'none') && ($post_filter == 'none')) {
    if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
        $post = json_decode(file_get_contents($directory.$p), true);

        echo "<div class = 'first_post'>";
        
        echo "<img src='".$post['thumb']."'><br>";
        echo "<div class = 'category'>";
        echo "Latest Post: ";
        echo "<a href='?cat=".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
        echo "<div class='first_post_card'>";
        echo "<div class = 'title font4'>".$post['title']."</div>";
        //echo "<div class = 'title'>".$post['id']."</div>";
        echo "<div class = 'abstract'>".$post['abstract']."</div>";
        echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
        echo '</div>';
       // echo "<div class = 'article'>".$post['article']."</div>" ;
        echo '</div>';
        echo "<div class='void'></div>";

        }
    }
$empty = 0;

if ($post_filter == 'none') {
    for ($i=0;$i<3;$i++){

            echo "<div class='column".$i."'>"; 

         
        for($j=0;3*$j+$i<$posts_number;$j++){
            $p=$posts[3*$j+$i+1];

            if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
                $post = json_decode(file_get_contents($directory.$p), true);
                if ($cat_filter == 'none'){
                    $empty = 1;
                    echo "<div class = 'post'>";
                    echo "<a href='?post=".$post['id']."'>";
                    echo "<img src='".$post['thumb']."'><br></a>";
                    echo "<div class = 'category'>";
                 
                    echo "<a href='?cat=".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
                    echo "<a href='?post=".$post['id']."'>";
                    echo "<div class = 'title font4'>".$post['title']."</div>";
                    echo "<div class = 'abstract'>".$post['abstract']."</div>";
                    echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";


                   // echo "<div class = 'article'>".$post['article']."</div>" ;
                    echo '</a></div>';
                    echo "<div class='void'></div>";
                }
                else{
                    if ($post['category'] == $cat_filter){
                        $empty = 1;
                        echo "<div class = 'post'>";
                        echo "<img src='".$post['thumb']."'><br>";
                        echo "<div class = 'category'>";
                        echo "<a href='?cat=".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
                        echo "<div class = 'title font4'>".$post['title']."</div>";
                        echo "<div class = 'abstract'>".$post['abstract']."</div>";
                        echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
                        // echo "<div class = 'article'>".$post['article']."</div>" ;
                        echo '</div>';
                        echo "<div class='void'></div>";
                    }
                }
            }
        }
        echo "</div>";
    }
}
else{
    $post = json_decode(file_get_contents($directory.$post_filter.'.json'), true);
    $empty = 1;
    echo "<div class = 'post'>";
    
    echo "<img src='".$post['thumb']."'><br>";
    echo "<div class = 'category'>";
 
    echo "<a href='?cat=".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
    echo "<div class = 'title font4'>".$post['title']."</div>";
    echo "<div class = 'abstract'>".$post['abstract']."</div>";
    echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";


   // echo "<div class = 'article'>".$post['article']."</div>" ;
    echo '</div>';
    echo "<div class='void'></div>";
}

if ($empty == 0){
    echo "Looks like I am gonna have to get things moving here!";
}
?>
<script >
    jQuery(document).ready(function() {
        $(window).scroll(function() {     
            var scroll = $(window).scrollTop();
            if (scroll > 0) {
                $("#header").addClass("header-shadow");
                $("#header-links").height("3.75rem");
                $("#title").css("font-size","2rem");
                $("#subtitle").css("font-size","0.67rem");
            }
            else {
                $("#header").removeClass("header-shadow");
                $("#header-links").height("5.8rem");
                $("#title").css("font-size","4rem");
                $("#subtitle").css("font-size","1.355rem");
            }
        });
    });
</script>
</body>
</html>
