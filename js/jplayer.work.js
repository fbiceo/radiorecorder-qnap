$("#jquery_jplayer_1").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				title: "Bubble",
				mp3: "http://jplayer.org/audio/mp3/Miaow-07-Bubble.mp3"
			});
		},
		swfPath: "jplayer.2.7/js",
		supplied: "mp3",
		wmode: "window",
		smoothPlayBar: true,
		keyEnabled: true,
		remainingDuration: true,
		toggleDuration: true
	});