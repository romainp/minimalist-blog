<?php

function subscribe($email, $firstName ){

	$file = dirname(__FILE__)."/addressBook/addressBook.json";
	$string = file_get_contents($file);
	$addressBook = json_decode( $string, true );
	echo "<div id='underlay' style='display:block'></div>";
	echo "<div id='message-subscribe' class='popup-div' style='display:block'>";
	echo "<a id = 'popup-close' class='title font6 mobile_off' href = '' > X </a>";
	echo "<div class='popup-inner'>";
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){
	    if (isset($addressBook["subscribers"][$email])){
	        echo "<h2>Hey ".$addressBook["subscribers"][$email]['firstName']."!</h2>You have already subscribed, thanks! <br><br>";
	    }
	    else if (isset($addressBook["unconfirmed"][$email])){
	        echo "<h2>Hey ".$addressBook["unconfirmed"][$email]['firstName']."!</h2><p style='text-align:left'>It seems that you already tried to subscribe. I went ahead and sent you the confirmation email again. If you still don't receive the email, check your spam folder or <a href='mailto:address@email.com?Subject=Website Issue!' target='_blank'>click here</a> to send me an email! </p>";
	    	exec("python newsletter/sendEmail-subscribe.py ".$email." ".$addressBook["unconfirmed"][$email]['key']." ".$firstName,$error);

	    }
	    else{
	        $key = sha1(microtime(true).mt_rand(10000,90000));
	        exec("python newsletter/sendEmail-subscribe.py ".$email." ".$key." ".$firstName,$error);
	        if ($error[0] == "ok"){
	            echo "<h2>Thanks ".$firstName."!</h2> Please check the inbox of <b>".$email."</b> to validate your subscription.";
	            $addressBook["unconfirmed"][$email] = ["firstName"=>$firstName,"key"=>$key];
	            file_put_contents($file, json_encode($addressBook,TRUE));
	        }
	        else{
	            echo "<h2>Ouch!</h2>Sorry <b>".$firstName."</b>, an error occured. Please contact me <a href='mailto:address@email.com?Subject=Website Issue!' target='_blank'>HERE</a> for more info.<br>";
	        }
	    }
	}
	else{
	    echo "<h2>Ouch!</h2>Sorry this email address does not seem to be valid. <br><br><a style='display:block;text-align:center;clear:both;' href='#' id='subscribe-back'>Go back!</a>";
	}
	echo "</div></div>";
}


function confirmation($email, $key ){

	$file = dirname(__FILE__)."/addressBook/addressBook.json";
	$string = file_get_contents($file);
	$addressBook = json_decode( $string, true );
	echo "<div id='underlay' style='display:block'></div>";
	echo "<div id='message-subscribe' class='popup-div' style='display:block'>";
	echo "<a id = 'popup-close' class='title font6 mobile_off' href = '' > X </a>";
	echo "<div class='popup-inner'>";
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){
	    if (isset($addressBook["subscribers"][$email])){
	        echo "<h2>Hey ".$addressBook["subscribers"][$email]['firstName']."!</h2>Your email was already confirmed! <br><br>";
	    }
	    else if (isset($addressBook["unconfirmed"][$email])){
	    	if ($addressBook["unconfirmed"][$email]['key'] === $key){
	        	echo "<h2>Hey ".$addressBook["unconfirmed"][$email]['firstName']."!</h2><p style='text-align:left'>Thanks a lot for confirming your newsletter subscription! </p> ";
	        	$addressBook["subscribers"][$email] = $addressBook["unconfirmed"][$email];
	        	unset ($addressBook["unconfirmed"][$email]);
	        	file_put_contents($file, json_encode($addressBook,TRUE));
	        	exec("python newsletter/notification.py ".$email." sub",$error2);
	    	}
	    	else{
	    		echo "<h2>Ouch!</h2><p style='text-align:left'>You tried to validate your subscription to the newsletter but I think the link you clicked is broken or something, the safety key doesn't match the records...</p> ";
	    	}
	    }
	    else{
	    	echo "<h2>Ouch!</h2><p style='text-align:left'>You tried to validate your subscription to the newsletter but I have no record for that email address...</p> ";
	    }
	}
	else{
		echo "<h2>Ouch!</h2><p style='text-align:left'>Seems like the formatting of this email address is not quite right...</p> ";
	}
	echo "</div></div>";
}

function unsubscribe($email, $key ){

	$file = dirname(__FILE__)."/addressBook/addressBook.json";
	$string = file_get_contents($file);
	$addressBook = json_decode( $string, true );
	echo "<div id='underlay' style='display:block'></div>";
	echo "<div id='message-subscribe' class='popup-div' style='display:block'>";
	echo "<a id = 'popup-close' class='title font6 mobile_off' href = '' > X </a>";
	echo "<div class='popup-inner'>";
	    if (isset($addressBook["subscribers"][$email])){
	    	if ($addressBook["subscribers"][$email]['key'] === $key){
	        	echo "<h2>Sad Face!</h2><p style='text-align:left'>I am sad to see you go, but I wish you a great day anyway! <br>You successfully unsubscribed.</p> ";
	        	unset ($addressBook["subscribers"][$email]);
	        	file_put_contents($file, json_encode($addressBook,TRUE));
	        	exec("python newsletter/notification.py ".$email." unsub",$error2);
	    	}
	    	else{
	    		echo "<h2>Ouch!</h2><p style='text-align:left'>You tried to unsubscribe from the newsletter but I think the link you just clicked on is broken or something, the safety key doesn't match the records...</p> ";
	    	}
	    }
	    else{
	    	echo "<h2>Ouch!</h2><p style='text-align:left'>You tried to unsubscribe from the newsletter but I have no record for that email address...</p> ";
	    }
	echo "</div></div>";
}


?>
