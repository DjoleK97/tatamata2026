// ============================================================
// TataMata – Script.js (Redesign 2026)
// ============================================================

// DROPDOWN ARROW ROTATION
let loggedInAsDrowDown = document.querySelector('.nav-item.dropdown');
let arrowDown = document.querySelector('.logged-in-as svg');

if (loggedInAsDrowDown) {
  loggedInAsDrowDown.addEventListener('show.bs.dropdown', (event) => {
    arrowDown.style.transform = "rotate(90deg)";
  });

  loggedInAsDrowDown.addEventListener('hidden.bs.dropdown', (event) => {
    arrowDown.style.transform = "rotate(0deg)";
  });
}

// SCROLL TO TOP + NAVBAR SCROLL STATE ========================

let scrollToTopBtn = document.getElementById('scroll-to-top');
let navbarContainer = document.querySelector('.navbar-container-custom');

document.addEventListener("scroll", () => {
  let offset = window.pageYOffset || document.documentElement.scrollTop;

  // Scroll-to-top button
  if (scrollToTopBtn) {
    scrollToTopBtn.style.display = offset > 300 ? "block" : "none";
  }

  // Navbar scrolled state (CSS handles the styling via .scrolled class)
  if (navbarContainer) {
    if (offset > 35) {
      navbarContainer.classList.add('scrolled');
    } else {
      navbarContainer.classList.remove('scrolled');
    }
  }
});

if (scrollToTopBtn) {
  scrollToTopBtn.addEventListener('click', () => {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  });
}

// PLAYING CLIPS ==============================================

let videos = document.querySelectorAll("video");
let videoContainer = document.querySelector(".video-container");
let videoContainerSmalls = document.querySelectorAll(".video-container-small");
let clipNameQuestion = document.getElementById('clip-name-question');

let lastPlayedClip = videoContainerSmalls[0];

addEventsForSwitchingClips();

function addEventsForSwitchingClips() {
  let clipLinks = document.querySelectorAll(".clip-link");

  clipLinks.forEach(clipLink => {
    clipLink.addEventListener('click', (event) => {

      searchResults.style.display = "none";
      searchResultsSmall.style.display = "none";
      pretragaInputKurses[0].value = "";
      pretragaInputKurses[1].value = "";

      let chapterID = clipLink.dataset.chapterId;
      let clipID = clipLink.dataset.clipId;

      let videoContaienrSmallForSelectedClip = findVideoSmallContaienr(chapterID, clipID);

      if (videoContaienrSmallForSelectedClip != lastPlayedClip) {
        let lastChapterID = lastPlayedClip.dataset.chapterId;
        let lastClipID = lastPlayedClip.dataset.clipId;

        let videoToStop = document.querySelector(`video[data-chapter-id="${lastChapterID}"][data-clip-id="${lastClipID}"]`);

        if (videoToStop && !videoToStop.paused) {
          videoToStop.pause();
        }

        changeDescriptions(lastChapterID, lastClipID, chapterID, clipID);

        videoContaienrSmallForSelectedClip.style.display = "block";
        lastPlayedClip.style.display = "none";
        lastPlayedClip = videoContaienrSmallForSelectedClip;

        let index = [...videoContainerSmalls].indexOf(lastPlayedClip);

        if (videoContainerSmalls[(index + 1)] == undefined) {
          nextBtn.disabled = true;
        } else {
          nextBtn.disabled = false;
        }

        if (videoContainerSmalls[(index - 1)] == undefined) {
          prevBtn.disabled = true;
        } else {
          prevBtn.disabled = false;
        }
      }
    });
  });
}

function findVideoSmallContaienr(chapterID, clipID) {
  for (let i = 0; i < videoContainerSmalls.length; i++) {
    let chapterID2 = videoContainerSmalls[i].dataset.chapterId;
    let clipID2 = videoContainerSmalls[i].dataset.clipId;

    if (chapterID == chapterID2 && clipID == clipID2) {
      return videoContainerSmalls[i];
    }
  }

  return undefined;
}

// PREV AND NEXT CLIP =========================================

let prevBtn = document.querySelector(".prev-btn");
let nextBtn = document.querySelector(".next-btn");

if (prevBtn) {
  prevBtn.addEventListener('click', () => {
    nextBtn.disabled = false;

    let currentChapter = lastPlayedClip.dataset.chapterId;
    let currentClip = lastPlayedClip.dataset.clipId;

    let index = [...videoContainerSmalls].indexOf(lastPlayedClip);
    let previousVideo = videoContainerSmalls[--index];

    if (previousVideo != undefined) {
      let videoToStop = document.querySelector(`video[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);

      if (videoToStop && !videoToStop.paused) {
        videoToStop.pause();
      }

      let chapterID = previousVideo.dataset.chapterId;
      let clipID = previousVideo.dataset.clipId;
      changeDescriptions(currentChapter, currentClip, chapterID, clipID);

      previousVideo.style.display = "block";
      lastPlayedClip.style.display = "none";
      lastPlayedClip = previousVideo;

      if (videoContainerSmalls[--index] == undefined) {
        prevBtn.disabled = true;
      }
    }
  });
}

if (nextBtn) {
  nextBtn.addEventListener('click', () => {
    prevBtn.disabled = false;

    let currentChapter = lastPlayedClip.dataset.chapterId;
    let currentClip = lastPlayedClip.dataset.clipId;

    let index = [...videoContainerSmalls].indexOf(lastPlayedClip);
    let nextVideo = videoContainerSmalls[++index];

    if (nextVideo != undefined) {
      let videoToStop = document.querySelector(`video[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);

      if (videoToStop && !videoToStop.paused) {
        videoToStop.pause();
      }

      let chapterID = nextVideo.dataset.chapterId;
      let clipID = nextVideo.dataset.clipId;
      changeDescriptions(currentChapter, currentClip, chapterID, clipID);

      nextVideo.style.display = "block";
      lastPlayedClip.style.display = "none";
      lastPlayedClip = nextVideo;

      if (videoContainerSmalls[++index] == undefined) {
        nextBtn.disabled = true;
      }
    }
  });
}

function changeDescriptions(currentChapter, currentClip, newChapter, newClip) {
  let collapseDivOLD = document.getElementById(`collapseOne${currentChapter}`);
  let collapseDivOLDSmall = document.getElementById(`collapseOne${currentChapter}69`);
  let collapseDivNEW = document.getElementById(`collapseOne${newChapter}`);
  let collapseDivNEWSmall = document.getElementById(`collapseOne${newChapter}69`);

  if (currentChapter != newChapter) {
    let bsCollapseOLD = new bootstrap.Collapse(collapseDivOLD, { toggle: false });
    let bsCollapseNEW = new bootstrap.Collapse(collapseDivNEW, { toggle: false });
    bsCollapseOLD.hide();
    bsCollapseNEW.show();

    let bsCollapseOLDSmall = new bootstrap.Collapse(collapseDivOLDSmall, { toggle: false });
    let bsCollapseNEWSmall = new bootstrap.Collapse(collapseDivNEWSmall, { toggle: false });
    bsCollapseOLDSmall.hide();
    bsCollapseNEWSmall.show();
  }

  let pToGiveActiveClass = document.querySelectorAll(`p.clip-link[data-chapter-id="${newChapter}"][data-clip-id="${newClip}"]`);
  let pToRemoveActiveClass = document.querySelectorAll(`p.clip-link[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);

  pToGiveActiveClass.forEach(pp => { pp.classList.add('active-clip'); });
  pToRemoveActiveClass.forEach(pp => { pp.classList.remove('active-clip'); });

  let clipDescriptionToClose = document.querySelector(`p.clip-description[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);
  let clipDescriptionToShow = document.querySelector(`p.clip-description[data-chapter-id="${newChapter}"][data-clip-id="${newClip}"]`);

  clipNameQuestion.value = newClip;

  clipDescriptionToClose.style.display = "none";
  clipDescriptionToShow.style.display = "block";
}

// ALERTS =====================================================

document.addEventListener("DOMContentLoaded", (event) => {
  $(".alert").fadeIn(1000);
  setTimeout(() => {
    $(".alert").fadeOut(1000);
  }, 6000);
});

// LOGIN FORM MULTIPLE REQUESTS ===============================

let loginForm = document.getElementById('login-form');
let allowSubmit = true;

if (loginForm) {
  loginForm.onsubmit = function () {
    if (allowSubmit)
      allowSubmit = false;
    else
      return false;
  }
}

// CLOSE NAVBAR ON LINK CLICK =================================

let closeNavbarLinks = document.querySelectorAll('.close-nav-link');
let navbarCollapse = document.querySelector('.navbar-collapse');

closeNavbarLinks.forEach(closeNavbarLink => {
  closeNavbarLink.addEventListener('click', () => {
    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
      navbarCollapse.classList.remove('show');
    }
  });
});

// CONTACT FORM ===============================================

function validateForm() {
  const nameInput = document.getElementById('contact-name');
  const emailInput = document.getElementById('contact-email');
  const messageInput = document.getElementById('contact-message');
  const mathInput = document.getElementById('math-input');
  const result = document.querySelector(`[name="result"]`);

  const nameError = document.getElementById('name-error');
  const emailError = document.getElementById('email-error');
  const messageError = document.getElementById('message-error');
  const mathError = document.getElementById('math-error');

  let hasError = false;

  if (cleanInput(nameInput.value) == "") {
    nameInput.classList.add('is-invalid');
    if (nameError) { nameError.style.display = "block"; }
    hasError = true;
  } else {
    nameInput.classList.remove('is-invalid');
    if (nameError) { nameError.style.display = "none"; }
  }

  if (cleanInput(emailInput.value).length < 5) {
    emailInput.classList.add('is-invalid');
    if (emailError) { emailError.style.display = "block"; }
    hasError = true;
  } else {
    emailInput.classList.remove('is-invalid');
    if (emailError) { emailError.style.display = "none"; }
  }

  if (cleanInput(messageInput.value).length < 20) {
    messageInput.classList.add('is-invalid');
    messageError.style.display = "block";
    hasError = true;
  } else {
    messageInput.classList.remove('is-invalid');
    messageError.style.display = "none";
  }

  if (mathInput.value == "" || mathInput.value != result.value) {
    mathInput.classList.add('is-invalid');
    if (mathError) { mathError.style.display = "block"; }
    hasError = true;
  } else {
    mathInput.classList.remove('is-invalid');
    mathInput.classList.add('is-valid');
    if (mathError) { mathError.style.display = "none"; }
  }

  return !hasError;
}

function cleanInput(input) {
  let temporalDivElement = document.createElement("div");
  temporalDivElement.innerHTML = input;
  return temporalDivElement.textContent || temporalDivElement.innerText || "";
}

function validateEmail(email) {
  const re = /^(([^&lt;&gt;()\[\]\\.,;:\s@"]+(\.[^&lt;&gt;()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

// TESTIMONIALS ===============================================

$(document).ready(function () {
  $("#testimonial-slider").owlCarousel({
    items: 3,
    itemsDesktop: [1000, 3],
    itemsDesktopSmall: [991, 2],
    itemsTablet: [768, 2],
    itemsMobile: [575, 1],
    loop: true,
    autoPlay: 11000,
  });
});

// BANNER OWL CAROUSEL
$("#kursevi-area .owl-carousel").owlCarousel({
  items: 6,
});

// KURSEVI (FILTER) ===========================================

let osnovnaSkolaBtn = document.getElementById('osnovna-btn');
let srednjaSkolaBtn = document.getElementById('srednja-btn');
let fakultetSkolaBtn = document.getElementById('fakultet-btn');
let osnovnaSkolaDivs = document.querySelectorAll('.osnovnaskoladiv');
let srednjaSkolaDivs = document.querySelectorAll('.srednjaskoladiv');
let fakultetSkolaDivs = document.querySelectorAll('.fakultetskoladiv');
let currentSchool;
let pretragaInput = document.querySelector('.pretraga-input');
let searchResults = document.querySelector('.search-results');

if (osnovnaSkolaBtn || srednjaSkolaBtn || fakultetSkolaBtn) {
  if (osnovnaSkolaBtn.classList.contains('active')) {
    currentSchool = 1;
    hideSrednja();
    hideFakultet();
  } else if (srednjaSkolaBtn.classList.contains('active')) {
    currentSchool = 2;
    hideOsnovna();
    hideFakultet();
    showSrednja();
  } else {
    currentSchool = 3;
    hideOsnovna();
    hideSrednja();
    showFakultet();
  }
}

if (osnovnaSkolaBtn) {
  osnovnaSkolaBtn.addEventListener('click', () => {
    if (currentSchool != 1) {
      showOsnovna();
      hideSrednja();
      hideFakultet();
      osnovnaSkolaBtn.classList.add('active');
      srednjaSkolaBtn.classList.remove('active');
      fakultetSkolaBtn.classList.remove('active');
      currentSchool = 1;
    }
  });
}

if (srednjaSkolaBtn) {
  srednjaSkolaBtn.addEventListener('click', () => {
    if (currentSchool != 2) {
      hideOsnovna();
      hideFakultet();
      showSrednja();
      osnovnaSkolaBtn.classList.remove('active');
      fakultetSkolaBtn.classList.remove('active');
      srednjaSkolaBtn.classList.add('active');
      currentSchool = 2;
    }
  });
}

if (fakultetSkolaBtn) {
  fakultetSkolaBtn.addEventListener('click', () => {
    if (currentSchool != 3) {
      hideOsnovna();
      hideSrednja();
      showFakultet();
      osnovnaSkolaBtn.classList.remove('active');
      srednjaSkolaBtn.classList.remove('active');
      fakultetSkolaBtn.classList.add('active');
      currentSchool = 3;
    }
  });
}

function showOsnovna() { osnovnaSkolaDivs.forEach(d => { d.style.display = "block"; }); }
function showSrednja() { srednjaSkolaDivs.forEach(d => { d.style.display = "block"; }); }
function showFakultet() { fakultetSkolaDivs.forEach(d => { d.style.display = "block"; }); }
function hideOsnovna() { osnovnaSkolaDivs.forEach(d => { d.style.display = "none"; }); }
function hideSrednja() { srednjaSkolaDivs.forEach(d => { d.style.display = "none"; }); }
function hideFakultet() { fakultetSkolaDivs.forEach(d => { d.style.display = "none"; }); }

if (pretragaInput) {
  pretragaInput.addEventListener('keyup', (event) => {
    let searchText = pretragaInput.value;

    if (searchText == "") {
      searchResults.style.display = "none";
      return;
    }

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: { pretragaKursevi: 1, searchText },
      success: (response) => {
        let courses = JSON.parse(response);

        if (courses.length == 0) {
          searchResults.style.display = "none";
          return;
        }

        let html = "";

        courses.forEach(course => {
          html += `<a href="kurs/${course.id}" class="text-decoration-none" style="color: black;">
                    <div class="search-result-item d-flex">
                      <div class="course-img d-flex justify-content-center align-items-center">
                          ${course.live ? `<img class="scale-btn-2" style="width: 100px;" src="./public/images/courses/${course.img}" alt="Image error">` : `<img class="scale-btn-2" style="width: 100px;" src="./public/images/upripremi.png" alt="Image error">`}
                      </div>
                      <div class="course-name ms-2 d-flex align-items-center">
                          ${course.name}
                      </div>
                    </div>
                  </a>`;
        });

        searchResults.innerHTML = html;
        searchResults.style.display = "block";
      }
    });
  });
}

// KURS (CLIP SEARCH) =========================================

let pretragaInputKurses = document.querySelectorAll('.pretraga-input-kurs');
let searchResultsSmall = document.querySelector('.search-results-small');

if (pretragaInputKurses) {
  let url = window.location.href;
  let courseID = url.substring(url.lastIndexOf('/') + 1);

  pretragaInputKurses.forEach(pretragaInputKurs => {
    pretragaInputKurs.addEventListener('keyup', (event) => {
      let searchText = pretragaInputKurs.value;

      if (searchText == "") {
        searchResults.style.display = "none";
        searchResultsSmall.style.display = "none";
        return;
      }

      $.ajax({
        url: '../ajax.php',
        type: 'POST',
        data: { pretragaKlipovi: 1, courseID, searchText },
        success: (response) => {
          let clips = JSON.parse(response);

          if (clips.length == 0) {
            searchResults.style.display = "none";
            return;
          }

          let html = "";

          clips.forEach(clip => {
            html += `<div class="search-result-item">
                      <div class="course-name ms-2">
                        <p data-chapter-id="${clip.chapter_id}" data-clip-id="${clip.id}" class="m-0 card-text clip-link">- ${clip.name}</p>
                      </div>
                    </div>`;
          });

          searchResults.innerHTML = html;
          searchResultsSmall.innerHTML = html;
          searchResults.style.display = "block";
          searchResultsSmall.style.display = "block";
          searchResults.style.width = "100%";
          searchResultsSmall.style.width = "100%";
          addEventsForSwitchingClips();
        }
      });
    });
  });
}

// COUNTERS ===================================================

document.addEventListener("DOMContentLoaded", function () {
  var elements = document.querySelectorAll(".scroll-counter")

  elements.forEach(function (item) {
    item.counterAlreadyFired = false
    item.counterSpeed = item.getAttribute("data-counter-time") / 45
    item.counterTarget = +item.innerText
    item.counterCount = 0
    item.counterStep = item.counterTarget / item.counterSpeed

    item.updateCounter = function () {
      item.counterCount = item.counterCount + item.counterStep
      item.innerText = Math.ceil(item.counterCount)

      if (item.counterCount < item.counterTarget) {
        setTimeout(item.updateCounter, item.counterSpeed)
      } else {
        item.innerText = item.counterTarget
      }
    }
  })

  var isElementVisible = function isElementVisible(el) {
    var scroll = window.scrollY || window.pageYOffset
    var boundsTop = el.getBoundingClientRect().top + scroll
    var viewport = {
      top: scroll,
      bottom: scroll + window.innerHeight,
    }
    var bounds = {
      top: boundsTop,
      bottom: boundsTop + el.clientHeight,
    }
    return (
      (bounds.bottom >= viewport.top && bounds.bottom <= viewport.bottom) ||
      (bounds.top <= viewport.bottom && bounds.top >= viewport.top)
    )
  }

  var handleScroll = function handleScroll() {
    elements.forEach(function (item, id) {
      if (true === item.counterAlreadyFired) return
      if (!isElementVisible(item)) return
      item.updateCounter()
      item.counterAlreadyFired = true
    })
  }

  window.addEventListener("scroll", handleScroll)
});

// SCROLL REVEAL (Intersection Observer) ======================

document.addEventListener("DOMContentLoaded", function () {
  const animElements = document.querySelectorAll('.animiraj');

  if (animElements.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('vidljiv');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px'
  });

  animElements.forEach(el => observer.observe(el));
});
