<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Quicksand:400,700,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Rajdhani' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/blog/minimalist-blog/style.css">
<?php 
$directory = './articles/';
$categories = ['woodwork', 'shape', 'electronics', 'software'];
$categories_links = ['woodwork' => 'Woodwork', 'shape'=>'Surfboard Shaping', 'electronics'=>'Electronics', 'software'=>'Software'];
function display_post_small($posts, $message){
    global $directory;
    global $categories_links;
    if(!empty($message)){
        echo "<div> Results for: <b>".$message."</b></div>";
    }
    for ($i=0;$i<3;$i++){
        echo "<div class='column".$i."'>"; 
        for($j=0;3*$j+$i<count($posts);$j++){
            $p=$posts[3*$j+$i];
            if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
                $post = json_decode(file_get_contents($directory.$p), true);
                echo "<div class = 'post'>";
                echo "<a href='/post/".$post['id']."/".$post['title']."'>";
                echo "<img src='".$post['thumb']."'></a>";
                echo "<div class = 'category'> - ";
                $post_cat = explode(', ', $post['category']);
                foreach($post_cat as $cat){
                    echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
                }
                echo "</div>";
                echo "<a href='/post/".$post['id']."/".$post['title']."'>";
                echo "<div class = 'title font4'>".$post['title']."</div>";
                echo "<div class = 'abstract'>".$post['abstract']."</div>";
                echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
                echo '</a></div>';
                echo "<div class='void'></div>";
            }
        } 
        echo "</div>";   
    }
}

function display_single_post($post){
    global $categories_links;
    echo "<div class = 'single_post'>";
    echo "<img src='".$post['thumb']."'><br>";
    echo "<div class = 'category'> - ";
    $post_cat = explode(', ', $post['category']);
    foreach($post_cat as $cat){
        echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
    }
    echo "</div>";
    echo "<div class = 'title font4'>".$post['title']."</div>";
    echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
    echo "<div class = 'article'>".$post['article']."</div>";
    echo '</div>';
    echo "<div class='void'></div>";
}
function display_featured_post($post){
    global $categories_links;
    echo "<div class = 'first_post'>";
    echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
    echo "<img src='".$post['thumb']."'></a>";
    echo "<div class = 'category'>";
    echo "Latest Post: - ";
    $post_cat = explode(', ', $post['category']);
    foreach($post_cat as $cat){
        echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
    }
    echo "</div>";
    echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
    echo "<div class='first_post_card'>";
    echo "<div class = 'title font4'>".$post['title']."</div>";
    echo "<div class = 'abstract'>".$post['abstract']."</div>";
    echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."</div>";
    echo '</div>';
    echo '</a></div>';
    echo "<div class='void'></div>";
}
function display_search_no_result($search_filter){
    echo "<div class='empty_page'>Sorry, did not find anything matching <b>".$search_filter."</b>.</div>"; 
}
$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$args = explode('/', $path);                // Split path on slashes. String starts with /
$args = array_diff($args, ["blog", "minimalist-blog"]);

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
$directory = './articles/';
$first_post = "yes";
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

$search_posts = [];
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
                array_push($search_posts, $post);
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
    
$home_page = 0;

$featured_post=$posts[0];
if (($cat_filter=="none") && ($post_filter=="none") && ($search_filter=="none") ) { //if ($path === '/'){
    if (pathinfo($featured_post, PATHINFO_EXTENSION)  == 'json'){
        $post = json_decode(file_get_contents($directory.$featured_post), true);
        display_featured_post($post);
        $home_page = 1;
        }
    
}
$posts_to_show = [];
$search_results = -1;
$cat_posts = 0;
if ($post_filter=="none") {
                $empty = 1;
                if ($cat_filter!="none"){
                    foreach ($posts as $p){
                        if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
                            $post = json_decode(file_get_contents($directory.$p), true);
                            if (strpos($post['category'], $cat_filter)!== false){
                                array_push($posts_to_show, $p);
                                $cat_posts +=1;
                                
                            }
                       }
                    }                   
                }
                else if ($search_filter!="none"){
                    $posts_to_show = $search_posts;
                }
                
                else{
                    $posts_to_show = array_slice($posts, 1);
                }
                if(count($posts_to_show)>0){
                    display_post_small($posts_to_show, implode(" ", $search_filter) );
                }
                else if($search_filter!="none"){
                    display_search_no_result(implode(" ", $search_filter));
                }
                else if ($cat_posts>0){
                    echo "<div class='empty_page'>Looks like I am gonna have to get things moving here!</div>";
                }
                else{
                    $empty = 0;
                }
}

else{
    $post = json_decode(file_get_contents($directory.$post_filter), true);
    $empty = 1;
    display_single_post($post);
}
if ($empty == 0){
    echo "<div class='empty_page'>404</div>";
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
    
function submit_search(){
        var search = document.getElementById('search').value.replace(/ /g,"-");;
    location.href = '../search/' + search;}
document.getElementById('submit').onclick = function() {
    submit_search();
};
document.getElementById('search').onkeydown = function(e){
   if(e.keyCode == 13){
     e.preventDefault();
     submit_search();
   }
};
    
</script>
</body>
</html>
