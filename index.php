<?php
require_once "includes/functions.php";
include_once 'includes/header.php';


// $_SESSION['logout_success_message'] = '<div class="container-fluid">
//   <div class="row">
//     <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
//       <div style="display: none;" class="alert alert-warning alert-dismissible show" role="alert">
//         <strong>Odjava uspešna! <i class="fas fa-check"></i></strong> Vidimo se opet uskoro ' . $leavingUN .
//   '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
//       </div>
//     </div>
//   </div>
// </div>';

$number1 = rand(1, 10);
$number2 = rand(1, 10);
$result = $number1 + $number2;
?>


<div id="main-page">


  <!-- LANDING -->
  <div id="landing">
    <div class="container-fluid">

      <?php printFormatedFlashMessage("register_success_message"); ?>
      <?php printFormatedFlashMessage("unauthorized_access"); ?>
      <?php printFormatedFlashMessage("contact_form_success"); ?>
      <?php printFormatedFlashMessage("login_success_message"); ?>

      <div class="row text-center text-white mt-5 kursevi-matematike-nisko">
        <h1 class="kursevi-matematike-za-osnovce mb-0">KURSEVI IZ MATEMATIKE <br class="smallonly d-none"> PO TVOJOJ MERI </h1>
        <p class="priprema">PRIPREMA ZA MALU MATURU</p>
        <p class="priprema">PRIPREMA ZA <br> MALU MATURU</p>
      </div>

      <div class="second-row row">
        <div class="col-md-12 col-lg-6">
          <div class="left-box text-white">
            <div class="wrapper text-center d-inline-block">
              <h2 class="mb-5 svestotitreba">Sve što ti treba na jednom mestu.</h2>
              <h2 class="mb-5 svestotitreba">Sve što ti treba <br> na jednom mestu.</h2>
              <p>Dostupno kad god poželiš</p>
              <p>Detaljno objašnjeno</p>
              <p class="lakoooo">Lako razumljivo</p>
              <a href="<?php echo BASE_URL; ?>kursevi" class="text-decoration-none">
                <button type="button" class="btn btn-primary buy-course-btn mt-4 scale-btn-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  POČNI UČENJE
                </button></a>
              <img src="public/images/plus.png" class="plus-img" width="250px" style="margin-right: 8rem; opacity: .3;" alt="">
              <img src="public/images/zmaj.png" class="zmaj-img" width="250px" style="opacity: .3;" alt="">
            </div>
          </div>
        </div>
        <!-- <div class="col d-inline-block">
      <img src="public/images/debela.png" width="50%" alt="">
    </div> -->
        <div class="col-md-12 col-lg-6">
          <div class="right-box text-end">
            <video id="home-video" class="video" width="900px" height="auto" controls disablepictureinpicture oncontextmenu="return false;" controlsList="nodownload">
              <source src="<?php echo BASE_URL; ?>videos/djole1.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- LANDING -->



  <!-- O BRENDU -->
  <div id="o-brendu">
    <h1 class="text-white">Matematika više neće da ti bude problem :)</h1>
    <p class="text-white razumevanje"><em>"Razumevanje matematike je prvi korak do znanja i odlične ocene"</em></p>

  <!-- FIVE DIVS -->
  <div id="five-divs" style="margin-top: 7rem;">
    <div class="container-fluid">
      <div class="row text-center">
        <img src="public/images/za_kurseve2.png" alt="">
      </div>
    </div>
  </div>
  <!-- FIVE DIVS -->

    <div class="container-fluid zastosamosnovao" style="margin-top: 5rem;">
      <div class="row">
        <div class="col-md-12 col-lg-6">
          <h2 class="text-white"><span class="color-yellow fw-bold">ZAŠTO</span> "TataMata"?</h2>
          <hr>
          <p class="text-white text-justify volim-verujem">
            <strong class="text-white">Volim</strong> da pomognem drugoj
            osobi
            ukoliko mogu i znam kako. <br>
            <strong class="text-white">Verujem</strong> da matematiku može <strong class="text-white">svako da
              nauči,</strong>
            ako se stvari objasne na pravi način. <br>
            <strong class="text-white">Moj san</strong> je da omogućim učenicima sa naših prostora
            da imaju kvalitetnu i jasnu nastavu
            koja će im doneti dugoročno znanje i odlične rezultate.
          </p>

          <h2 class="text-white mt-5"><span class="color-yellow fw-bold">KAKO</span> ću pokušati ovo da ostvarim?</h2>
          <hr>
          <p class="text-white text-justify volim-verujem">
            Pravljenjem video kurseva koji su sveobuhvatni, laki za razumevanje i koji testiraju šta ste naučili. <br>
			Cilj mi je da svaku lekciju naučite sa <strong class="text-white">razumevanjem.</strong> <br>
            Zbog toga mi je <strong class="text-white">kvalitet</strong> nastave na prvom mestu. <br>
            Kvalitetna nastava vama daje čvrsto znanje i odlične ocene, <br>
            a meni dobar ugled i više učenika. <br>
            Tako da svi pobeđujemo.
          </p>

          <!-- <p class="text-white text-justify volim-verujem mt-5">
            Zahvaljujući višegodišnjem iskustvu i <br>
            uspešnom individualnom radu sa preko 50 učenika, <br>
            znam šta vas najviše buni i na šta posebno morate <br>
            da obratite pažnju prilikom učenja. <br>
            Naravno, uvek sam tu i za sva vaša dodatna pitanja, <br>
            ukoliko ih budete imali.
          </p>-->

        </div>
        <div class="col-md-12 col-lg-6 text-center">
          <img class="lepi-djolo" src="public/images/lepidjolo.png" alt=""> <br>
		  <p class="text-white text-justify mt-2" > Đole - TataMata </p>
        </div>
      </div>

      <div class="mt-4 row text-white text-center counter-div">

        <div class="col">	<!-- ISKUSTVO -->
		<img class="imgCenter" src="public/images/iskustvo.png" alt=""> <br>
		
		Preko 
		  <span class="scroll-counter mt-5" data-counter-time="2000"><?php echo $brGod=getAge('2015-09-15')?></span> godina<br>
          radnog iskustva
				  
        </div>
        <div class="col">	<!-- KORISNICI -->
          <img class="imgCenter" src="public/images/srce2.png" alt=""> <br>
		  
		  Više od			
		  <span class="scroll-counter mt-5" data-counter-time="2000">90</span> <br>
          zadovoljnih učenika
		  

			

        </div>
        <div class="col">	<!-- KURSEVI-->
		 <img class="imgCenter" src="public/images/smajli.png" alt=""> <br>
          Čak
		  <span class="scroll-counter" data-counter-time="2000">87</span>%
          osmaka <br>je upisalo željenu školu
        </div>
      </div>

    </div>

  </div>
  <!-- O BRENDU -->


</div>

<?php if ($currentPage == '/index.php') { ?>
  </div>
<?php } ?>

<section id="usluge">

</section>

<div id="triangle1">
  <div id="triangle-bottomleft"></div>
  <div id="triangle-bottomright"></div>
</div>
<!-- USLUGE -->
<div id="usluge-second">
  <h1 class="pt-5 mb-5">USLUGE</h1>
  <div class="container">
    <div class="row text-center">
      <div class="col-md-4 usluge-col">
        <a class="text-decoration-none" href="<?php echo BASE_URL; ?>kursevi">
          <img class="icon" src="public/images/usluge/kursevi.svg" alt="">
          <p class="mt-3 usluga-title kursevik">Kursevi</p>
        </a>
        <!-- <div class="tooltip-usluge first">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_kursevi-01.png" alt="">
        </div> -->
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-sm-0">
        <a href="<?php echo BASE_URL; ?>priprema-za-prijemni" class="text-decoration-none"><img class="icon priprema-prijemni" style="height: 170px; margin-top: -25px;" src="public/images/usluge/malamatura.svg" alt=""></a>
        <a href="<?php echo BASE_URL; ?>priprema-za-prijemni" class="text-decoration-none">
          <p class="usluga-title pzp">Priprema za prijemni</p>
        </a>
        <!-- <div class="tooltip-usluge second">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_prijemni-01.png" alt="">
        </div> -->
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-sm-0">
        <a href="<?php echo BASE_URL; ?>konsultacije" class="text-decoration-none"><img class="icon konsultacije" style="height: 150px; margin-top: -10px;" src="<?php echo BASE_URL; ?>public/images/usluge/konsultacije.svg" alt="A"></a>
        <a href="<?php echo BASE_URL; ?>konsultacije" class="text-decoration-none">
          <p class="mt-2 usluga-title konsultz">Konsultacije</p>
        </a>
        <!-- <div class="tooltip-usluge third">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_konsultacije-01.png" alt="">
        </div> -->
      </div>
    </div>
    <div class="row mt-sm-5 text-center">
      <div class="col-md-4 usluge-col mt-3 mt-sm-0">
        <a href="<?php echo BASE_URL; ?>priprema-za-pismene-i-kontrolne" class="text-decoration-none"><img class="icon" src="public/images/usluge/pripremazapismeni.svg" alt=""></a>
        <a href="<?php echo BASE_URL; ?>priprema-za-pismene-i-kontrolne" class="text-decoration-none">
          <p class="mt-3 usluga-title pzpik">Priprema za <br> pismene i kontrolne</p>
        </a>
        <!-- <div class="tooltip-usluge fourth">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_pismenikontrolni-01.png" alt="">
        </div> -->
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-sm-0">
        <a href="<?php echo BASE_URL; ?>individualni-casovi" class="text-decoration-none"><img class="icon individualni" style="height: 150px;" src="public/images/usluge/individualnicasovi.svg" alt=""></a>
        <a href="<?php echo BASE_URL; ?>individualni-casovi" class="text-decoration-none">
          <p class="mt-3 usluga-title kursevik">Individualni časovi</p>
        </a>
        <!-- <div class="tooltip-usluge fifth">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_individualni-01.png" alt="">
        </div> -->
      </div>
      <div class="col-md-4 usluge-col mt-3 mt-sm-0">
        <a href="<?php echo BASE_URL; ?>grupni-casovi" class="text-decoration-none"><img class="icon grupni" style="height: 150px;" src="public/images/usluge/grupnicasovi.svg" alt=""></a>
        <a href="<?php echo BASE_URL; ?>grupni-casovi" class="text-decoration-none">
          <p class="mt-3 usluga-title grupnicass">Grupni časovi</p>
        </a>
        <!-- <div class="tooltip-usluge sixth">
          <img style="width: 450px; height: auto;" src="public/images/usluge/opis_usluge_grupe-01.png" alt="">
        </div> -->
      </div>
    </div>
  </div>
</div>
<div id="triangle2">
  <div id="triangle-bottomleft2"></div>
  <div id="triangle-bottomright2"></div>
</div>
<!-- USLUGE -->

<!-- TESTIMONIALS -->
<section id="testimonials">
  <div class="demoT">
    <div class="container" data-aos="zoom-in" data-aos-duration="700" data-aos-easing="linear">
      <h1 class="text-white">REKLI SU O MENI</h1>
      <div class="row">
        <div class="col-md-12">
          <div id="testimonial-slider" class="owl-carousel">

            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  Zahvaljujući pripremama za prijemni prošle godine moje dete je upisalo
                  željenu školu (gimnaziju). Svima toplo preporučujem a mi nastavljamo saradnju i u srednjoj školi.
                </p>
              </div>
              <h3 class="title">Jelena, Miloševa mama</h3>
              <!-- <span class="post"><a href="https://www.chess-boost.com/" target="_blank">Chess Boost <i class="fas fa-link"></i></a></span> -->
            </div>

            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  Sve preporuke za rad i saradnju sa ovim mladim čovekom. Ima veoma pristupačan
                  rad sa decom i iz njih izvuče najbolje...zahvaljući njihovom timskom radu
                  postigli smo super razultate na prijemnom ispitu. Prvo zahvalnost a onda i sve pohvale i preporuke!
                </p>
              </div>
              <h3 class="title">Rada, Nikolinina mama</h3>
              <!-- <span class="post"><a href="https://wolt.com/" target="_blank">Wolt <i class="fas fa-link"></i></a></span> -->
            </div>

            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  Veoma pametna i sposobna osoba spremna da podeli i uspešno prenese svoje
                  znanje na druge. Strpljiv i susretljiv, bez predrasuda. Osoba sa karakterom,
                  dosledan i dovoljno strog, a opet drugarski nastrojen, u nameri da nekoga nešto
                  nauči. Od kad radi sa mojom ćerkom matematika za nju više nije toliko strašna i
                  nerazumljiva. Svaka preporuka za dalju saradnju.
                </p>
              </div>
              <h3 class="title">Jelena, Tarina mama</h3>
              <!-- <span class="post"><a href="https://www.youtube.com/channel/UCue0aEGSSYYAahcpOixKkfg" target="_blank">
                  TheTricky10 <i class="fas fa-link"></i></a></span> -->
            </div>


            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  Napokon da pronadjem nekoga ko ne komplikuje stvari. Sve si objasnio lepo,kratko i jednostavno. Puno ti hvala!
                </p>
              </div>
              <h3 class="title">Andrej, učenik <br>
                YouTube komentar </h3>
              <!-- <span class="post"><a href="https://www.instagram.com/itsksenija/?igshid=ddhk01kzasl6" target="_blank">20 Minuta Sa Ksenijom <i class="fas fa-link"></i></a></span> -->
            </div>

            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  Brate mili, sutra imam test, više sam shvatio iz tvog klipa nego od privatnih časova,
                  jer ti nekako pričas našim jezikom, stvarno mi je žao što nemam nastavnika poput tebe.
                  Svaka čast, sutra imam test i mnogo sam sada siguran u sebe za bolju ocenu. LP samo jako.
                </p>
              </div>
              <h3 class="title">Ilija, učenik <br>
                YouTube komentar</h3>
              <!-- <span class="post"><a href="https://uvekotvoreno.com/" target="_blank">Uvek Otvoreno <i class="fas fa-link"></i></a></span> -->
            </div>


            <!-- <div class="testimonial">
                <div class="testimonial-content">
                  <div class="testimonial-icon">
                    <i class="fa fa-quote-left"></i>
                  </div>
                  <p class="description">
                    Bespotrebno je unajmljivati preskupe studije i agencije koje će vam odrađivati reklame za ogromne
                    svote novca. Sa ovim momcima smo otpočeli saradnju po preporuci i prezadovoljni smo kvalitetom
                    usluge i cenom. Preporučili bismo svima pixelate!
                  </p>
                </div>
                <h3 class="title">Nikola Zubić</h3>
                <span class="post">Sales Expert</a></span>
              </div> -->

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- TESTIMONIALS -->

<!-- FAQ -->
<section id="faq">
  <div class="container" data-aos-offset="150" data-aos="zoom-in" data-aos-duration="700" data-aos-easing="linear">
    <h1 class="text-white text-center fw-bold mb-3">NAJČEŠĆE POSTAVLJENA PITANJA</h1>
    <div id="faq-categories" class="d-flex text-white justify-content-center">
      <p class="faq-category active fw-bold" data-categorie-name="kat1">SAJT</p>
      <p class="faq-category" data-categorie-name="kat2">KURSEVI</p>
      <p class="faq-category" data-categorie-name="kat3">NALOG</p>
    </div>
    <div class="row">
      <div class="col-xl-12 offset-xl-0 col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-12 offset-sm-0 col-10 offset-1">


        <div class="accordion accordion-flush text-white" id="accordion-faq-1" data-categorie-name="kat1">

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-1" aria-expanded="false" aria-controls="faq-1-collapse-1">
                #1. Kako da koristim sajt tatamata.rs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-1-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">- Na vrhu ove strane imaš video uputstvo gde je sve detaljno objašnjeno.</div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->



          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-4" aria-expanded="false" aria-controls="faq-1-collapse-4">
                #2. Sa koliko uređaja mogu da pristupim sajtu?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-1-collapse-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">
                - Možeš da pristupiš sajtu sa najviše 2 različita uređaja. (npr. laptop i telefon)
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->



          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-6" aria-expanded="false" aria-controls="faq-1-collapse-6">
                #3. Da li mogu da promenim uređaj preko kog ulazim na sajt, ukoliko ga više ne koristim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-1-collapse-6" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">
                - Možeš, ali obavezno se prvo javi putem forme!
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-2" aria-expanded="false" aria-controls="faq-1-collapse-2">
                #4. Da li je moguće gledati klipove preko mobilnog uređaja?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-1-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-1">
              <div class="accordion-body">
                - Moguće je. Samo prilikom gledanja klipova omogući rotaciju na telefonu.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

        </div>

        <!-- KAT 2 -->
        <div class="accordion accordion-flush text-white" id="accordion-faq-2" data-categorie-name="kat2" style="display: none;">

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-1" aria-expanded="false" aria-controls="faq-2-collapse-1">
                #1. Kako mogu da kupim željeni kurs?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                - Odeš na stranicu "KURSEVI", izabereš kurs koji želiš da slušaš i klikneš dugme "KUPI KURS". Tada se
                otvara stranica sa primerom uplatnice.
                Potrebno je da popuniš uplatnicu prema uputstvima sa stranice i izvršiš uplatu u pošti ili banci.
                Takođe, uplata se može izvršiti i putem elektronskog ili mobilnog bankarstva.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-2" aria-expanded="false" aria-controls="faq-2-collapse-2">
                #2. Kada ću dobiti pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                - Čim uplata bude evidentirana, dobijaš pristup kursu.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-3" aria-expanded="false" aria-controls="faq-2-collapse-3">
                #3. Da li ću moći da razumem kurs ukoliko nemam predznanje iz te oblasti?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                -Naravno! Kursevi su prilagođeni svim učenicima nezavisno od njihovog trenutnog nivoa znanja.
                Bilo da se prvi put susrećeš sa nekom lekcijom koja ti je potpuno nepoznata ili imaš neko predznanje,
                ovi kursevi će ti pomoći da savladaš i razumeš sve tipove zadataka iz određene oblasti.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-4" aria-expanded="false" aria-controls="faq-2-collapse-4">
                #4. Koliko dugo imam pristup kursevima?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-4" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                - Imaš pristup godinu dana od datuma kupovine.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-5" aria-expanded="false" aria-controls="faq-2-collapse-5">
                #5. Kako da se prijavim za pripremnu nastavu za malu maturu?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-5" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                - Prijavljuješ se tako što popuniš formu koja se nalazi na dnu ove strane ili u DM na instagramu
                @tatamata.casovi
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2-collapse-6" aria-expanded="false" aria-controls="faq-2-collapse-6">
                #6. Kako da se prijavim za neku od drugih usluga (individualni časovi, grupni časovi, priprema za
                pismene i kontrolne zadatke)?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-2-collapse-6" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-2">
              <div class="accordion-body">
                - Prijavljuješ se tako što popuniš formu koja se nalazi na dnu ove strane ili u DM na instagramu
                @tatamata.casovi
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

        </div>

        <!-- KAT 3 -->
        <div class="accordion accordion-flush text-white" id="accordion-faq-3" data-categorie-name="kat3" style="display: none;">

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-collapse-1" aria-expanded="false" aria-controls="faq-3-collapse-1">
                #1. Kako da znam da li treba da se registrujem ili prijavim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-3-collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">
                - Ako si nov na sajtu i nemaš nalog potrebno je da se REGISTRUJEŠ. (ovo se radi samo jedanput)
                - Kada izvršiš registraciju, svaki sledeći put kada uđeš na sajt treba da se PRIJAVIŠ. (ovo radiš svaki
                sledeći put)
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3-collapse-2" aria-expanded="false" aria-controls="faq-3-collapse-2">
                #2. Zaboravio/la šifru i ne mogu da se ulogujem. Šta da radim?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-3-collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">
                - Na stranici "PRIJAVI SE" klikni na tekst "Zaboravili ste šifru". Nakon toga unesi tvoj email i klikni
                dugme "POTVRDI".
                - Ubrzo će ti stići mejl za promenu šifre. Uđi na link iz email-a i unesi novu šifru u oba polja. Klikom
                na dugme "POTVRDI" šifra će biti promenjena.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

          <!-- FAQ QUESTION START -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1-collapse-3" aria-expanded="false" aria-controls="faq-1-collapse-3">
                #3. Da li mogu da delim nalog sa još nekom osobom?
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </button>
            </h2>
            <div id="faq-1-collapse-3" class="accordion-collapse collapse" data-bs-parent="#accordion-faq-3">
              <div class="accordion-body">
                - Ne. Svaki nalog je namenjen isključivo za jednu osobu. Ukoliko se primeti da se nalog deli sa drugim
                osobama, pristup kupljenim kursevima će biti onemogućen.
              </div>
            </div>
          </div>
          <!-- FAQ QUESTION END -->

        </div>

      </div>
    </div>
</section>
<!-- FAQ -->


<!-- -------- KONTAKT ---------- -->
<section id="kontakt" class="contact mb-5">
  <div class="container">
    <div class="row justify-content-center">
      <h1 class="mb-1 text-center fw-bold text-white">KONTAKT</h1>

      <div class="col-lg-8 col-md-8 col-sm-10 col-11 g-0 gx-sm-5">
        <div class="login-form-container">


          <div class="row">
            <div class="col">
              <form enctype="multipart/form-data" action="contact-form-logic.php" id="contact-form" method="POST" onsubmit="return validateForm()">

                <?php if (Database::getInstance()->isUserLoggedIn()) { ?>

                  <div class="mb-4">
                    <label class="form-label"> <i class="far fa-user"></i>Ime i prezime</label>
                    <input id="contact-name" readonly name="firstname" type="text" class="form-control" value="<?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>">
                  </div>

                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                    <input id="contact-email" readonly name="email" type="email" class="form-control" placeholder="Unesite email" value="<?php echo $_SESSION['user']->email ?? ""; ?>">
                  </div>

                <?php } else { ?>

                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-user"></i> Ime i prezime</label>
                    <input id="contact-name" name="firstname" type="text" class="form-control <?php if (isset($errors['firstname'])) echo 'is-invalid';
                                                                                              else if (isset($firstname)) echo 'is-valid'; ?>" placeholder="Tvoje ime i prezime" value="<?php echo $firstname ?? ""; ?>">
                    <p id="name-error" class="invalid-feedback mb-0" style="display: none;">Molimo unesite ime.</p>
                  </div>

                  <div class="mb-4">
                    <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                    <input id="contact-email" name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                                            else if (isset($email)) echo 'is-valid'; ?>" placeholder="Tvoj email" value="<?php echo $email ?? ""; ?>">
                    <p id="email-error" class="invalid-feedback mb-0" style="display: none;">Email format nije validan.</p>
                  </div>

                <?php } ?>

                <div class="mb-4">
                  <label class="form-label"><i class="far fa-comment-dots"></i> Poruka</label>
                  <textarea id="contact-message" name="message" class="form-control" placeholder="Unesi poruku" style="height: 100px"></textarea>
                  <p id="message-error" class="invalid-feedback mb-0" style="display: none;">Potrebno je uneti bar 20 karaktera.</p>
                </div>

                <div class="mb-4">
                  <label class="form-label"><i class="fas fa-paperclip"></i> Dodajte fajlove </label>
                  <p>(jpg, jpeg, png, jfif, pdf) MAX 5MB</p>
                  <input id="contact-files" accept="image/*,application/pdf" class="form-control" type="file" multiple name="files[]">
                </div>

                <div class="mb-4">
                  <label class="form-label"><i class="fas fa-calculator"></i> Koliko je <?php echo $number1 . " + " . $number2 ?></label>
                  <input id="math-input" name="math" type="number" class="form-control <?php if (isset($errors['math'])) echo 'is-invalid';
                                                                                        else if (isset($math)) echo 'is-valid'; ?>" placeholder="Unesi rezultat">
                  <p id="math-error" class="invalid-feedback mb-0" style="display: none;">Rezultat nije tačan.</p>
                </div>

                <input type="hidden" name="result" value="<?php echo $result ?>">

                <button id="contact-send-btn" name="contact" type="submit" class="register-btn btn btn-primary scale-btn-2">POŠALJI <i class="fas fa-check"></i></button>
              </form>
            </div>
          </div>




        </div>

      </div>

    </div>
  </div>
</section>
<!-- -------- KONTAKT ---------- -->


</div>


<?php include_once 'includes/plyr_footer.php'; ?>
<script>
  let element = document.querySelector(`[data-video-index="${counterPlyr++}"]`);
  new Plyr('#home-video', {
    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'airplay', 'fullscreen'],
    settings: ['speed', 'quality'],
    quality: {
      default: 576,
      options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240]
    },
  });
</script>
<?php include_once 'includes/footer.php'; ?>