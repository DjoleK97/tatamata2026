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

// SCROLL TO TOP =========================================================================================================

let scrollToTopBtn = document.getElementById('scroll-to-top');
let navbarContainer = document.querySelector('.navbar-container-custom');
let navbar = document.querySelector('.navbar');

document.addEventListener("scroll", (event) => {
  let offset = pageYOffset;

  if (offset > 300) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }

  // NAVBAR =========================================================================================================

  if (offset > 35) {
    if (navbarContainer) {
      navbarContainer.classList.add('fixed-navbar-help');
      navbarContainer.style.paddingTop = "0";
      navbarContainer.style.backgroundColor = "#000a35";
    }
    // navbar.style.width = "90%";
    // navbar.style.margin = "0 auto";
  } else {
    if (navbarContainer) {
      navbarContainer.classList.remove('fixed-navbar-help');
      // navbar.style.width = "100%";
      if (document.width > 576) {
        navbarContainer.style.paddingTop = "30px";
      } else {
        navbarContainer.style.paddingTop = "20px";
      }
      if (!navbarCollapse.classList.contains('show')) {
        navbarContainer.style.backgroundColor = "transparent";
      }
    }
  }

});

scrollToTopBtn.addEventListener('click', () => {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
});

// PLAYING CLIPS ======================================================================================================

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

      if (videoContaienrSmallForSelectedClip != lastPlayedClip) { // Ako je razlicit od poslednjeg playovanog pusti ga i zaustavi poslednji playovn
        let lastChapterID = lastPlayedClip.dataset.chapterId;
        let lastClipID = lastPlayedClip.dataset.clipId;

        let videoToStop = document.querySelector(`video[data-chapter-id="${lastChapterID}"][data-clip-id="${lastClipID}"]`);

        if (videoToStop && !videoToStop.paused) {
          videoToStop.pause();
        }

        // Change clip description
        changeDescriptions(lastChapterID, lastClipID, chapterID, clipID);
        // Change clip description

        // Close old clip and display new
        videoContaienrSmallForSelectedClip.style.display = "block";
        lastPlayedClip.style.display = "none";
        lastPlayedClip = videoContaienrSmallForSelectedClip;
        // Close old clip and display new

        // Change buttons availability if clip is first or last
        let index = [...videoContainerSmalls].indexOf(lastPlayedClip);

        if (videoContainerSmalls[(index + 1)] == undefined) { // If this is the last clip disable next btn
          nextBtn.disabled = true;
        } else {
          nextBtn.disabled = false;
        }

        if (videoContainerSmalls[(index - 1)] == undefined) { // If this is the first clip disable prev btn
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

// PREV AND NEXT CLIP ==========================================================================================

let prevBtn = document.querySelector(".prev-btn");
let nextBtn = document.querySelector(".next-btn");

// let chapterNameZZ = document.querySelector('.chapternamezz');
// let clipNameZZ = document.querySelector('.clipnamezz');

if (prevBtn) {
  prevBtn.addEventListener('click', () => {
    nextBtn.disabled = false;

    let currentChapter = lastPlayedClip.dataset.chapterId;
    let currentClip = lastPlayedClip.dataset.clipId;

    let index = [...videoContainerSmalls].indexOf(lastPlayedClip);
    let previousVideo = videoContainerSmalls[--index]; // Its undefined if clip is first.

    if (previousVideo != undefined) {
      let videoToStop = document.querySelector(`video[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);

      if (videoToStop && !videoToStop.paused) {
        videoToStop.pause();
      }

      let chapterID = previousVideo.dataset.chapterId;
      let clipID = previousVideo.dataset.clipId;
      changeDescriptions(currentChapter, currentClip, chapterID, clipID);

      // Close old clip and display new
      previousVideo.style.display = "block";
      lastPlayedClip.style.display = "none";
      lastPlayedClip = previousVideo;
      // Close old clip and display new

      // Change button availability
      if (videoContainerSmalls[--index] == undefined) { // If this is the first clip disable prev btn
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
    let nextVideo = videoContainerSmalls[++index]; // Its undefined if clip is last.

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

      if (videoContainerSmalls[++index] == undefined) { // If this is the last clip disable next btn
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
    let bsCollapseOLD = new bootstrap.Collapse(collapseDivOLD, {
      toggle: false
    });

    let bsCollapseNEW = new bootstrap.Collapse(collapseDivNEW, {
      toggle: false
    });

    bsCollapseOLD.hide();
    bsCollapseNEW.show();

    let bsCollapseOLDSmall = new bootstrap.Collapse(collapseDivOLDSmall, {
      toggle: false
    });

    let bsCollapseNEWSmall = new bootstrap.Collapse(collapseDivNEWSmall, {
      toggle: false
    });

    bsCollapseOLDSmall.hide();
    bsCollapseNEWSmall.show();
  }

  // Mora ovako 2x jer imamo 2 diva jedan za mobilne jedan za desktop racunare
  let pToGiveActiveClass = document.querySelectorAll(`p.clip-link[data-chapter-id="${newChapter}"][data-clip-id="${newClip}"]`);
  let pToRemoveActiveClass = document.querySelectorAll(`p.clip-link[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);

  pToGiveActiveClass.forEach(pp => {
    pp.classList.add('active-clip');
  });
  pToRemoveActiveClass.forEach(pp => {
    pp.classList.remove('active-clip');
  });

  let clipDescriptionToClose = document.querySelector(`p.clip-description[data-chapter-id="${currentChapter}"][data-clip-id="${currentClip}"]`);
  let clipDescriptionToShow = document.querySelector(`p.clip-description[data-chapter-id="${newChapter}"][data-clip-id="${newClip}"]`);

  clipNameQuestion.value = newClip;

  clipDescriptionToClose.style.display = "none";
  clipDescriptionToShow.style.display = "block";
}


// ALERTS ======================================================================================================

document.addEventListener("DOMContentLoaded", (event) => {
  // Your code to run since DOM is loaded and ready

  $(".alert").fadeIn(1000);
  setTimeout(() => {
    $(".alert").fadeOut(1000);
  }, 6000);

});

// LOGIN FORM MULTIPLE REQUESTS ======================================================================================================

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

// USLUGE ======================================================================================================

// let uslugeIcons = document.querySelectorAll("#usluge-second .icon");
// let uslugeTitles = document.querySelectorAll("#usluge-second .usluga-title");
// let tooltipDivs = document.querySelectorAll('#usluge-second .tooltip-usluge');

// uslugeIcons.forEach((uslugeIcon, index) => {
//   uslugeIcon.addEventListener('mouseover', () => {
//     // $(tooltipDivs[index]).fadeIn(800);
//     tooltipDivs[index].style.display = 'block';
//   });

//   uslugeIcon.addEventListener('mouseout', () => {
//     // $(tooltipDivs[index]).fadeOut(200);
//     tooltipDivs[index].style.display = 'none';
//   });
// });

// uslugeTitles.forEach((uslugeTitle, index) => {
//   uslugeTitle.addEventListener('mouseover', () => {
//     // $(tooltipDivs[index]).fadeIn(800);
//     tooltipDivs[index].style.display = 'block';
//   });

//   uslugeTitle.addEventListener('mouseout', () => {
//     // $(tooltipDivs[index]).fadeOut(200);
//     tooltipDivs[index].style.display = 'none';
//   });
// });


// FAQ =====================================================================================================================================

let faqCategories = document.querySelectorAll("#faq .faq-category");
let accordionDivs = document.querySelectorAll("#faq .accordion");
let lastSelectedAccordion = accordionDivs[0];
let lastActiveP = faqCategories[0];

faqCategories.forEach(faqCategorie => {
  faqCategorie.addEventListener('click', () => {
    let categorieName = faqCategorie.dataset.categorieName;
    let element = document.querySelector(`div.accordion[data-categorie-name="${categorieName}"]`);
    if (element != lastSelectedAccordion) {
      faqCategorie.classList.add('active');
      lastActiveP.classList.remove('active');
      lastActiveP = faqCategorie;

      element.style.display = "block";
      lastSelectedAccordion.style.display = "none";
      lastSelectedAccordion = element;
    }
  })
});

// CLOSE NAVBAR =====================================================================================================================================

let closeNavbarLinks = document.querySelectorAll('.close-nav-link');
let navbarCollapse = document.querySelector('.navbar-collapse');

if (navbarCollapse) {
  navbarCollapse.addEventListener('show.bs.collapse', function (event) {
    navbarContainer.style.backgroundColor = "#000a35";
  });

  navbarCollapse.addEventListener('hide.bs.collapse', function (event) {
    if (!navbarContainer.classList.contains('fixed-navbar-help')) {
      navbarContainer.style.backgroundColor = "transparent";
    }
  });
}

closeNavbarLinks.forEach(closeNavbarLink => {
  closeNavbarLink.addEventListener('click', () => {
    if (navbarCollapse.classList.contains('show')) {
      navbarCollapse.classList.remove('show');
    }
  });
});

// CONTACT FORM =====================================================================================================================================

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
    if (nameError) {
      nameError.style.display = "block";
    }
    hasError = true;
  } else {
    nameInput.classList.remove('is-invalid');
    if (nameError) {
      nameError.style.display = "none";
    }
  }

  if (cleanInput(emailInput.value).length < 5) {
    emailInput.classList.add('is-invalid');
    if (emailError) {
      emailError.style.display = "block";
    }
    hasError = true;
  } else {
    emailInput.classList.remove('is-invalid');
    if (emailError) {
      emailError.style.display = "none";
    }
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
    if (mathError) {
      mathError.style.display = "block";
    }
    hasError = true;
  } else {
    mathInput.classList.remove('is-invalid');
    mathInput.classList.add('is-valid');
    if (mathError) {
      mathError.style.display = "none";
    }
  }

  return !hasError;
}

function cleanInput(input) {
  // Create a new div element
  let temporalDivElement = document.createElement("div");
  // Set the HTML content with the providen
  temporalDivElement.innerHTML = input;

  // Retrieve the text property of the element (cross-browser support)
  return temporalDivElement.textContent || temporalDivElement.innerText || "";
}

function validateEmail(email) {
  const re = /^(([^&lt;&gt;()\[\]\\.,;:\s@"]+(\.[^&lt;&gt;()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  console.log(email);
  console.log(String(email).toLowerCase());
  console.log(re.test(String(email).toLowerCase()));
  return re.test(String(email).toLowerCase());
}

// TESTIMONIALS =====================================================================================================================================

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

// KURSEVI ============================================================================================================================================

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

// if (srednjaSkolaDivs) {
//   srednjaSkolaDivs.forEach(srednjaSkolaDiv => {
//     srednjaSkolaDiv.style.display = "none";
//   });
// }

function showOsnovna() {
  osnovnaSkolaDivs.forEach(osnovnaSkolaDiv => {
    osnovnaSkolaDiv.style.display = "block";
  });
}

function showSrednja() {
  srednjaSkolaDivs.forEach(srednjaSkolaDiv => {
    srednjaSkolaDiv.style.display = "block";
  });
}

function showFakultet() {
  fakultetSkolaDivs.forEach(fakultetSkolaDiv => {
    fakultetSkolaDiv.style.display = "block";
  });
}

function hideOsnovna() {
  osnovnaSkolaDivs.forEach(osnovnaSkolaDiv => {
    osnovnaSkolaDiv.style.display = "none";
  });
}

function hideSrednja() {
  srednjaSkolaDivs.forEach(srednjaSkolaDiv => {
    srednjaSkolaDiv.style.display = "none";
  });
}

function hideFakultet() {
  fakultetSkolaDivs.forEach(fakultetSkolaDiv => {
    fakultetSkolaDiv.style.display = "none";
  });
}

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
      data: {
        pretragaKursevi: 1,
        searchText,
      },
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

// KURS ============================================================================================================================================

let pretragaInputKurses = document.querySelectorAll('.pretraga-input-kurs');
let searchResultsSmall = document.querySelector('.search-results-small');

if (pretragaInputKurses) {
  let url = window.location.href;     // Returns full URL (https://example.com/path/example.html)
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
        data: {
          pretragaKlipovi: 1,
          courseID,
          searchText,
        },
        success: (response) => {
          console.log(response);
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


// COUNTERS ===========================================================================================================================================


document.addEventListener("DOMContentLoaded", function () {
  // You can change this class to specify which elements are going to behave as counters.
  var elements = document.querySelectorAll(".scroll-counter")

  elements.forEach(function (item) {
    // Add new attributes to the elements with the '.scroll-counter' HTML class
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

  // Function to determine if an element is visible in the web page
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

  // Funciton that will get fired uppon scrolling
  var handleScroll = function handleScroll() {
    elements.forEach(function (item, id) {
      if (true === item.counterAlreadyFired) return
      if (!isElementVisible(item)) return
      item.updateCounter()
      item.counterAlreadyFired = true
    })
  }

  // Fire the function on scroll
  window.addEventListener("scroll", handleScroll)
});