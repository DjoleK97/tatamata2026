<br><br><br>

</div>

<?php if ($currentPage == '/index.php') { ?>

  <footer id="footer" class="text-center text-white">
    <div class="container-fluid">
      <div class="col">

        <div id="icons-and-terms">
          <div id="icons" class="d-flex">
            <a class="icon-link" target="_blank" href="https://www.youtube.com/c/TataMATA/"><i class="fab fa-youtube"></i></a>
            <a class="icon-link" target="_blank" href="https://www.instagram.com/tatamata.casovi/"><i class="fab fa-instagram"></i></a>
            <a class="icon-link" target="_blank" href="https://www.tiktok.com/@callmetatamata?lang=en"><i class="fab fa-tiktok"></i></a>
          </div>
          <div id="terms" class="d-flex justify-content-center">
            <p><a target="_blank" href="<?php echo BASE_URL; ?>uslovi-koriscenja">Uslovi korisćenja</a></p>
            <p class="politika"><a target="_blank" href="<?php echo BASE_URL; ?>politika-privatnosti">Politika privatnosti</a></p>
          </div>
        </div>

        <div id="copyright">
          <p class="text-white">
            TataMata © <?php echo date("Y"); ?>
          </p>
          <p class="text-white">
            Site created by
            <a target="_blank" class="text-decoration-none pixelate" href="https://pixelate.rs/"><strong>Pixelate</strong>
            </a>
          </p>
        </div>

      </div>
    </div>

  </footer>

<?php } ?>


<script defer src="<?php echo BASE_URL . "public/js/script.js" ?>"></script>
<?php if ($currentPage == '/prijava.php' || $currentPage == '/registracija.php') { ?>
  <script src="<?php echo BASE_URL . "public/js/bfp.js" ?>"></script>
  <script async src="//cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js" onload="initFingerprintJS()"></script>
<?php } ?>

</body>

</html>