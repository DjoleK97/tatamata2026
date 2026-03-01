let imePrezime = document.getElementById('ime-prezime');
let emailSifra = document.getElementById('email-sifra');
let zemljaRazredTelefon = document.getElementById('zemlja-razred-telefon');
let prevBtnB = document.getElementById('prevB');
let nextBtnB = document.getElementById('nextB');
let registerForm = document.getElementById("register-form");
let registerProgressItems = document.querySelectorAll('.register-progress-item');
let schoolSelect = document.querySelector(`[name="school"]`);
let numberDivRegister = document.getElementById('number-div');
let deteMozeCheckbox = document.querySelector('.detemozecheck');

// SELECTED COUNTRY ======================================================================================================

let countrySelect = document.getElementsByName('country')[0];
let gradeDiv = document.getElementById('grade-div');

if (countrySelect) {
  countrySelect.addEventListener('change', (event) => {
    let selectedCountry = countrySelect.value;

    if (selectedCountry == "srbija" && schoolSelect.value != "elementary_school") {
      numberDivRegister.style.display = "block";
    } else {
      numberDivRegister.style.display = "none";
    }
  });
}

let currentPage = 1;

nextBtnB.addEventListener('click', async () => {
  switch (currentPage) {
    case 1:

      let firstname = document.querySelector(`input[name="firstname"]`);
      let lastname = document.querySelector(`input[name="lastname"]`);

      let ifime = document.querySelector('.ifime');
      let ifime2 = document.querySelector('.ifime2');
      let ifprezime = document.querySelector('.ifprezime');
      let ifprezime2 = document.querySelector('.ifprezime2');

      let error = false;

      clearErrors([firstname, lastname], [ifime, ifime2, ifprezime, ifprezime2]);

      if (firstname.value == "") {
        error = true;
        firstname.classList.add('is-invalid');
        ifime.style.display = "block";
      } else {
        if (!isLettersOnly(firstname.value)) {
          firstname.classList.add('is-invalid');
          error = true;
          ifime2.style.display = "block";
        } else {
          firstname.classList.add('is-valid');
        }
      }

      if (lastname.value == "") {
        lastname.classList.add('is-invalid');
        error = true;
        ifprezime.style.display = "block";
      } else {
        if (!isLettersOnly(lastname.value)) {
          lastname.classList.add('is-invalid');
          error = true;
          ifprezime2.style.display = "block";
        } else {
          lastname.classList.add('is-valid');
        }
      }

      if (!error) {
        imePrezime.style.display = "none";
        emailSifra.style.display = "block";
        prevBtnB.style.display = "inline-block";
        currentPage = 2;
        registerProgressItems[currentPage - 1].classList.add('active');
        // registerProgressItems[currentPage - 2].classList.remove('active');
      }

      break;
    case 2:

      let email = document.querySelector(`input[name="email"]`);
      let password = document.querySelector(`input[name="password"]`);
      let password2 = document.querySelector(`input[name="password2"]`);

      let ifemail = document.querySelector('.ifemail');
      let ifemail2 = document.querySelector('.ifemail2');
      let ifpassword = document.querySelector('.ifpassword');
      let ifpassword2 = document.querySelector('.ifpassword2');

      let error2 = false;

      clearErrors([email, password, password2], [ifemail, ifemail2, ifpassword, ifpassword2]);

      if (email.value == "") {
        error2 = true;
        email.classList.add('is-invalid');
        ifemail.style.display = "block";
      } else {
        if (!validateEmail2(email.value)) {
          email.classList.add('is-invalid');
          ifemail.style.display = "block";
          error2 = true;
        } else {
          if (await takenEmail(email.value)) {
            email.classList.add('is-invalid');
            error2 = true;
            ifemail2.style.display = "block";
          } else {
            email.classList.add('is-valid');
          }
        }
      }

      if (password.value == "") {
        password.classList.add('is-invalid');
        error2 = true;
        ifpassword.style.display = "block";
      } else {
        if (password.value != password2.value) {
          ifpassword2.style.display = "block";
          error2 = true;
        } else {
          password.classList.add('is-valid');
          password2.classList.add('is-valid');
        }
      }

      if (!error2) {
        emailSifra.style.display = "none";
        imePrezime.style.display = "none";
        zemljaRazredTelefon.style.display = "block";
        currentPage = 3;
        nextBtnB.innerHTML = `Završi <i class="fas fa-check"></i>`;
        registerProgressItems[currentPage - 1].classList.add('active');
        // registerProgressItems[currentPage - 2].classList.remove('active');

        if (schoolSelect.value == "elementary_school") {
          numberDivRegister.style.display = "none";
          deteMozeCheckbox.style.display = "block";
        } else {
          numberDivRegister.style.display = "block";
          deteMozeCheckbox.style.display = "none";
        }
      }

      break;
    case 3:

      let slazemSe = document.querySelector(`input[name="slazem_se"]`);
      let slazemSe2 = document.querySelector(`input[name="slazem_se2"]`);
      let ifslazemSe = document.querySelector('.ifslazemse');
      let ifslazemSe2 = document.querySelector('.ifslazemse2');

      clearErrors([slazemSe, slazemSe2], [ifslazemSe, ifslazemSe2]);

      let hasError = false;

      if (!slazemSe.checked) {
        ifslazemSe.style.display = "block";
        hasError = true;
      }

      if (!slazemSe2.checked && deteMozeCheckbox.style.display != "none") {
        ifslazemSe2.style.display = "block";
        hasError = true;
      }

      if (!hasError) {
        registerForm.submit();
      }

      break;
  }
});

prevBtnB.addEventListener('click', () => {
  switch (currentPage) {
    case 2:
      emailSifra.style.display = "none";
      imePrezime.style.display = "block";
      prevBtnB.style.display = "none";
      currentPage = 1;

      registerProgressItems[currentPage - 1].classList.add('active');
      registerProgressItems[currentPage].classList.remove('active');

      break;
    case 3:
      emailSifra.style.display = "block";
      zemljaRazredTelefon.style.display = "none";
      currentPage = 2;

      nextBtnB.innerHTML = `Dalje <i class="fas fa-arrow-right"></i>`;
      registerProgressItems[currentPage - 1].classList.add('active');
      registerProgressItems[currentPage].classList.remove('active');

      break;
  }
});

function isLettersOnly(text) {
  const regex = /^[a-zA-ZšđčćžŠĐČĆŽ]+$/;

  return regex.test(String(text));
}

function clearErrors(fields, messages) {
  fields.forEach(field => {
    field.classList.remove('is-invalid');
    field.classList.remove('is-valid');
  });

  messages.forEach(message => {
    message.style.display = "none";
  });
}

function cleanInput(input) {
  // Create a new div element
  let temporalDivElement = document.createElement("div");
  // Set the HTML content with the providen
  temporalDivElement.innerHTML = input;

  // Retrieve the text property of the element (cross-browser support)
  return temporalDivElement.textContent || temporalDivElement.innerText || "";
}

function validateEmail2(email) {
  if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
    return true;
  }

  return false;
}

async function takenEmail(email) {
  let taken = false;

  await $.ajax({
    url: 'ajax.php',
    type: 'POST',
    data: {
      email,
    },
    success: (response) => {
      if (response == '1') {
        taken = true;
      }
    }
  });

  return taken;
}