<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Quicksand:400,700,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Rajdhani' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/blog/minimalist-blog/style.css">
<?php 

$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$args = explode('/', $path);                // Split path on slashes. String starts with /
$args = array_diff($args, ["blog", "minimalist-blog"]);
$categories = ['woodwork', 'shape', 'electronics', 'software'];
$categories_links = ['woodwork' => 'Woodwork', 'shape'=>'Surfboard Shaping', 'electronics'=>'Electronics', 'software'=>'Software'];

echo "<div id='header' class='header'>";
echo "<div id='logo' class='logo'>";
echo "<div id='title' class='title font8'><a href='/blog/minimalist-blog/'>DUST</a></div>";
echo "<div id='subtitle' class='subtitle'><a href='/blog/minimalist-blog/'>O'CLOCK</a></div>";
echo "</div>";
echo "<div id='header-links' class='header-links'>";
echo "<div class='top-link'><a href='.'>Home</a></div>";
foreach ($categories as $cat){
        if ($cat_filter == $cat){
            echo "<div class='top-link italic'><a href='/cat/".$cat."'>".$categories_links[$cat]."</a></div>";
        }
        else{
            echo "<div class='top-link'><a href='/cat/".$cat."'>".$categories_links[$cat]."</a></div>";
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
$posts_list = array_diff(scandir($directory), array('..', '.', '~'));
$posts = [];
foreach($posts_list as $post){
    if (($post[0] != '.') && (substr($post, strlen($post)-5,5) == '.json')){
        array_push($posts, $post);
    }  
}
rsort($posts);
$posts_number = count($posts);
$close = "no";
if(!empty(($args[array_search('cat', $args)]))){
    foreach($categories as &$cat){
        if($args[array_search('cat', $args)+1] == $cat){
            $cat_filter = $cat;
            break;
            }
        $cat_filter = "404";
    }
}
else{
    $cat_filter = "none";
} 

if(!empty(($args[array_search('post', $args)]))){
    foreach($posts as &$post){
        if($args[array_search('post', $args)+1].'.json' == $post){
            $post_filter = $post;
            break;
            }
        $post_filter = "404";
    }
}
else{
    $post_filter = "none";
} 

$search_results = [];
$found = 0;
$search_filter = [];
if(!empty(($args[array_search('search', $args)]))){
        if(!empty($args[array_search('search', $args)+1])){
            $search_filter = explode('-', $args[array_search('search', $args)+1]);   
            foreach ($posts as $post){
            $text = file_get_contents($directory.$post, true);
            foreach ($search_filter as $search_term){
           
                if(strpos(strtolower($text), strtolower($search_term)) === false){
                    break;
                    }
                $found = $found+1;
             }
             if ($found == sizeof($search_filter)){
                array_push($search_results, $post);
             }
             $found = 0;
             }
        }
}
else{
    $search_filter = "none";
} 
?>

<form id="form1" name="form1" method="post" action=""> 
  <input type="text" name="search" id="search" /> <input type="button" name="submit" id="submit" value="Submit">
    </form>
    
    
  <?php  
    
    

$featured_post=$posts[0];
if (($cat_filter=="none") && ($post_filter=="none")) {
    if (pathinfo($featured_post, PATHINFO_EXTENSION)  == 'json'){
        $post = json_decode(file_get_contents($directory.$featured_post), true);
        echo "<div class = 'first_post'>";
        echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
        echo "<img src='".$post['thumb']."'></a>";
        echo "<div class = 'category'>";
        echo "Latest Post: ";
        echo "<a href='/cat/".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
        echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
        echo "<div class='first_post_card'>";
        echo "<div class = 'title font4'>".$post['title']."</div>";
        //echo "<div class = 'title'>".$post['id']."</div>";
        echo "<div class = 'abstract'>".$post['abstract']."</div>";
        echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
        echo '</div>';
       // echo "<div class = 'article'>".$post['article']."</div>" ;
        echo '</a></div>';
        echo "<div class='void'></div>";
        }
    }
$empty = 0;

if ($post_filter=="none") {
    for ($i=0;$i<3;$i++){
        echo "<div class='column".$i."'>"; 
        for($j=0;3*$j+$i<$posts_number;$j++){
            $p=$posts[3*$j+$i+1];

            if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
                $post = json_decode(file_get_contents($directory.$p), true);
                if ($cat_filter=="none"){
                    $empty = 1;
                    echo "<div class = 'post'>";
                    echo "<a href='/post/".$post['id']."/".$post['title']."'>";
                    echo "<img src='".$post['thumb']."'></a>";
                    echo "<div class = 'category'>";
                    echo "<a href='/cat/".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
                    echo "<a href='/post/".$post['id']."/".$post['title']."'>";
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
                        echo "<a href='/cat/".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
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
    $post = json_decode(file_get_contents($directory.$post_filter), true);
    $empty = 1;
    echo "<div class = 'single_post'>";
    
    echo "<img src='".$post['thumb']."'><br>";
    echo "<div class = 'category'>";
 
    echo "<a href='/cat/".$post['category']."'>".$categories_links[$post['category']]."</a></div>";
    echo "<div class = 'title font4'>".$post['title']."</div>";
    echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
    echo "<div class = 'article'>".$post['article']."</div>";
   // echo "<div class = 'article'>".$post['article']."</div>" ;
    echo '</div>';
    echo "<div class='void'></div>";
}
if ($empty == 0){
    echo "<div class='empty_page'>Looks like I am gonna have to get things moving here!</div>";
}

?>
<!--
<div id="disqus_thread"></div>

<script>

/**
 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables */
/*
var disqus_config = function () {
    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = '//dustoclock.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    -->                                
<?php


//FOOTER
echo "<div class='footer'>";
echo "Hey it's me mate! Wanna  <a href='mailto:romain.pitz@gmail.com?Subject=Hello' target='_top'>send me an email?</a>";
echo "</div>";
?>
<script >
    jQuery(document).ready(function() {
        $(window).scroll(function() {     
            var scroll = $(window).scrollTop();
            if (scroll > 0) {
                $("#header").addClass("header-shadow");
                $("#header-links").height("4.2rem");
                $("#title").css("font-size","2rem");
                $("#subtitle").css("font-size","1.2rem");
            }
            else {
                $("#header").removeClass("header-shadow");
                $("#header-links").height("5.8rem");
                $("#title").css("font-size","3.4rem");
                $("#subtitle").css("font-size","2.1rem");
            }
        });
    });
    
    
    function myFunction() {
    document.getElementById("frm1").submit();
}

document.getElementById('submit').onclick = function() {
    var search = document.getElementById('search').value.replace(/ /g,"-");;
    location.href = '/search/' + search;
};

    
</script>
</body>
</html>
