
</div>

<footer id="footer">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-auto">

        <div id="icons" class="d-flex mb-4">
          <a class="icon-link" target="_blank" href="https://www.youtube.com/c/TataMATA/" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          <a class="icon-link" target="_blank" href="https://www.instagram.com/tatamata.casovi/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a class="icon-link" target="_blank" href="https://www.tiktok.com/@callmetatamata?lang=en" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>

        <div id="terms" class="d-flex justify-content-center mb-3">
          <p><a target="_blank" href="<?php echo BASE_URL; ?>uslovi-koriscenja">Uslovi korišćenja</a></p>
          <p class="ms-4"><a target="_blank" href="<?php echo BASE_URL; ?>politika-privatnosti">Politika privatnosti</a></p>
        </div>

        <div id="copyright">
          <p>TataMata &copy; <?php echo date("Y"); ?></p>
          <p>Napravio <a target="_blank" class="text-decoration-none pixelate" href="https://pixelate.rs/"><strong>Pixelate</strong></a></p>
        </div>

      </div>
    </div>
  </div>
</footer>


<script defer src="<?php echo BASE_URL . "public/js/script.js" ?>"></script>
<?php if ($currentPage == '/prijava.php' || $currentPage == '/registracija.php') { ?>
  <script src="<?php echo BASE_URL . "public/js/bfp.js" ?>"></script>
  <script async src="//cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js" onload="initFingerprintJS()"></script>
<?php } ?>

</body>

</html>