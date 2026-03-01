'use strict';

/* ===== Enable Bootstrap Popover (on element  ====== */

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

/* ==== Enable Bootstrap Alert ====== */
var alertList = document.querySelectorAll('.alert')
alertList.forEach(function (alert) {
  new bootstrap.Alert(alert)
});


/* ===== Responsive Sidepanel ====== */
const sidePanelToggler = document.getElementById('sidepanel-toggler');
const sidePanel = document.getElementById('app-sidepanel');
const sidePanelDrop = document.getElementById('sidepanel-drop');
const sidePanelClose = document.getElementById('sidepanel-close');

window.addEventListener('load', function () {
  responsiveSidePanel();
});

window.addEventListener('resize', function () {
  responsiveSidePanel();
});


function responsiveSidePanel() {
  let w = window.innerWidth;
  if (w >= 1200) {
    // if larger 
    //console.log('larger');
    sidePanel.classList.remove('sidepanel-hidden');
    sidePanel.classList.add('sidepanel-visible');

  } else {
    // if smaller
    //console.log('smaller');
    sidePanel.classList.remove('sidepanel-visible');
    sidePanel.classList.add('sidepanel-hidden');
  }
};

sidePanelToggler.addEventListener('click', () => {
  if (sidePanel.classList.contains('sidepanel-visible')) {
    console.log('visible');
    sidePanel.classList.remove('sidepanel-visible');
    sidePanel.classList.add('sidepanel-hidden');

  } else {
    console.log('hidden');
    sidePanel.classList.remove('sidepanel-hidden');
    sidePanel.classList.add('sidepanel-visible');
  }
});



sidePanelClose.addEventListener('click', (e) => {
  e.preventDefault();
  sidePanelToggler.click();
});

sidePanelDrop.addEventListener('click', (e) => {
  sidePanelToggler.click();
});

let courseSelect = document.getElementById('course-select');
let chapterSelect = document.getElementById('chapter-select');

let courseSelectEdit = document.getElementById('course-select-edit');
let chapterSelectEdit = document.getElementById('chapter-select-edit');

let schoolTypeSelect = document.getElementById('school-type');
let gradesSelect = document.getElementById('grades-select');

let schoolTypeSelectsEdit = document.querySelectorAll(`select[name="school_type_id_edit"]`);

if (courseSelect) {
  courseSelect.addEventListener('change', loadChaptersForCourse);
  loadChaptersForCourse(undefined, courseSelect.value);
}

if (courseSelectEdit) {
  courseSelectEdit.addEventListener('change', loadChaptersForCourseEdit);
}

if (schoolTypeSelect) {
  schoolTypeSelect.addEventListener('change', loadGradesForSchoolType);
}
if (schoolTypeSelectsEdit) {
  schoolTypeSelectsEdit.forEach(schoolTypeSelectEdit => {
    schoolTypeSelectEdit.addEventListener('change', loadGradesForSchoolTypeEdit);
  });
}

function loadChaptersForCourse(event, id) {
  let courseID;
  if (event == undefined) {
    courseID = id;
  } else {
    courseID = event.target.value;
  }
  console.log(courseID);
  let xhr = new XMLHttpRequest();

  xhr.open('GET', 'ajax.php?course_id=' + courseID, true);

  xhr.onload = () => { // If we use lambda expression we can't use this.status
    if (xhr.status == 200) {
      let chapters = JSON.parse(xhr.responseText);
      console.log(chapters);
      chapterSelect.innerHTML = "";

      chapters.forEach((chapter) => {
        chapterSelect.innerHTML += `<option value="${chapter.chapter_id}">${chapter.chapter_name}</option>`
      });

      // <option value="<?php echo $chapter['chapter_id'] ?? "1"; ?>"><?php echo $chapter['chapter_name'] ?? "Error"; ?></option>

    }
  }

  xhr.onerror = () => {
    console.log('Request Error.');
  }

  xhr.send();
}

function loadChaptersForCourseEdit(event) {
  let courseID = event.target.value;
  console.log(courseID);
  let xhr = new XMLHttpRequest();

  xhr.open('GET', 'ajax.php?course_id=' + courseID, true);

  xhr.onload = () => { // If we use lambda expression we can't use this.status
    if (xhr.status == 200) {
      let chapters = JSON.parse(xhr.responseText);
      console.log(chapters);
      chapterSelectEdit.innerHTML = "";

      chapters.forEach((chapter) => {
        chapterSelectEdit.innerHTML += `<option value="${chapter.chapter_id}">${chapter.chapter_name}</option>`
      });

      // <option value="<?php echo $chapter['chapter_id'] ?? "1"; ?>"><?php echo $chapter['chapter_name'] ?? "Error"; ?></option>

    }
  }

  xhr.onerror = () => {
    console.log('Request Error.');
  }

  xhr.send();
}

function loadGradesForSchoolType(event) {
  let schoolTypeID = event.target.value;
  console.log(schoolTypeID);
  let xhr = new XMLHttpRequest();

  xhr.open('GET', 'ajax.php?school_type_id=' + schoolTypeID, true);

  xhr.onload = () => { // If we use lambda expression we can't use this.status
    if (xhr.status == 200) {
      let grades = JSON.parse(xhr.responseText);
      console.log(grades);
      gradesSelect.innerHTML = "";

      grades.forEach((grade) => {
        if (!(grade.grade_school_type_id == 1 && grade.grade_id <= 4)) {
          gradesSelect.innerHTML += `<option value="${grade.grade_id}">${grade.grade_name}</option>`;
        }
      });
    }
  }

  xhr.onerror = () => {
    console.log('Request Error.');
  }

  xhr.send();
}

function loadGradesForSchoolTypeEdit(event) {
  let schoolTypeID = event.target.value;
  console.log(schoolTypeID);
  let xhr = new XMLHttpRequest();
  let gradesSelectEdit = document.querySelector(`select[data-course-id-gd="${event.target.dataset.courseIdSc}"]`);

  xhr.open('GET', 'ajax.php?school_type_id=' + schoolTypeID, true);

  xhr.onload = () => { // If we use lambda expression we can't use this.status
    if (xhr.status == 200) {
      let grades = JSON.parse(xhr.responseText);
      console.log(grades);
      gradesSelectEdit.innerHTML = "";

      grades.forEach((grade) => {
        if (!(grade.grade_school_type_id == 1 && grade.grade_id <= 4)) {
          gradesSelectEdit.innerHTML += `<option value="${grade.grade_id}">${grade.grade_name}</option>`;
        }
      });
    }
  }

  xhr.onerror = () => {
    console.log('Request Error.');
  }

  xhr.send();
}

// LOGS SORTING ===================================================================================================================================

// let origin = window.location.origin;   // Returns base URL (https://example.com)
// let baseURL = '';
// let perPage = 5; // Change here, also change in userslogs.php
// let fullURL = window.location.href;     // Returns full URL (https://example.com/path/example.html)
// let currentPageNumber = fullURL.substring(fullURL.lastIndexOf('/') + 1);
// if(currentPageNumber == 'userslogs'){
//   currentPageNumber = 1;
// }

// if (origin == 'http://localhost') {
//   baseURL = 'http://localhost/karagaca/';
// } else if (origin == 'https://tatamata.rs') {
//   baseURL = 'https://tatamata.rs/';
// }

// let userTD = document.getElementById('user');
// let dateTD = document.getElementById('date');

// if (userTD) {
//   userTD.addEventListener('click', (event) => {
//     let xhr = new XMLHttpRequest();

//     xhr.open('GET', `${baseURL}admin/logs_sort.php?sortType=user`);

//     xhr.onload = () => { // If we use lambda expression we can't use this.status
//       if (xhr.status == 200) {
//         let logs = JSON.parse(xhr.responseText);
//         let html = "";

//         logs.forEach((log, index) => {
//           if (index != logs.length - 1) {
//             if (((index + 1) <= (currentPageNumber * perPage)) && (index + 1) > (currentPageNumber * perPage - perPage)) {
//               html += `<tr>
//               <td class="cell">#${++index}</td>
//               <td class="cell"><a href="${logs[logs.length - 1]}admin/user/${log.user_id}">${log.firstname} ${log.lastname}</a></td>
//               <td class="cell">${log.cpu_cores}</td>
//               <td class="cell">${log.ram} GB</td>
//               <td class="cell">${log.gpu}</td>
//               <td class="cell">${log.os}</td>
//               <td class="cell">${log.screen_resolution}</td>
//               <td class="cell">${log.timezone}</td>
//               <td class="cell">${log.date}</td>                                       
//             </tr>`;
//             }
//           }
//         });


//         document.querySelector('tbody').innerHTML = html;
//       }
//     }

//     xhr.onerror = () => {
//       console.log('Request Error.');
//     }

//     xhr.send();

//   });
// }

// if (dateTD) {
//   dateTD.addEventListener('click', (event) => {
//     let xhr = new XMLHttpRequest();

//     xhr.open('GET', `${baseURL}admin/logs_sort.php?sortType=date`);

//     xhr.onload = () => { // If we use lambda expression we can't use this.status
//       if (xhr.status == 200) {
//         let logs = JSON.parse(xhr.responseText);
//         let html = "";

//         logs.forEach((log, index) => {
//           if (index != logs.length - 1) {
//             if (((index + 1) <= (currentPageNumber * perPage)) && (index + 1) > (currentPageNumber * perPage - perPage)) {
//               html += `<tr>
//               <td class="cell">#${++index}</td>
//               <td class="cell"><a href="${logs[logs.length - 1]}admin/user/${log.user_id}">${log.firstname} ${log.lastname}</a></td>
//               <td class="cell">${log.cpu_cores}</td>
//               <td class="cell">${log.ram} GB</td>
//               <td class="cell">${log.gpu}</td>
//               <td class="cell">${log.os}</td>
//               <td class="cell">${log.screen_resolution}</td>
//               <td class="cell">${log.timezone}</td>
//               <td class="cell">${log.date}</td>                                       
//             </tr>`;
//             }
//           }
//         });


//         document.querySelector('tbody').innerHTML = html;
//       }
//     }

//     xhr.onerror = () => {
//       console.log('Request Error.');
//     }

//     xhr.send();
//   });
// }