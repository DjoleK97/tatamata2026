
<footer id="footer">
  <div class="container">
    <div class="row g-4">

      <!-- Brand kolona -->
      <div class="col-lg-4 col-md-6">
        <a href="<?php echo BASE_URL; ?>pocetna">
          <img src="<?php echo BASE_URL; ?>public/images/LOGO_VEKTOR.svg" alt="TataMata" class="footer-logo">
        </a>
        <p class="footer-opis">Online video kursevi matematike za ucenike osnovnih i srednjih skola. Uci sopstvenim tempom, sa razumevanjem.</p>
        <div class="footer-socijalne">
          <a href="https://www.youtube.com/c/TataMATA/" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          <a href="https://www.instagram.com/tatamata.casovi/" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="https://www.tiktok.com/@callmetatamata" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

      <!-- Navigacija kolona -->
      <div class="col-lg-2 col-md-3 col-6">
        <h4 class="footer-naslov">Navigacija</h4>
        <ul class="footer-linkovi">
          <li><a href="<?php echo BASE_URL; ?>pocetna">Pocetna</a></li>
          <li><a href="<?php echo BASE_URL; ?>kursevi">Kursevi</a></li>
          <li><a href="<?php echo BASE_URL; ?>pocetna#usluge">Usluge</a></li>
          <li><a href="<?php echo BASE_URL; ?>pocetna#faq">FAQ</a></li>
          <li><a href="<?php echo BASE_URL; ?>pocetna#kontakt">Kontakt</a></li>
        </ul>
      </div>

      <!-- Pravno kolona -->
      <div class="col-lg-2 col-md-3 col-6">
        <h4 class="footer-naslov">Pravno</h4>
        <ul class="footer-linkovi">
          <li><a href="<?php echo BASE_URL; ?>uslovi-koriscenja" target="_blank">Uslovi koriscenja</a></li>
          <li><a href="<?php echo BASE_URL; ?>politika-privatnosti" target="_blank">Politika privatnosti</a></li>
        </ul>
      </div>

      <!-- Kontakt kolona -->
      <div class="col-lg-4 col-md-6">
        <h4 class="footer-naslov">Kontakt</h4>
        <p class="footer-kontakt-info">
          <i class="fas fa-envelope me-2"></i> info@tatamata.rs
        </p>
        <p class="footer-kontakt-info">
          <i class="fas fa-map-marker-alt me-2"></i> Srbija
        </p>
      </div>

    </div>

    <hr class="footer-linija">

    <div class="footer-dno">
      <p>TataMata &copy; <?php echo date("Y"); ?></p>
      <p>Napravio <a target="_blank" href="https://pixelate.rs/"><strong>Pixelate</strong></a></p>
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
