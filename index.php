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
      <i class="fas fa-graduation-cap"></i> Matematika postaje laka
    </div>

    <h1 class="hero-title">
      Matematika može<br>
      <span class="highlight">da bude laka</span>
    </h1>

    <p class="hero-subtitle">
      Video kursevi objašnjeni korak po korak, dostupni 24/7.
      Učiš sopstvenim tempom i razumeš gradivo.
    </p>

    <!-- CTA dugmad -->
    <div class="hero-cta-group">
      <a href="<?php echo BASE_URL; ?>kursevi" class="hero-cta-primary">
        Pogledaj kurseve <i class="fas fa-arrow-right"></i>
      </a>
      <a href="<?php echo BASE_URL; ?>pocetna#o-brendu" class="hero-cta-secondary">
        <i class="fas fa-play-circle"></i> Upoznaj me
      </a>
    </div>

    <!-- Statistike -->
    <div class="hero-stats">
      <div class="animiraj">
        <span class="hero-stat-num"><?php echo getAge('2015-09-15'); ?>+</span>
        <div class="hero-stat-lbl">godina iskustva</div>
      </div>
      <div class="animiraj">
        <span class="hero-stat-num">90+</span>
        <div class="hero-stat-lbl">zadovoljnih učenika</div>
      </div>
      <div class="animiraj">
        <span class="hero-stat-num">87%</span>
        <div class="hero-stat-lbl">osmaka upisalo željenu školu</div>
      </div>
      <div class="animiraj">
        <span class="hero-stat-num">24/7</span>
        <div class="hero-stat-lbl">dostupnost kurseva</div>
      </div>
    </div>

    <!-- Trust strip -->
    <div class="hero-trust">
      <span><i class="fas fa-shield-alt"></i> Sigurna platforma</span>
      <span><i class="fas fa-clock"></i> Pristup godinu dana</span>
      <span><i class="fas fa-redo"></i> Gledaj neograničeno</span>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     KURSEVI GRID
══════════════════════════════════════════ -->
<section class="home-courses-section" id="pocetna-kursevi">
  <div class="container">

    <div class="text-center mb-2 animiraj">
      <span class="section-badge"><i class="fas fa-play-circle me-1"></i> Video kursevi</span>
    </div>
    <h2 class="section-title text-center animiraj">Izaberi kurs koji ti treba</h2>
    <p class="section-subtitle text-center animiraj">
      Kursevi za osnovnu skolu, srednju skolu i fakultet — sve na jednom mestu.
    </p>

    <div class="row g-4 animiraj-grupa">
      <?php
      $prikazano = 0;
      foreach ($sviKursevi as $kurs) :
        if ($prikazano >= 6) break;
        $prikazano++;
        $nazivRazreda = $mapaRazreda[$kurs['grade_id']] ?? '';
      ?>
        <div class="col-lg-4 col-md-6 animiraj">
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

    <div class="text-center mt-5 animiraj">
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

    <div class="text-center mb-2 animiraj">
      <span class="section-badge"><i class="fas fa-user-tie me-1"></i> O meni</span>
    </div>
    <h2 class="section-title text-center animiraj">Ko stoji iza TataMata?</h2>
    <p class="section-subtitle text-center animiraj">
      Upoznaj profesora koji stoji iza svakog video kursa.
    </p>

    <div class="row g-5 align-items-start">

      <!-- Tekst levo -->
      <div class="col-lg-7 animiraj">
        <div class="brendu-stavka">
          <div class="brendu-ikona"><i class="fas fa-heart"></i></div>
          <div>
            <h4>Volim da pomognem</h4>
            <p>Verujem da matematiku može naučiti svako, ako se stvari objasne na pravi način. Moj pristup je zasnovan na razumevanju, ne na mehaničkom bubanju formula.</p>
          </div>
        </div>

        <div class="brendu-stavka">
          <div class="brendu-ikona"><i class="fas fa-lightbulb"></i></div>
          <div>
            <h4>Kvalitet na prvom mestu</h4>
            <p>Svaki kurs je pažljivo osmišljen i detaljno snimljen. Cilj mi je da svaku lekciju naučiš sa razumevanjem, a ne da samo prođeš.</p>
          </div>
        </div>

        <div class="brendu-stavka">
          <div class="brendu-ikona"><i class="fas fa-bullseye"></i></div>
          <div>
            <h4>Tvoj uspeh je moj cilj</h4>
            <p>Moj san je da omogućim učenicima sa naših prostora da imaju kvalitetnu nastavu koja će im doneti dugotrajno znanje i odlične rezultate.</p>
          </div>
        </div>
      </div>

      <!-- Profilna kartica desno -->
      <div class="col-lg-5 animiraj">
        <div class="profil-kartica">
          <div class="profil-avatar">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <h3>Djole — TataMata</h3>
          <p class="profil-uloga">Osnivač TataMata platforme</p>
          <p>Profesor matematike sa više od <?php echo getAge('2015-09-15'); ?> godina iskustva u radu sa učenicima</p>
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

  </div>
</section>


<!-- ══════════════════════════════════════════
     USLUGE
══════════════════════════════════════════ -->
<section id="usluge"></section>

<div id="usluge-second">
  <div class="container">

    <div class="text-center mb-2 animiraj">
      <span class="section-badge" style="background:rgba(255,224,102,.15);color:var(--zuta);">
        <i class="fas fa-star me-1"></i> Naše usluge
      </span>
    </div>
    <h1 class="animiraj">Šta sve nudimo?</h1>

    <div class="row g-4 justify-content-center animiraj-grupa">

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>kursevi" class="usluga-karta">
          <i class="fas fa-play-circle"></i>
          <p class="usluga-naziv">Kursevi</p>
          <p class="usluga-opis">Video kursevi iz matematike za sve uzraste</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>priprema-za-prijemni" class="usluga-karta">
          <i class="fas fa-medal"></i>
          <p class="usluga-naziv">Priprema za prijemni</p>
          <p class="usluga-opis">Intenzivna priprema za malu maturu i prijemni ispit</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>konsultacije" class="usluga-karta">
          <i class="fas fa-comments"></i>
          <p class="usluga-naziv">Konsultacije</p>
          <p class="usluga-opis">Individualne konsultacije iz matematike</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>priprema-za-pismene-i-kontrolne" class="usluga-karta">
          <i class="fas fa-pencil-alt"></i>
          <p class="usluga-naziv">Priprema za pismene</p>
          <p class="usluga-opis">Ciljana priprema za pismene i kontrolne zadatke</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>individualni-casovi" class="usluga-karta">
          <i class="fas fa-user-graduate"></i>
          <p class="usluga-naziv">Individualni časovi</p>
          <p class="usluga-opis">Časovi prilagođeni tvom tempu i potrebama</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 animiraj">
        <a href="<?php echo BASE_URL; ?>grupni-casovi" class="usluga-karta">
          <i class="fas fa-users"></i>
          <p class="usluga-naziv">Grupni časovi</p>
          <p class="usluga-opis">Učite zajedno uz vodstvo iskusnog profesora</p>
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

    <div class="text-center mb-2 animiraj">
      <span class="section-badge"><i class="fas fa-star me-1"></i> Recenzije</span>
    </div>
    <h1 class="animiraj">Šta kažu učenici i roditelji</h1>

    <div class="row">
      <div class="col-12">
        <div id="testimonial-slider" class="owl-carousel">

          <div class="preporuka-karta">
            <div class="preporuka-zvezdice"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="preporuka-tekst">
              Zahvaljujući pripremama za prijemni, prošle godine moje dete je upisalo željenu školu (gimnaziju).
              Svima toplo preporučujem, a mi nastavljamo saradnju i u srednjoj školi.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava); font-size:1.3rem;"></i>Jelena, Miloseva mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-zvezdice"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="preporuka-tekst">
              Sve preporuke za rad i saradnju sa ovim mladim covekom. Ima veoma pristupacen
              rad sa decom i iz njih izvuce najbolje. Zahvaljujuci timskom radu postigli smo
              super rezultate na prijemnom ispitu.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:#059669; font-size:1.3rem;"></i>Rada, Nikolinina mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-zvezdice"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="preporuka-tekst">
              Veoma pametna i sposobna osoba, strpljiv i susretljiv. Od kad radi sa mojom cerkom,
              matematika za nju više nije toliko strašna i nerazumljiva. Svaka preporuka za dalju saradnju.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:#d97706; font-size:1.3rem;"></i>Jelena, Tarina mama
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-zvezdice"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="preporuka-tekst">
              Napokon da nadjem nekoga ko ne komplikuje stvari. Sve si objasnio lepo, kratko i jednostavno.
              Puno ti hvala!
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:var(--plava); font-size:1.3rem;"></i>Andrej, ucenik — YouTube komentar
            </div>
          </div>

          <div class="preporuka-karta">
            <div class="preporuka-zvezdice"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="preporuka-tekst">
              Brate mili, sutra imam test, vise sam shvatio iz tvog klipa nego od privatnih casova,
              jer ti nekako pricas nasim jezikom. Svaka cast, mnogo sam sada siguran u sebe za bolju ocenu.
            </p>
            <div class="preporuka-autor">
              <i class="fas fa-user-circle me-2" style="color:#059669; font-size:1.3rem;"></i>Ilija, ucenik — YouTube komentar
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

    <div class="text-center mb-2 animiraj">
      <span class="section-badge"><i class="fas fa-question-circle me-1"></i> Pomoc</span>
    </div>
    <h1 class="text-center fw-bold mb-4 animiraj">Često postavljana pitanja</h1>

    <div class="row animiraj">
      <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">

        <div class="accordion accordion-flush" id="accordion-faq">

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q1">
                Kako mogu da kupim željeni kurs?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Odeš na stranicu "Kursevi", izabereš kurs i klikneš "Kupi kurs". Nakon toga popuniš uplatnicu i uplatu vršiš u pošti, banci ili putem internet bankarstva.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q2">
                Kada cu dobiti pristup kursevima?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Čim uplata bude evidentirana, dobiješ pristup kursu.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q3">
                Koliko dugo imam pristup kursevima?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Imaš pristup godinu dana od datuma kupovine.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q4">
                Da li cu moci da razumem kurs ukoliko nemam predznanje?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Naravno! Kursevi su prilagođeni svim učenicima, bez obzira na trenutni nivo znanja.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q5">
                Sa koliko uredjaja mogu da pristupim sajtu?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q5" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Možeš da pristupiš sajtu sa najviše 2 različita uređaja (npr. laptop i telefon).</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q6">
                Da li je moguce gledati klipove preko mobilnog uredjaja?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q6" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Da, moguće je. Samo tokom gledanja klipova uključi rotaciju ekrana na telefonu.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q7">
                Kako da znam da li treba da se registrujem ili prijavim?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q7" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Ako si nov na sajtu i nemaš nalog — registruj se (samo jedanput). Svaki sledeći put — prijavi se.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q8">
                Da li mogu da delim nalog sa jos nekom osobom?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q8" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Ne. Svaki nalog je namenjen isključivo jednoj osobi. Deljenje naloga rezultuje trajnim blokiranjem pristupa kursevima.</div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-q9">
                Zaboravio sam sifru i ne mogu da se ulogujem. Sta da radim?
                <i class="fas fa-plus-circle ms-auto flex-shrink-0"></i>
              </button>
            </h2>
            <div id="faq-q9" class="accordion-collapse collapse" data-bs-parent="#accordion-faq">
              <div class="accordion-body">Na stranici "Prijavi se" klikni "Zaboravili ste šifru?". Unesite email i kliknite "Potvrdi". Stići će vam mejl sa linkom za promenu šifre.</div>
            </div>
          </div>

        </div>

        <!-- CTA ispod FAQ-a -->
        <div class="faq-cta text-center">
          <p>Nisi pronašao/la odgovor?</p>
          <a href="#kontakt" class="btn-outline-plava btn">Posalji nam poruku <i class="fas fa-arrow-right ms-1"></i></a>
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

    <div class="text-center mb-2 animiraj">
      <span class="section-badge"><i class="fas fa-envelope me-1"></i> Kontakt</span>
    </div>
    <h1 class="mb-4 text-center fw-bold animiraj">Posalji nam poruku</h1>

    <div class="row justify-content-center g-4">

      <div class="col-lg-7 col-md-8 animiraj">
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
              <label class="form-label"><i class="fas fa-paperclip"></i> Dodaj fajlove <small style="color:var(--siva-500);font-weight:400;">(jpg, jpeg, png, pdf — maks. 5MB)</small></label>
              <input id="contact-files" accept="image/*,application/pdf" class="form-control" type="file" multiple name="files[]">
            </div>

            <div class="mb-4">
              <label class="form-label"><i class="fas fa-calculator"></i> Koliko je <?php echo $broj1 . ' + ' . $broj2; ?>?</label>
              <input id="math-input" name="math" type="number" class="form-control" placeholder="Unesi rezultat">
              <p id="math-error" class="invalid-feedback mb-0" style="display:none;">Rezultat nije tacan.</p>
            </div>

            <input type="hidden" name="result" value="<?php echo $zbir; ?>">
            <button id="contact-send-btn" name="contact" type="submit" class="btn btn-primary w-100" style="padding:14px; font-size:1rem;">
              <i class="fas fa-paper-plane me-2"></i>Posalji poruku
            </button>

          </form>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 animiraj">
        <div class="kontakt-info-karta">
          <h3><i class="fas fa-headset me-2" style="color:var(--plava);"></i> Imate pitanje?</h3>
          <div class="kontakt-info-stavka">
            <i class="fas fa-envelope"></i>
            <span>info@tatamata.rs</span>
          </div>
          <div class="kontakt-info-stavka">
            <i class="fas fa-clock"></i>
            <span>Odgovaramo u roku od 24h</span>
          </div>
          <div class="kontakt-info-stavka">
            <i class="fas fa-map-marker-alt"></i>
            <span>Srbija</span>
          </div>
          <hr style="border-color: var(--siva-200);">
          <p style="font-size:.85rem; color:var(--siva-700); margin-bottom:12px;">Pratite nas na mrezi:</p>
          <div class="kontakt-socijalne">
            <a href="https://www.youtube.com/c/TataMATA/" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="https://www.instagram.com/tatamata.casovi/" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://www.tiktok.com/@callmetatamata" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<?php include_once 'includes/plyr_footer.php'; ?>
<?php include_once 'includes/footer.php'; ?>
