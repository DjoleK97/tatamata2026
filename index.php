<?php
require_once "includes/functions.php";
require_once "classes/Database.php";

$broj1  = rand(1, 10);
$broj2  = rand(1, 10);
$zbir   = $broj1 + $broj2;

$sviKursevi = Database::getInstance()->getAllCourses();
$sviRazredi = Database::getInstance()->getAllGrades();

// Mapa razred_id => naziv razreda
$mapaRazreda = [];
foreach ($sviRazredi as $r) {
  $mapaRazreda[$r['grade_id']] = $r['grade_name'];
}

include_once 'includes/header.php';
?>

<!-- ══════════════════════════════════════════
     HERO SEKCIJA
══════════════════════════════════════════ -->
<section class="hero-section">
  <div class="container position-relative" style="z-index:1; max-width:860px;">

    <?php printFormatedFlashMessage("register_success_message"); ?>
    <?php printFormatedFlashMessage("unauthorized_access"); ?>
    <?php printFormatedFlashMessage("contact_form_success"); ?>
    <?php printFormatedFlashMessage("login_success_message"); ?>

    <div class="hero-eyebrow">
      <i class="fas fa-graduation-cap"></i> Online kursevi matematike
    </div>

    <h1 class="hero-title">
      Nauci matematiku<br>
      <span class="highlight">na laksi nacin</span>
    </h1>

    <p class="hero-subtitle">
      Sveobuhvatni video kursevi, dostupni kad god pozelis —
      detaljno objasnjeni i lako razumljivi. Samo za tebe.
    </p>

    <!-- Traka za pretragu -->
    <form class="hero-search-form" onsubmit="return heroPretraga(this)">
      <input
        class="hero-search-input"
        type="text"
        id="hero-search-input"
        placeholder="Pretrazi kurseve (npr. razlomci, jednacine…)"
        autocomplete="off"
      >
      <button class="hero-search-btn" type="submit">
        <i class="fas fa-search"></i>&nbsp; Pretrazi
      </button>
    </form>

    <a href="<?php echo BASE_URL; ?>kursevi" class="hero-cta">
      Pogledaj sve kurseve <i class="fas fa-arrow-right"></i>
    </a>

    <!-- Statistike -->
    <div class="hero-stats">
      <div>
        <span class="hero-stat-num"><?php echo getAge('2015-09-15'); ?>+</span>
        <div class="hero-stat-lbl">godina iskustva</div>
      </div>
      <div>
        <span class="hero-stat-num">90+</span>
        <div class="hero-stat-lbl">zadovoljnih ucenika</div>
      </div>
      <div>
        <span class="hero-stat-num">87%</span>
        <div class="hero-stat-lbl">osmaka upisalo zeljenu skolu</div>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     KURSEVI GRID
══════════════════════════════════════════ -->
<section class="home-courses-section" id="pocetna-kursevi">
  <div class="container">

    <div class="text-center mb-2">
      <span class="section-badge"><i class="fas fa-play-circle me-1"></i> Kursevi</span>
    </div>
    <h2 class="section-title text-center">Izaberi kurs koji ti treba</h2>
    <p class="section-subtitle text-center">
      Kursevi za osnovnu skolu, srednju skolu i fakultet — sve na jednom mestu.
    </p>

    <div class="row g-4">
      <?php
      $prikazano = 0;
      foreach ($sviKursevi as $kurs) :
        if ($prikazano >= 6) break;
        $prikazano++;
        $nazivRazreda = $mapaRazreda[$kurs['grade_id']] ?? '';
      ?>
        <div class="col-lg-4 col-md-6">
          <a href="<?php echo BASE_URL; ?>kurs/<?php echo $kurs['id']; ?>" class="home-course-card">
            <div class="karta-slika">
              <?php if ($kurs['live']) : ?>
                <img
                  src="<?php echo BASE_URL; ?>public/images/courses/<?php echo $kurs['img']; ?>"
                  alt="<?php echo htmlspecialchars($kurs['name']); ?>"
                  loading="lazy"
                >
              <?php else : ?>
                <div class="karta-placeholder">
                  <i class="fas fa-clock"></i>
                  <span>U pripremi</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="karta-telo">
              <div class="karta-razred">
                <i class="fas fa-layer-group me-1"></i><?php echo htmlspecialchars($nazivRazreda); ?>
              </div>
              <div class="karta-naziv"><?php echo htmlspecialchars($kurs['name']); ?></div>
              <div class="karta-dno">
                <span class="btn-karta">Pogledaj kurs <i class="fas fa-arrow-right ms-1"></i></span>
                <?php if ($kurs['live']) : ?>
                  <span class="bedz-dostupno"><i class="fas fa-check-circle me-1"></i>Dostupan</span>
                <?php else : ?>
                  <span class="bedz-uskoro"><i class="fas fa-hourglass-half me-1"></i>Uskoro</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
      <a href="<?php echo BASE_URL; ?>kursevi" class="vidi-sve-link">
        Svi kursevi <i class="fas fa-arrow-right"></i>
      </a>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     O BRENDU
══════════════════════════════════════════ -->
<section id="o-brendu">
  <div class="container">

    <div class="text-center mb-2">
      <span class="section-badge"><i class="fas fa-user-tie me-1"></i> O meni</span>
    </div>
    <h2 class="section-title text-center">Matematika vise nece da ti bude problem</h2>
    <p class="razumevanje text-center mb-5">
      <em>"Razumevanje matematike je prvi korak do znanja i odlicne ocene"</em>
    </p>

    <div class="row g-5 align-items-start">

      <!-- Tekst levo -->
      <div class="col-lg-7 o-brendu-tekst">
        <h2><span class="color-yellow fw-bold">ZASTO</span> "TataMata"?</h2>
        <hr>
        <p>
          <strong>Volim</strong> da pomognem drugoj osobi ukoliko mogu i znam kako.<br>
          <strong>Verujem</strong> da matematiku moze <strong>svako da nauci</strong>,
          ako se stvari objasne na pravi nacin.<br>
          <strong>Moj san</strong> je da omogucim ucenicima sa nasih prostora
          da imaju kvalitetnu i jasnu nastavu koja ce im doneti dugotrajno znanje i odlicne rezultate.
        </p>

        <h2 class="mt-4"><span class="color-yellow fw-bold">KAKO</span> cu pokusati ovo da ostvarim?</h2>
        <hr>
        <p>
          Pravljenjem video kurseva koji su sveobuhvatni, laki za razumevanje i koji testiraju sta ste naucili.<br>
          Cilj mi je da svaku lekciju naucite sa <strong>razumevanjem</strong>.<br>
          Zbog toga mi je <strong>kvalitet</strong> nastave na prvom mestu.<br>
          Kvalitetna nastava vama daje cvrsto znanje i odlicne ocene — tako da svi pobedujemo.
        </p>
      </div>

      <!-- Profilna kartica desno -->
      <div class="col-lg-5">
        <div class="profil-kartica">
          <div class="profil-avatar">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <h3>Djole — TataMata</h3>
          <p>Profesor matematike sa vise od <?php echo getAge('2015-09-15'); ?> godina iskustva u radu sa ucenicima</p>
          <div class="d-flex justify-content-center gap-3 mt-4" style="flex-wrap:wrap;">
            <a href="https://www.youtube.com/c/TataMATA/" target="_blank" rel="noopener" class="btn btn-primary px-4">
              <i class="fab fa-youtube me-2"></i>YouTube
            </a>
            <a href="https://www.instagram.com/tatamata.casovi/" target="_blank" rel="noopener" class="btn btn-outline-secondary px-4">
              <i class="fab fa-instagram me-2"></i>Instagram
            </a>
          </div>
        </div>
      </div>

    </div>

    <!-- Statistike kartice -->
    <div class="row g-4 mt-4">
      <div class="col-md-4">
        <div class="stat-kartica">
          <i class="fas fa-briefcase"></i>
          <div class="stat-broj">
            <span class="scroll-counter" data-counter-time="2000"><?php echo getAge('2015-09-15'); ?></span>+
          </div>
          <div class="stat-opis">godina radnog iskustva</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-kartica">
          <i class="fas fa-heart"></i>
          <div class="stat-broj">
            <span class="scroll-counter" data-counter-time="2000">90</span>+
          </div>
          <div class="stat-opis">zadovoljnih ucenika</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-kartica">
          <i class="fas fa-trophy"></i>
          <div class="stat-broj">
            <span class="scroll-counter" data-counter-time="2000">87</span>%
          </div>
          <div class="stat-opis">osmaka upisalo zeljenu skolu</div>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     USLUGE
══════════════════════════════════════════ -->
<section id="usluge"></section>

<div id="usluge-second">
  <div class="container">

    <div class="text-center mb-2">
      <span class="section-badge" style="background:rgba(255,224,102,.15);color:var(--zuta);">
        <i class="fas fa-star me-1"></i> Sta nudimo
      </span>
    </div>
    <h1>USLUGE</h1>

    <div class="row g-4 justify-content-center">

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>kursevi" class="usluga-karta">
          <i class="fas fa-play-circle"></i>
          <p class="usluga-naziv">Kursevi</p>
          <p class="usluga-opis">Video kursevi iz matematike za sve uzraste</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>priprema-za-prijemni" class="usluga-karta">
          <i class="fas fa-medal"></i>
          <p class="usluga-naziv">Priprema za prijemni</p>
          <p class="usluga-opis">Intenzivna priprema za malu maturu i prijemni ispit</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>konsultacije" class="usluga-karta">
          <i class="fas fa-comments"></i>
          <p class="usluga-naziv">Konsultacije</p>
          <p class="usluga-opis">Individualne konsultacije iz matematike</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>priprema-za-pismene-i-kontrolne" class="usluga-karta">
          <i class="fas fa-pencil-alt"></i>
          <p class="usluga-naziv">Priprema za pismene</p>
          <p class="usluga-opis">Ciljana priprema za pismene i kontrolne zadatke</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>individualni-casovi" class="usluga-karta">
          <i class="fas fa-user-graduate"></i>
          <p class="usluga-naziv">Individualni casovi</p>
          <p class="usluga-opis">Casovi prilagodjeni tvom tempu i potrebama</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="<?php echo BASE_URL; ?>grupni-casovi" class="usluga-karta">
          <i class="fas fa-users"></i>
          <p class="usluga-naziv">Grupni casovi</p>
          <p class="usluga-opis">Ucite zajedno uz vodstvo iskusnog profesora</p>
        </a>
      </div>

    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════
     PREPORUKE
══════════════════════════════════════════ -->
<section id="testimonials">
  <div class="container">

    <div class="text-center mb-2">
      <span class="section-badge"><i class="fas fa-star me-1"></i> Recenzije</span>
    </div>
    <h1>STA KAZUJU UCENICI</h1>

    <div class="row">
      <div class="col-12">
        <div id="testimonial-slider" class="owl-carousel">

          <div class="preporuka-karta">
            <div class="preporuka-ikona"><i class="fas fa-quote-left"></i></div>
            <p class="preporuka-tekst">
              Zahvaljujuci pripremama za prijemni prosle godine moje dete je upisalo zeljenu skolu (gimnaziju).
              Svima toplo preporucujem, a mi nastavljamo saradnju i u srednjoj skoli.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava)"></i>Jelena, Miloseva mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-ikona"><i class="fas fa-quote-left"></i></div>
            <p class="preporuka-tekst">
              Sve preporuke za rad i saradnju sa ovim mladim covekom. Ima veoma pristupacen
              rad sa decom i iz njih izvuce najbolje. Zahvaljujuci timskom radu postigli smo
              super rezultate na prijemnom ispitu.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava)"></i>Rada, Nikolinina mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-ikona"><i class="fas fa-quote-left"></i></div>
            <p class="preporuka-tekst">
              Veoma pametna i sposobna osoba, strpljiv i susretljiv. Od kad radi sa mojom cerkom,
              matematika za nju vise nije toliko strasna i nerazumljiva. Svaka preporuka za dalju saradnju.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava)"></i>Jelena, Tarina mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-ikona"><i class="fas fa-quote-left"></i></div>
            <p class="preporuka-tekst">
              Napokon da nadjem nekoga ko ne komplikuje stvari. Sve si objasnio lepo, kratko i jednostavno.
              Puno ti hvala!
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava)"></i>Andrej, ucenik — YouTube komentar
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-ikona"><i class="fas fa-quote-left"></i></div>
            <p class="preporuka-tekst">
              Brate mili, sutra imam test, vise sam shvatio iz tvog klipa nego od privatnih casova,
              jer ti nekako pricas nasim jezikom. Svaka cast, mnogo sam sada siguran u sebe za bolju ocenu.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava)"></i>Ilija, ucenik — YouTube komentar
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     CESTA PITANJA (FAQ)
══════════════════════════════════════════ -->
<section id="faq">
  <div class="container">

    <div class="text-center mb-2">
      <span class="section-badge"><i class="fas fa-question-circle me-1"></i> Pomoc</span>
    </div>
    <h1 class="text-center fw-bold mb-3">CESTO POSTAVLJANA PITANJA</h1>

    <div id="faq-categories" class="d-flex justify-content-center flex-wrap">
      <p class="faq-category active fw-bold" data-categorie-name="kat1">
        <i class="fas fa-globe me-1"></i> Sajt
      </p>
      <p class="faq-category" data-categorie-name="kat2">
        <i class="fas fa-book me-1"></i> Kursevi
      </p>
      <p class="faq-category" data-categorie-name="kat3">
        <i class="fas fa-user me-1"></i> Nalog
      </p>
    </div>

    <div class="row">
      <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">

        <!-- Kategorija: SAJT -->
        <div class="accordion accordion-flush" id="accordion-faq-1" data-categorie-name="kat1">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-kol-1">
                Kako da koristim sajt tatamata.rs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-kol-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">Na pocetnoj strani imas video uputstvo u kome je sve detaljno objasnjeno.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-kol-2">
                Sa koliko uredjaja mogu da pristupim sajtu?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-kol-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">Mozes da pristupisas sajtu sa najvise 2 razlicita uredjaja (npr. laptop i telefon).</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-kol-3">
                Da li mogu da promenim uredjaj ukoliko ga vise ne koristim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-kol-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">Mozes, ali se obavezno prvo javi putem kontakt forme.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-kol-4">
                Da li je moguce gledati klipove preko mobilnog uredjaja?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-kol-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">Da, moguce je. Samo tokom gledanja klipova ukljuci rotaciju ekrana na telefonu.</div>
            </div>
          </div>
        </div>

        <!-- Kategorija: KURSEVI -->
        <div class="accordion accordion-flush" id="accordion-faq-2" data-categorie-name="kat2" style="display:none;">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-kol-1">
                Kako mogu da kupim zeljeni kurs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-kol-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">Odjes na stranicu "Kursevi", izaberes kurs i kliknes "Kupi kurs". Nakon toga popunis uplatnicu i uplatu vrsis u posti, banci ili putem internet bankarstva.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-kol-2">
                Kada cu dobiti pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-kol-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">Cim uplata bude evidentirana, dobijes pristup kursu.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-kol-3">
                Da li cu moci da razumem kurs ukoliko nemam predznanje?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-kol-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">Naravno! Kursevi su prilagodjeni svim ucenicima, nezavisno od trenutnog nivoa znanja.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-kol-4">
                Koliko dugo imam pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-kol-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">Imas pristup godinu dana od datuma kupovine.</div>
            </div>
          </div>
        </div>

        <!-- Kategorija: NALOG -->
        <div class="accordion accordion-flush" id="accordion-faq-3" data-categorie-name="kat3" style="display:none;">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-kol-1">
                Kako da znam da li treba da se registrujem ili prijavim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-kol-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">Ako si nov na sajtu i nemas nalog — registruj se (samo jedanput). Svaki sledeci put — prijavi se.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-kol-2">
                Zaboravio sam sifru i ne mogu da se ulogujem. Sta da radim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-kol-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">Na stranici "Prijavi se" klikni "Zaboravili ste sifru?". Unesi email i klikni "Potvrdi". Stici ce ti mejl sa linkom za promenu sifre.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-kol-3">
                Da li mogu da delim nalog sa jos nekom osobom?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle ms-auto flex-shrink-0" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-kol-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">Ne. Svaki nalog je namenjen iskljucivo jednoj osobi. Deljenje naloga rezultuje trajnim blokiranjem pristupa kursevima.</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     KONTAKT
══════════════════════════════════════════ -->
<section id="kontakt">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-12 text-center mb-2">
        <span class="section-badge"><i class="fas fa-envelope me-1"></i> Kontakt</span>
      </div>
      <h1 class="mb-4 text-center fw-bold">POSALJI PORUKU</h1>

      <div class="col-lg-7 col-md-9 col-sm-11 col-12">
        <div class="login-form-container">
          <form enctype="multipart/form-data" action="contact-form-logic.php" id="contact-form" method="POST" onsubmit="return validateForm()">

            <?php if (Database::getInstance()->isUserLoggedIn()) : ?>
              <div class="mb-4">
                <label class="form-label"><i class="fas fa-user"></i> Ime i prezime</label>
                <input id="contact-name" readonly name="firstname" type="text" class="form-control"
                  value="<?php echo htmlspecialchars($_SESSION['user']->firstname . ' ' . $_SESSION['user']->lastname); ?>">
              </div>
              <div class="mb-4">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input id="contact-email" readonly name="email" type="email" class="form-control"
                  value="<?php echo htmlspecialchars($_SESSION['user']->email ?? ''); ?>">
              </div>
            <?php else : ?>
              <div class="mb-4">
                <label class="form-label"><i class="fas fa-user"></i> Ime i prezime</label>
                <input id="contact-name" name="firstname" type="text" class="form-control"
                  placeholder="Tvoje ime i prezime" value="<?php echo htmlspecialchars($firstname ?? ''); ?>">
                <p id="name-error" class="invalid-feedback mb-0" style="display:none;">Molimo unesi ime.</p>
              </div>
              <div class="mb-4">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input id="contact-email" name="email" type="email" class="form-control"
                  placeholder="Tvoj email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <p id="email-error" class="invalid-feedback mb-0" style="display:none;">Email format nije validan.</p>
              </div>
            <?php endif; ?>

            <div class="mb-4">
              <label class="form-label"><i class="fas fa-comment-dots"></i> Poruka</label>
              <textarea id="contact-message" name="message" class="form-control" placeholder="Unesi poruku"></textarea>
              <p id="message-error" class="invalid-feedback mb-0" style="display:none;">Potrebno je uneti bar 20 karaktera.</p>
            </div>

            <div class="mb-4">
              <label class="form-label"><i class="fas fa-paperclip"></i> Dodaj fajlove <small style="color:var(--siva);font-weight:400;">(jpg, jpeg, png, pdf — maks. 5MB)</small></label>
              <input id="contact-files" accept="image/*,application/pdf" class="form-control" type="file" multiple name="files[]">
            </div>

            <div class="mb-4">
              <label class="form-label"><i class="fas fa-calculator"></i> Koliko je <?php echo $broj1 . ' + ' . $broj2; ?>?</label>
              <input id="math-input" name="math" type="number" class="form-control" placeholder="Unesi rezultat">
              <p id="math-error" class="invalid-feedback mb-0" style="display:none;">Rezultat nije tacan.</p>
            </div>

            <input type="hidden" name="result" value="<?php echo $zbir; ?>">
            <button id="contact-send-btn" name="contact" type="submit" class="btn btn-primary w-100 scale-btn-2" style="padding:14px; font-size:1rem;">
              <i class="fas fa-paper-plane me-2"></i>Posalji poruku
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>


<script>
function heroPretraga(form) {
  var q = document.getElementById('hero-search-input').value.trim();
  if (q) {
    window.location.href = '<?php echo BASE_URL; ?>kursevi?q=' + encodeURIComponent(q);
  }
  return false;
}
</script>

<?php include_once 'includes/plyr_footer.php'; ?>
<?php include_once 'includes/footer.php'; ?>
