social-counter
==============

Display count number of each social media


example
==============

	$Social = new Social();
	
	$Social->set_prefix('prefix_');

	echo $Social->facebook('https://www.facebook.com/facebook');
	
	echo $Social->twitter( "twitter username", "consumer key ", "consumer secret" , "access token" , "access token secret");

	echo $Social->dribbble('http://dribbble.com/dribbble');

	echo $Social->vimeo('http://vimeo.com/channels/staffpicks');

	echo $Social->youtube('http://www.youtube.com/user/youtube');

	echo $Social->github('https://github.com/mustafaismail22');

