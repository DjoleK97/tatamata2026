<?php
require_once "includes/functions.php";
require_once "classes/Database.php";

$number1 = rand(1, 10);
$number2 = rand(1, 10);
$result  = $number1 + $number2;

// Kursevi za homepage grid
$allCourses = Database::getInstance()->getAllCourses();
$allGrades  = Database::getInstance()->getAllGrades();

include_once 'includes/header.php';
?>

<!-- ══════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════ -->
<section class="hero-section">
  <div class="container position-relative" style="z-index:1;">

    <?php printFormatedFlashMessage("register_success_message"); ?>
    <?php printFormatedFlashMessage("unauthorized_access"); ?>
    <?php printFormatedFlashMessage("contact_form_success"); ?>
    <?php printFormatedFlashMessage("login_success_message"); ?>

    <div class="hero-eyebrow">📚 Online kursevi matematike</div>

    <h1 class="hero-title">
      Nauči matematiku<br>
      <span class="highlight">na lakši način</span>
    </h1>

    <p class="hero-subtitle">
      Sveobuhvatni video kursevi, dostupni kad god poželiš —
      detaljno objašnjeni i lako razumljivi.
    </p>

    <!-- Search bar -->
    <form class="hero-search-form" action="<?php echo BASE_URL; ?>kursevi" method="GET" onsubmit="return heroSearch(this)">
      <input
        class="hero-search-input"
        type="text"
        name="q"
        placeholder="Pretraži kurseve (npr. razlomci, jednačine…)"
        autocomplete="off"
      >
      <button class="hero-search-btn" type="submit">
        <i class="fas fa-search"></i>&nbsp; Pretraži
      </button>
    </form>

    <a href="<?php echo BASE_URL; ?>kursevi" class="hero-cta">
      Pogledaj sve kurseve <i class="fas fa-arrow-right"></i>
    </a>

    <!-- Stats -->
    <div class="hero-stats">
      <div>
        <span class="hero-stat-number"><?php echo getAge('2015-09-15'); ?>+</span>
        <div class="hero-stat-label">godina iskustva</div>
      </div>
      <div>
        <span class="hero-stat-number">90+</span>
        <div class="hero-stat-label">zadovoljnih učenika</div>
      </div>
      <div>
        <span class="hero-stat-number">87%</span>
        <div class="hero-stat-label">osmaka upisalo željenu školu</div>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     FEATURED COURSES GRID
══════════════════════════════════════════ -->
<section class="home-courses-section" id="pocetna-kursevi">
  <div class="container">
    <div class="text-center mb-2">
      <span class="section-badge">Kursevi</span>
    </div>
    <h2 class="section-title text-center">Izaberi kurs koji ti treba</h2>
    <p class="section-subtitle text-center">Kursevi za osnovnu školu, srednju školu i fakultet</p>

    <?php
    // Group courses by grade for display
    $coursesByGrade = [];
    foreach ($allCourses as $course) {
      $coursesByGrade[$course['grade_id']][] = $course;
    }

    // Map grade_id => grade info
    $gradeMap = [];
    foreach ($allGrades as $g) {
      $gradeMap[$g['grade_id']] = $g;
    }

    $shown = 0;
    $maxShow = 6; // show max 6 courses on homepage
    ?>

    <div class="row g-4">
      <?php foreach ($allCourses as $course) : ?>
        <?php if ($shown >= $maxShow) break; ?>
        <?php
          $gradeName = $gradeMap[$course['grade_id']]['grade_name'] ?? '';
          $shown++;
        ?>
        <div class="col-lg-4 col-md-6">
          <a href="<?php echo BASE_URL; ?>kurs/<?php echo $course['id']; ?>" class="home-course-card">
            <div class="card-img-wrap">
              <?php if ($course['live']) : ?>
                <img
                  src="<?php echo BASE_URL; ?>public/images/courses/<?php echo $course['img']; ?>"
                  alt="<?php echo htmlspecialchars($course['name']); ?>"
                  loading="lazy"
                >
              <?php else : ?>
                <img
                  src="<?php echo BASE_URL; ?>public/images/upripremi.png"
                  alt="U pripremi"
                  loading="lazy"
                >
              <?php endif; ?>
            </div>
            <div class="card-body-custom">
              <div class="card-grade-tag"><?php echo htmlspecialchars($gradeName); ?></div>
              <div class="card-title-text"><?php echo htmlspecialchars($course['name']); ?></div>
              <div class="card-bottom">
                <span class="btn-card-primary">Pogledaj kurs</span>
                <?php if ($course['live']) : ?>
                  <span class="card-live-badge">✓ Dostupan</span>
                <?php else : ?>
                  <span class="card-soon-badge">Uskoro</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
      <a href="<?php echo BASE_URL; ?>kursevi" class="view-all-link">
        Svi kursevi <i class="fas fa-arrow-right"></i>
      </a>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     O BRENDU
══════════════════════════════════════════ -->
<div id="o-brendu">
  <div class="container">
    <div class="text-center mb-2">
      <span class="section-badge">O meni</span>
    </div>
    <h1>Matematika više neće da ti bude problem :)</h1>
    <p class="razumevanje"><em>"Razumevanje matematike je prvi korak do znanja i odlične ocene"</em></p>

    <div id="five-divs">
      <div class="container-fluid">
        <div class="row text-center">
          <img src="public/images/za_kurseve2.png" alt="">
        </div>
      </div>
    </div>

    <div class="zastosamosnovao mt-5">
      <div class="row align-items-center g-5">
        <div class="col-md-12 col-lg-6">
          <h2><span class="color-yellow fw-bold">ZAŠTO</span> "TataMata"?</h2>
          <hr>
          <p class="volim-verujem">
            <strong>Volim</strong> da pomognem drugoj osobi ukoliko mogu i znam kako.<br>
            <strong>Verujem</strong> da matematiku može <strong>svako da nauči</strong>,
            ako se stvari objasne na pravi način.<br>
            <strong>Moj san</strong> je da omogućim učenicima sa naših prostora
            da imaju kvalitetnu i jasnu nastavu
            koja će im doneti dugoročno znanje i odlične rezultate.
          </p>

          <h2 class="mt-5"><span class="color-yellow fw-bold">KAKO</span> ću pokušati ovo da ostvarim?</h2>
          <hr>
          <p class="volim-verujem">
            Pravljenjem video kurseva koji su sveobuhvatni, laki za razumevanje i koji testiraju šta ste naučili.<br>
            Cilj mi je da svaku lekciju naučite sa <strong>razumevanjem.</strong><br>
            Zbog toga mi je <strong>kvalitet</strong> nastave na prvom mestu.<br>
            Kvalitetna nastava vama daje čvrsto znanje i odlične ocene,<br>
            a meni dobar ugled i više učenika.<br>
            Tako da svi pobeđujemo.
          </p>
        </div>
        <div class="col-md-12 col-lg-6 text-center">
          <img class="lepi-djolo" src="public/images/lepidjolo.png" alt="Đole - TataMata">
          <p class="mt-3" style="color:var(--gray);">Đole - TataMata</p>
        </div>
      </div>

      <div class="row counter-div text-center mt-5">
        <div class="col">
          <img class="imgCenter" src="public/images/iskustvo.png" alt="">
          <span class="scroll-counter" data-counter-time="2000"><?php echo getAge('2015-09-15'); ?></span>
          <div>godina<br>radnog iskustva</div>
        </div>
        <div class="col">
          <img class="imgCenter" src="public/images/srce2.png" alt="">
          <span class="scroll-counter" data-counter-time="2000">90</span>
          <div>zadovoljnih učenika</div>
        </div>
        <div class="col">
          <img class="imgCenter" src="public/images/smajli.png" alt="">
          <span class="scroll-counter" data-counter-time="2000">87</span>%
          <div>osmaka je upisalo<br>željenu školu</div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════
     USLUGE
══════════════════════════════════════════ -->
<section id="usluge"></section>

<div id="usluge-second">
  <div class="container">
    <div class="text-center mb-2">
      <span class="section-badge" style="background:#fff3;color:var(--dark);">Šta nudimo</span>
    </div>
    <h1>USLUGE</h1>
    <div class="row text-center justify-content-center">
      <div class="col-md-4 usluge-col">
        <a class="text-decoration-none" href="<?php echo BASE_URL; ?>kursevi">
          <img class="icon" src="public/images/usluge/kursevi.svg" alt="Kursevi">
          <p class="mt-3 usluga-title">Kursevi</p>
        </a>
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-md-0">
        <a href="<?php echo BASE_URL; ?>priprema-za-prijemni" class="text-decoration-none">
          <img class="icon" style="height:150px;" src="public/images/usluge/malamatura.svg" alt="Priprema za prijemni">
          <p class="usluga-title">Priprema za prijemni</p>
        </a>
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-md-0">
        <a href="<?php echo BASE_URL; ?>konsultacije" class="text-decoration-none">
          <img class="icon" style="height:140px;" src="<?php echo BASE_URL; ?>public/images/usluge/konsultacije.svg" alt="Konsultacije">
          <p class="mt-2 usluga-title">Konsultacije</p>
        </a>
      </div>
      <div class="col-md-4 usluge-col mt-3">
        <a href="<?php echo BASE_URL; ?>priprema-za-pismene-i-kontrolne" class="text-decoration-none">
          <img class="icon" src="public/images/usluge/pripremazapismeni.svg" alt="Priprema">
          <p class="mt-3 usluga-title">Priprema za pismene i kontrolne</p>
        </a>
      </div>
      <div class="col-md-4 usluge-col mt-3">
        <a href="<?php echo BASE_URL; ?>individualni-casovi" class="text-decoration-none">
          <img class="icon" style="height:140px;" src="public/images/usluge/individualnicasovi.svg" alt="Individualni">
          <p class="mt-3 usluga-title">Individualni časovi</p>
        </a>
      </div>
      <div class="col-md-4 usluge-col mt-3">
        <a href="<?php echo BASE_URL; ?>grupni-casovi" class="text-decoration-none">
          <img class="icon" style="height:140px;" src="public/images/usluge/grupnicasovi.svg" alt="Grupni">
          <p class="mt-3 usluga-title">Grupni časovi</p>
        </a>
      </div>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════ -->
<section id="testimonials">
  <div class="demoT">
    <div class="container">
      <div class="text-center mb-2">
        <span class="section-badge" style="background:rgba(255,224,102,.18);color:var(--yellow);">Recenzije</span>
      </div>
      <h1>REKLI SU O MENI</h1>
      <div class="row">
        <div class="col-md-12">
          <div id="testimonial-slider" class="owl-carousel">

            <div class="testimonial">
              <div class="testimonial-icon"><i class="fa fa-quote-left"></i></div>
              <p class="description">
                Zahvaljujući pripremama za prijemni prošle godine moje dete je upisalo
                željenu školu (gimnaziju). Svima toplo preporučujem a mi nastavljamo saradnju i u srednjoj školi.
              </p>
              <h3 class="title">Jelena, Miloševa mama</h3>
            </div>

            <div class="testimonial">
              <div class="testimonial-icon"><i class="fa fa-quote-left"></i></div>
              <p class="description">
                Sve preporuke za rad i saradnju sa ovim mladim čovekom. Ima veoma pristupačan
                rad sa decom i iz njih izvuče najbolje…zahvaljući njihovom timskom radu
                postigli smo super rezultate na prijemnom ispitu.
              </p>
              <h3 class="title">Rada, Nikolinina mama</h3>
            </div>

            <div class="testimonial">
              <div class="testimonial-icon"><i class="fa fa-quote-left"></i></div>
              <p class="description">
                Veoma pametna i sposobna osoba spremna da podeli i uspešno prenese svoje
                znanje na druge. Strpljiv i susretljiv, bez predrasuda.
                Od kad radi sa mojom ćerkom matematika za nju više nije toliko strašna.
              </p>
              <h3 class="title">Jelena, Tarina mama</h3>
            </div>

            <div class="testimonial">
              <div class="testimonial-icon"><i class="fa fa-quote-left"></i></div>
              <p class="description">
                Napokon da pronadjem nekoga ko ne komplikuje stvari. Sve si objasnio lepo, kratko i jednostavno. Puno ti hvala!
              </p>
              <h3 class="title">Andrej, učenik — YouTube komentar</h3>
            </div>

            <div class="testimonial">
              <div class="testimonial-icon"><i class="fa fa-quote-left"></i></div>
              <p class="description">
                Brate mili, sutra imam test, više sam shvatio iz tvog klipa nego od privatnih časova,
                jer ti nekako pričas našim jezikom. Svaka čast, mnogo sam sada siguran u sebe za bolju ocenu.
              </p>
              <h3 class="title">Ilija, učenik — YouTube komentar</h3>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     FAQ
══════════════════════════════════════════ -->
<section id="faq">
  <div class="container">
    <div class="text-center mb-2">
      <span class="section-badge">Pitanja</span>
    </div>
    <h1 class="text-center fw-bold mb-3">NAJČEŠĆE POSTAVLJENA PITANJA</h1>
    <div id="faq-categories" class="d-flex justify-content-center flex-wrap">
      <p class="faq-category active fw-bold" data-categorie-name="kat1">SAJT</p>
      <p class="faq-category" data-categorie-name="kat2">KURSEVI</p>
      <p class="faq-category" data-categorie-name="kat3">NALOG</p>
    </div>
    <div class="row">
      <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">

        <!-- KAT 1 -->
        <div class="accordion accordion-flush" id="accordion-faq-1" data-categorie-name="kat1">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-1">
                #1. Kako da koristim sajt tatamata.rs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">- Na vrhu ove strane imaš video uputstvo gde je sve detaljno objašnjeno.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-4">
                #2. Sa koliko uređaja mogu da pristupim sajtu?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-collapse-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">- Možeš da pristupiš sajtu sa najviše 2 različita uređaja. (npr. laptop i telefon)</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-6">
                #3. Da li mogu da promenim uređaj ukoliko ga više ne koristim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-collapse-6" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">- Možeš, ali obavezno se prvo javi putem forme!</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-2">
                #4. Da li je moguće gledati klipove preko mobilnog uređaja?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-1-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">- Moguće je. Samo prilikom gledanja klipova omogući rotaciju na telefonu.</div>
            </div>
          </div>
        </div>

        <!-- KAT 2 -->
        <div class="accordion accordion-flush" id="accordion-faq-2" data-categorie-name="kat2" style="display:none;">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-1">
                #1. Kako mogu da kupim željeni kurs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">- Odeš na "KURSEVI", izabereš kurs i klikneš "KUPI KURS". Popuniš uplatnicu i izvršiš uplatu u pošti, banci ili online.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-2">
                #2. Kada ću dobiti pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">- Čim uplata bude evidentirana, dobijaš pristup kursu.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-3">
                #3. Da li ću moći da razumem kurs ukoliko nemam predznanje?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-collapse-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">- Naravno! Kursevi su prilagođeni svim učenicima nezavisno od nivoa znanja.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-4">
                #4. Koliko dugo imam pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-collapse-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">- Imaš pristup godinu dana od datuma kupovine.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-5">
                #5. Kako da se prijavim za pripremnu nastavu za malu maturu?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-2-collapse-5" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">- Popuniš formu ispod ili piši u DM na instagramu @tatamata.casovi</div>
            </div>
          </div>
        </div>

        <!-- KAT 3 -->
        <div class="accordion accordion-flush" id="accordion-faq-3" data-categorie-name="kat3" style="display:none;">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-collapse-1">
                #1. Kako da znam da li treba da se registrujem ili prijavim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">- Ako si nov na sajtu i nemaš nalog — REGISTRUJ SE (samo jedanput). Svaki sledeći put — PRIJAVI SE.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-collapse-2">
                #2. Zaboravio/la šifru i ne mogu da se ulogujem. Šta da radim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">- Na stranici "PRIJAVI SE" klikni "Zaboravili ste šifru". Unesi email i klikni "POTVRDI". Doći će ti mejl sa linkom za promenu šifre.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-collapse-3">
                #3. Da li mogu da delim nalog sa još nekom osobom?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>
            </h2>
            <div id="faq-3-collapse-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">- Ne. Svaki nalog je namenjen isključivo jednoj osobi. Deljenje naloga rezultuje onemogućavanjem pristupa.</div>
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
<section id="kontakt" class="contact mb-0">
  <div class="container">
    <div class="row justify-content-center">
      <h1 class="mb-4 text-center fw-bold">KONTAKT</h1>

      <div class="col-lg-7 col-md-9 col-sm-11 col-12">
        <div class="login-form-container">
          <div class="row">
            <div class="col">
              <form enctype="multipart/form-data" action="contact-form-logic.php" id="contact-form" method="POST" onsubmit="return validateForm()">

                <?php if (Database::getInstance()->isUserLoggedIn()) : ?>
                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-user"></i> Ime i prezime</label>
                    <input id="contact-name" readonly name="firstname" type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user']->firstname . ' ' . $_SESSION['user']->lastname); ?>">
                  </div>
                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                    <input id="contact-email" readonly name="email" type="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user']->email ?? ''); ?>">
                  </div>
                <?php else : ?>
                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-user"></i> Ime i prezime</label>
                    <input id="contact-name" name="firstname" type="text" class="form-control" placeholder="Tvoje ime i prezime" value="<?php echo $firstname ?? ''; ?>">
                    <p id="name-error" class="invalid-feedback mb-0" style="display:none;">Molimo unesite ime.</p>
                  </div>
                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                    <input id="contact-email" name="email" type="email" class="form-control" placeholder="Tvoj email" value="<?php echo $email ?? ''; ?>">
                    <p id="email-error" class="invalid-feedback mb-0" style="display:none;">Email format nije validan.</p>
                  </div>
                <?php endif; ?>

                <div class="mb-4">
                  <label class="form-label"><i class="far fa-comment-dots"></i> Poruka</label>
                  <textarea id="contact-message" name="message" class="form-control" placeholder="Unesi poruku" style="height:110px;"></textarea>
                  <p id="message-error" class="invalid-feedback mb-0" style="display:none;">Potrebno je uneti bar 20 karaktera.</p>
                </div>

                <div class="mb-4">
                  <label class="form-label"><i class="fas fa-paperclip"></i> Dodajte fajlove</label>
                  <p class="mb-1" style="font-size:.8rem;color:var(--gray);">(jpg, jpeg, png, pdf) MAX 5MB</p>
                  <input id="contact-files" accept="image/*,application/pdf" class="form-control" type="file" multiple name="files[]">
                </div>

                <div class="mb-4">
                  <label class="form-label"><i class="fas fa-calculator"></i> Koliko je <?php echo $number1 . ' + ' . $number2; ?>?</label>
                  <input id="math-input" name="math" type="number" class="form-control" placeholder="Unesi rezultat">
                  <p id="math-error" class="invalid-feedback mb-0" style="display:none;">Rezultat nije tačan.</p>
                </div>

                <input type="hidden" name="result" value="<?php echo $result; ?>">
                <button id="contact-send-btn" name="contact" type="submit" class="btn btn-primary w-100 scale-btn-2" style="padding:14px;">
                  POŠALJI <i class="fas fa-check ms-1"></i>
                </button>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script>
function heroSearch(form) {
  const q = form.querySelector('input[name="q"]').value.trim();
  if (q) {
    window.location.href = '<?php echo BASE_URL; ?>kursevi?q=' + encodeURIComponent(q);
  }
  return false;
}
</script>

<?php include_once 'includes/plyr_footer.php'; ?>
<?php include_once 'includes/footer.php'; ?>
