<script src="https://cdn.plyr.io/3.6.4/plyr.js"></script>
<script>
  let players = document.querySelectorAll('video');
  let counterPlyr = 0;
  if (players) {
    players.forEach(player => {
      let element = document.querySelector(`[data-video-index="${counterPlyr++}"]`);

      new Plyr(element, {
        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'airplay', 'fullscreen'],
      });
    });
  }
</script>