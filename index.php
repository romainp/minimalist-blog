<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font-awesome/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <?php
    include './newsletter/subscribe.php';
    $directory = './articles/';
    $categories = ['woodwork', 'shape', 'electronics', 'software', 'misc'];
    $categories_links = ['woodwork' => 'Woodwork', 'shape'=>'Surfboard Shaping', 'electronics'=>'Electronics', 'software'=>'Software', 'misc'=>'Misc'];
    function display_post_small($posts, $message, $category){
        global $directory;
        global $categories_links;
        if(!empty($message)){
            echo "<div><h1> Search: <b>".$message.'</b></h1>Use quotes to search for the entire phrase: "search phrase".</div><div class="menu-void"></div>';
        }
        if ($category !== NULL){
            echo "<h1>".$category."</h1>";
        }
        echo "<div class='posts'>";
        for($j=0;$j<count($posts);$j++){
            $p=$posts[$j];
            if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
                $post = json_decode(file_get_contents($directory.$p), true);
                echo "<div class = 'post'>";
                echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
                echo "<img src='".$post['thumb']."'></a>";
                echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
                echo "<div class = 'title font4'>".$post['title']."</div></a>";
                echo "<div class = 'abstract'>".$post['abstract']."</div>";
                echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."<br>In: ";
                $post_cat = explode(', ', $post['category']);
                foreach($post_cat as $cat){
                    echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
                }
                echo "</div>";
                echo '</div>';
            }
        }
        echo '</div>' ;
    }

    function display_single_post($post){
        global $categories_links;
        echo "<h1>".$post['title']."</h1>";
        echo "<div class = 'single_post'>";
        echo "<img id='post-cover' src='".$post['thumb']."'><br>";
        if (isset($post['gallery-thumb'])){
            $thumbnails = explode(', ', $post['gallery-thumb']);
            echo "<div class = 'gallery-thumb'>";
            foreach($thumbnails as $thb){
                echo "<a href='#''><img src='".$thb."'></a>";
            }
            echo "</div>";
        }
        $post_cat = explode(', ', $post['category']);
        echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)." in ";
        foreach($post_cat as $cat){
            echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
        }
        echo "</div>";
        echo "<div class = 'article'>".$post['article']."</div>";
        echo '</div>';
        echo "<div class='void'></div>";
        echo "<div class='show-comments' id='show-comments'><h3 id='show-comments-toggle'><a href='#'>SHOW COMMENTS</a></h3><h3 id='hide-comments-toggle'><a href='#'>HIDE COMMENTS</a></h3></div>";
        echo "<div id='disqus_thread'></div>";

    }

    function display_featured_post($post){
        global $categories_links;
        echo "<h1>Some stuff I made</h1>";
        echo "<div class = 'first_post'>";
        echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
        echo "<img src='".$post['thumb']."'></a>";
        echo "<div class='first_post_card'>";
        echo "<a href='/post/".$post['id']."/".$post['slug']."'>";
        echo "<div class = 'title font4'>".$post['title']."</div></a>";
        echo "<div class = 'abstract'>".$post['abstract']."</div>";
        echo "<div class = 'date font2'>Posted on: ".substr($post['id'],6,2)."-".substr($post['id'],4,2)."-".substr($post['id'],0,4)."<br>In: ";
        $post_cat = explode(', ', $post['category']);
        foreach($post_cat as $cat){
            echo "<a href='/cat/".$cat."'>".$categories_links[$cat]."</a> - ";
        }
        echo "</div>";
        echo '</div>';
        echo '</div>';
    }
    function display_search_no_result($search_filter){
        echo "<div class='empty_page'>Sorry, did not find anything matching <b>".$search_filter.'</b>.<br><br>Use quotes to search for the entire phrase: "search phrase"</div>';
    }
$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$args = explode('/', $path);                // Split path on slashes. String starts with /
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
if (isset($_GET['cat'])) {
    foreach($categories as &$cat){
        if( $_GET['cat'] === $cat){
            $cat_filter = $cat;
            break;
        }
        $cat_filter = "404";
    }
}
else{
    $cat_filter = "none";
}

if (isset($_GET['post'])) {
    foreach($posts as &$post){
        if($_GET['post'].'.json' === $post){
            $post_filter = $post;
            break;
        }
        $post_filter = "404";
    }
}
else{
    $post_filter = "none";
}
if (isset($_GET['email']) and isset($_GET['key']) and isset($_GET['unsub'])){
    if($_GET['unsub'] == 'yes'){
        echo unsubscribe($_GET['email'], $_GET['key']);
    }
    else{
        echo "<div id='underlay' style='display:block'></div>";
        echo "<div id='message-subscribe' class='popup-div' style='display:block'>";
        echo "<a id = 'popup-close' class='title font6' href = '' > X </a>";
        echo "<div class='popup-inner'>";
        echo "<h2>Ouch!</h2><p style='text-align:left'>Sorry this link seems broken...</p> ";
        echo "</div></div>";
    }
}
else if (isset($_GET['email']) and isset($_GET['firstname'])){
    echo subscribe($_GET['email'], $_GET['firstname']);
}
else if (isset($_GET['email']) and isset($_GET['key'])){
    echo confirmation($_GET['email'], $_GET['key']);
}
?>
    <!--HEADER-->
<div id='logo-mobile' class='logo-mobile'>
        <div id='title-mobile' class='title-mobile font8'>
            <a href='/'>DUST</a>
        </div>
        <div id='subtitle-mobile' class='subtitle-mobile gold'>
            <a href='/'>&DONUTS</a>
        </div>
    </div>
<div id='underlay'></div>
<div id="subscribe-div" class='popup-div'>
    <a id = 'popup-close' class='title font6 mobile_off' href = '' > X </a>

    <form id="subscribe" action='/index.php'>
        <h2>Newsletter</h2>
      Receive an email everytime there is a new post!<br>
      <input id="firstname" type="text" name="firstname" placeholder="How should I call you?"><br>
      <input id="email" type="email" name="email" placeholder="What is your email address?"><br>
    </form>
<a href="#" onclick="document.forms['subscribe'].submit();"><h3>Subscribe</h3></a>
</div>
<div id='header' class='header'>
    <div id='logo' class='logo'>
        <div id='title' class='title font8'>
            <a href='/'>DUST</a>
        </div>
        <div id='subtitle' class='subtitle gold'>
            <a href='/'>&DONUTS</a>
        </div>
    </div>
    <div class='menu-void'></div>
    <div id='header-links' class='header-links'>
        <div class='top-link'>
            <a href='/'>Home</a>
        </div>
        <div class='top-link'><a href='/cat/woodwork'>Woodwork</a></div>
        <div class='top-link'><a href='/cat/shape'>Surfboard Shaping</a></div>
        <div class='top-link'><a href='/cat/electronics'>Electronics</a></div>
        <div class='top-link'><a href='/cat/software'>Software</a></div>
        <div class='top-link'><a href='/cat/misc'>Misc</a></div>
        <div class='menu-void'></div>
        <div class='search'>
            <input type="text" name="search" id="search" placeholder="search" />
        </div>
        <div class='menu-void'></div>
        <div class="top-link">
            <a target="_blank" href='http://www.instagram.com/'>Instagram</a>
        </div>
        <div class="top-link">
            <a target="_blank" href='http://www.youtube.com/'>Youtube</a>
        </div>
        <div class="top-link">
            <a id='subscribe-link' href=''>Subscribe</a>
        </div>
        <div class='menu-void'></div>
        <div class="top-link">
            <a href='/post/2016101501/about-me'>About me</a>
        </div>
        <div class="top-link">
            <a target="_blank" href=''>Got a question?</a>
        </div>
        <div class="top-link">
            <a target="_blank" href=''>Support me</a>
        </div>
        <div class='menu-void'></div>
        <div class='menu-void'></div>

        <div class='copyright'>
            Website made with love and a text editor. <br/><a target='_blank' href='https://github.com/romainp/minimalist-blog'>Check out the code here!</a>
        </div>
        <div class='menu-void'></div>
        <div id="header-end"></div>
    </div>
</div>

<!--HEADER-->
</head>
<body>
	<div id = 'menu-open'> <a id="menu-open-link" href = '' > <i class="fa fa-bars" aria-hidden="true"></i></a></div>
	<div class='content'>
	<?php
    $search_posts = [];
    $found = 0;
    $search_filter = [];
    if(isset($_GET['search'])){
        if (($_GET['search'][0] === '"') && (substr($_GET['search'], -1) === '"')){
            $search_filter = [str_replace("-", " ", trim($_GET['search'],'"'))];
        }
        else{
            $search_filter = explode('-', $_GET['search']);

        }
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
    else{
        $search_filter = [];
    }
    ?>
  	<?php

	$home_page = 0;

	$featured_post=$posts[0];
	$error = '';
	if(isset($_GET['error'])){
    	$error = $_GET['error'];
	}

	if (($cat_filter=="none") && ($post_filter=="none") && ($search_filter==[]) && !(isset($_GET['error'])) ) { //if ($path === '/'){
	    if (pathinfo($featured_post, PATHINFO_EXTENSION)  == 'json'){
	        $post = json_decode(file_get_contents($directory.$featured_post), true);
	        display_featured_post($post);
	        $home_page = 1;
	    }

	}
	$posts_to_show = [];
	$search_results = -1;
	$cat_posts = 0;
	$category;
	if ($post_filter==="none") {
	    if (($cat_filter!=="none") && ($cat_filter!=="404")){
	        foreach ($posts as $p){
	            if (pathinfo($p, PATHINFO_EXTENSION)  == 'json'){
	                $post = json_decode(file_get_contents($directory.$p), true);
	                if (strpos($post['category'], $cat_filter)!== false){
	                    array_push($posts_to_show, $p);
	                    $cat_posts += 1;
	                }
	            }
	        }
	    }
	    else if ($cat_filter==="404"){
	        echo "<div class='empty_page'>Hu, looks like there is a problem with the category you tried to retrieve.</div>";
	        $empty = 1;
	    }
	    else if ($search_filter!=[]){
	        $posts_to_show = $search_posts;
	    }
	    else if ($error === "404"){
	        echo "<div class='empty_page'>Hu, looks like there is a problem with the page you tried to retrieve.</div>";
	        $empty = 1;
	    }
	    else{
	        $posts_to_show = array_slice($posts, 1);
	    }
	    if(count($posts_to_show)>0){
	        if ((count($search_filter)>0) && (isset($_GET['search']))){
	            $search_terms = str_replace("-", " ", $_GET['search']);
	        }
	        else{
	            $search_terms = "";
	            $category = $categories_links[$cat_filter];
	        }
	        display_post_small($posts_to_show, $search_terms, $category) ;
	        $empty = 1;
	    }
	    else if($search_filter!=[]){
	        display_search_no_result(str_replace("-", " ", $_GET['search']));
	        $empty = 1;
	    }
	    else if (($cat_posts==0) && ($cat_filter!=="404") && !(isset($_GET['error']))){
	        echo "<div class='empty_page'>Looks like I am gonna have to get things moving here!</div>";
	        $empty = 1;
	    }
	}
	else{
	    if (file_exists($directory.$post_filter)) {
	        $post = json_decode(file_get_contents($directory.$post_filter), true);
	        $empty = 1;
	        display_single_post($post);
	    }
	    else{
	       echo "<div class='empty_page'>Could not find that post, hum..</div>";
	       $empty = 1;
	   }
	}
	if ($empty == 0){
	    echo "<div class='empty_page'>404</div>";
	}
	if ($post_filter != "404" && $post_filter != "none"){
	    echo "
	      INSERT DISQUS CODE here
        ";
	}
?>
</div><!--Content-->
<script >
    var mobile_header_scroll = 0;
    var mobile = 0;
    $(document).ready(function() {

        if ($( window ).width()<1000 ){

            if ($('#subscribe-div').is(":visible")){
                $('#menu-open-link').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            else if ($('#header').hasClass('open')){
                $('#menu-open-link').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            else if ($('#message-subscribe').is(":visible")){
                $('#menu-open-link').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
        }


        $('body').css('min-height', 'calc('.concat(String($('#header-end').offset().top)).concat('px - 2rem') );
        $( window ).resize(function() {//Android triggers a resize window when the nav bar is hidden. Makes the menu hide if scrolling when open.
            if ($( window ).width()>999 ){
                mobile = 0;
                if (!$('#header').hasClass('hidden')){
                    $('#header').css('display', 'block');
                }
                if ($('#header').hasClass('open')){
                    $('#header').css('display', 'none');
                    $('#header').removeClass('open');
                }
                if ($(window).scrollTop() < 16) {
                    if ($('#header').hasClass('hidden')){
                        $('#header').removeClass('hidden');
                        $('#header').toggle('show');
                        $('#header').css('top', 0);
                    }
                }
                $('#underlay').css('display', 'none');
                if ($('#subscribe-div').is( ":visible" )){
                    $('#subscribe-div').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                }
                else if ($('#message-subscribe').is( ":visible" )){
                    $('#message-subscribe').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                }
            }
            else{
                if ($('#header').is( ":visible" )){
                    $('#underlay').css('display', 'block');
                    $('#menu-open-link').html('<i class="fa fa-times" aria-hidden="true"></i>');

                }
                else if (!$('#subscribe-div').is( ":visible" )){
                 	$('#underlay').css('display', 'none');
                 	$('#menu-open-link').html('<i class="fa fa-bars" aria-hidden="true"></i>');

                }

            }

        });
        $(window).scroll(function() {
            if ($( window ).width()>999 ){ // if mobile version, the header is fixed to the page.
                var scroll = $(window).scrollTop();
                var header_end = $('#header-end').offset().top;
                var header_scroll = $('#header').offset().top;
                var window_height = $(window).height();
                if (scroll == 0){
                    $('#header').css('top', 0);//making sure than the header comes back to the top when back at the top of the page
                }
                if (header_end > window_height){
                    if (scroll < header_end - window_height || header_scroll < 0){
                        $('#header').css('top', -scroll);
                    }
                }
                else{

                    if (scroll > 15){
                        if (!$('#header').hasClass('hidden')){
                            $('#header').addClass('hidden');
                            $('#header').toggle('hide');

                        }
                    }
                    else if (scroll < 16) {
                        if ($('#header').hasClass('hidden')){
                            $('#header').removeClass('hidden');
                            $('#header').toggle('show');

                        }
                    }
                }
            }



        });

        $('.image-text').each(function(){
            $(this).append("<div class='image-instruction'>Click to zoom in</div>");
            initialize_pics($(this));
        });

        $('.image-text').click(function(event) {
            if ($(this).hasClass("big")){
                $(this).removeClass("big");
                $(this).find('.image-instruction').html('Click to zoom in');
            }
            else{
                $(this).addClass("big");
                $(this).find('.image-instruction').html('Click to zoom out');
           }
        });
        $('#search').keydown(function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                submit_search();
            }
        });
        $('#firstname').keydown(function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                $('#email').focus();
            }
        });
        $('#email').keydown(function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                $('#subscribe').submit();
            }
        });

            $('#menu-open').click(function(event) {
                event.preventDefault();
                if ($('#subscribe-div').is( ":visible" )){

                    $('#subscribe-div').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                    $('#underlay').fadeToggle('show');

                }
                else if ($('#message-subscribe').is( ":visible" )){
                    $('#message-subscribe').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                    $('#underlay').fadeToggle('show');

                }
                else if ($('#header').is( ":visible" )){
                    if ($( window ).width() < 985){
                        $('#header').fadeToggle('hide');
                        $('#header').removeClass('open');
                        $('#underlay').fadeToggle('show');
                        event.preventDefault();
                        $('#menu-open-link').animate({
                            opacity: "toggle"
                          }, 250, "linear", function() {
                            $( this ).html('<i class="fa fa-bars" aria-hidden="true"></i>');
                          });
                        $('#menu-open-link').animate({
                            opacity: "toggle"
                          }, 250, "linear");
                            }
                        }
                else{
                    event.preventDefault();
                    $('#header').fadeToggle('show');
                    $('#underlay').fadeToggle('show');
                    $('#header').css('top', 0);
                    $('#header').addClass('open');
                    $('#menu-open-link').animate({
                        opacity: "toggle"
                      }, 250, "linear", function() {
                        $( this ).html('<i class="fa fa-times" aria-hidden="true"></i>');
                      });
                    $('#menu-open-link').animate({
                        opacity: "toggle"
                      }, 250, "linear");
                    $('html, body').animate({scrollTop: '0'}, 350);
                }
            });


        $('#subscribe-link').click(function(event) {
                event.preventDefault();
                 if ($( window ).width() < 985){
                    if ($('#header').is( ":visible" )){
                        $('#header').toggle();
                        $('#header').removeClass('open');

                    }
                    $('#subscribe-div').fadeToggle();
                }
                else{
                    $('#underlay').fadeToggle();
                    $('#subscribe-div').fadeToggle();
                }
                $('#firstname').focus();
            });
        $('#underlay').click(function(event) {
                event.preventDefault();
                $('#underlay').fadeToggle('show');
                if ($('#subscribe-div').is( ":visible" )){
                    $('#subscribe-div').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                }
                else if ($('#message-subscribe').is( ":visible" )){
                    $('#message-subscribe').fadeToggle('show', function(){
                        window.location.href = '/';
                    });
                }
                else if ($('#header').is( ":visible" )){
                    if ($( window ).width() < 985){
                        $('#header').fadeToggle('hide');
                        $('#header').removeClass('open');
                        event.preventDefault();
                        $('#menu-open-link').animate({
                            opacity: "toggle"
                          }, 250, "linear", function() {
                            $( this ).html('<i class="fa fa-bars" aria-hidden="true"></i>');
                          });
                        $('#menu-open-link').animate({
                            opacity: "toggle"
                          }, 250, "linear");
                            }
                        }
            });
        $('#subscribe-close').click(function(event) {
                event.preventDefault();
                $('#subscribe-div').fadeToggle('show');
                $('#underlay').fadeToggle('show', function(){
                    window.location.href = '/';
                });
            });
        $('#popup-close').click(function(event) {
                event.preventDefault();
                $('#message-subscribe').toggle('show');
                $('#underlay').fadeToggle('show', function(){
                    window.location.href = '/';
                });

            });
        $('#subscribe-back').click(function(event) {
                event.preventDefault();
                $('#message-subscribe').toggle('show');
                $('#subscribe-div').fadeToggle('show');

        });
        $('.gallery-thumb a img').click(function(e){
            e.preventDefault();
            var new_pic = $(this).attr("src");
            $('#post-cover').fadeToggle(function(){
                $('#post-cover').attr("src", new_pic);
                $('#post-cover').fadeToggle();
            });
        });
        $('#show-comments-toggle').click(function(e){
            e.preventDefault();
            $('#show-comments-toggle').fadeToggle(function(){
                $('#hide-comments-toggle').fadeToggle();
            });
            $('#disqus_thread').fadeToggle().css("display","inline-block");
        });
        $('#hide-comments-toggle').click(function(e){
            e.preventDefault();
            $('#hide-comments-toggle').fadeToggle(function(){
                $('#show-comments-toggle').fadeToggle();
            });
            $('#disqus_thread').fadeToggle().css("display","inline-block");
        });
	});
    function submit_search(){
        var search = document.getElementById('search').value.replace(/ /g,"-");
        location.href = '/search/' + search;
    }
</script>
//DISQUS LINE OF CODE HERE
</body>
</html>
