// Open specific tab on form submit with error
const openTab = {
  // open tab based on url params
  openTabBasedOnUrl: function ($urlParams) {
    if ($urlParams.has('mp')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openMp();
        this.markFields('MPmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('party')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openParty();
        this.markFields('PARTYmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('interest')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openInterest();
        this.markFields('INTERESTSmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('constituency')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openConstituency();
        this.markFields('CONSTITUENCYmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    }
  },

  // manage MP tab
  mp: document.getElementById('mp'),
  openMp: function () {
    this.mp.nextElementSibling.firstElementChild.classList.add('is-visible');
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  // manage PARTY tab
  party: document.getElementById('parties'),
  openParty: function () {
    this.party.nextElementSibling.firstElementChild.classList.add('is-visible');
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  // manage INTEREST tab
  interest: document.getElementById('interests'),
  openInterest: function () {
    this.interest.nextElementSibling.firstElementChild.classList.add(
      'is-visible'
    );
    this.interest.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  // manage CONSTITUENCY tab
  constituency: document.getElementById('constituencyTab'),
  openConstituency: function () {
    this.constituency.nextElementSibling.firstElementChild.classList.add(
      'is-visible'
    );
    this.constituency.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  // any submit button clicked, ask if sure
  confirmAction: function (event) {
    if (
      event.target.getAttribute('type') == 'submit' &&
      !confirm('Are you sure?')
    ) {
      event.preventDefault();
    }
  },

  wrapper: document.getElementById('manage'),
  eventListeners: function () {
    this.wrapper.addEventListener('click', this.confirmAction);
  },

  markFields: function (section) {
    let target = document.getElementById(section);
    let input = target.getElementsByTagName('input');
    let select = target.getElementsByTagName('select');
    if (section == 'MPmanage') {
      let checkboxes = document.getElementsByName('interests[]');
      let checkboxesWrapper = document.getElementById('interestsBoxes');
      let checked = false;
      for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].getAttribute('checked') === '') {
          checked = true;
        }
      }
      if (!checked) {
        checkboxesWrapper.classList.add('errorField');
      } else {
        checkboxesWrapper.classList.add('successField');
      }
    }
    for (let i = 0; i < input.length; i++) {
      if (input[i].value == false) {
        input[i].classList.add('errorField');
      } else if (input[i].getAttribute('type') !== 'submit') {
        input[i].classList.add('successField');
      }
    }
    for (let i = 0; i < select.length; i++) {
      if (select[i].value == false) {
        select[i].classList.add('errorField');
      } else {
        select[i].classList.add('successField');
      }
    }
  },
};
const url = urlParams();
openTab.openTabBasedOnUrl(url);
openTab.eventListeners();

// Party principal colour field
let colours = document.getElementById('colours');
let input = document.getElementById('principalColour');
let select = document.getElementById('colours');
let options = colours.getElementsByTagName('option');
let body = document.getElementsByTagName('body')[0];
let count = 0;
searchColour = function () {
  count = 0;
  select.style.display = 'none';
  for (let i = 0; i < options.length; i++) {
    if (options[i].innerText.includes(input.value) && input.value.length > 0) {
      options[i].style.display = 'block';
      count++;
    } else if (input.value.length == 0) {
      options[i].style.display = 'block';
    } else {
      options[i].style.display = 'none';
    }
  }
  if (count === 0) {
    select.style.display = 'none';
  } else if (count < 7) {
    select.style.display = 'block';
    select.setAttribute('size', count);
    select.style.overflow = 'hidden';
  } else {
    select.style.display = 'block';
    select.setAttribute('size', 7);
    select.style.overflow = 'auto';
  }
};

openSelect = function (event) {
  select.style.display = 'block';
  input.parentElement.parentElement.parentElement.style.overflow = 'visible';
};

input.addEventListener('focus', openSelect);
input.addEventListener('keyup', searchColour);
body.addEventListener('click', function (event) {
  if (
    !event.target.classList.contains('colourOption') &&
    event.target.getAttribute('id') != 'principalColour'
  ) {
    select.style.display = 'none';
    input.parentElement.parentElement.parentElement.style.overflow = 'hidden';
  }
});
select.addEventListener('click', function (event) {
  if (event.target.classList.contains('colourOption')) {
    console.log(event.target.innerText);
    input.value = event.target.innerText;
    input.style =
      'padding: 8px;margin-bottom:0;border:4px solid ' +
      event.target.innerText.replace(/\s/g, '');
    select.style.display = 'none';
    input.parentElement.parentElement.parentElement.style.overflow = 'hidden';
  }
});

let confirmation = document.getElementById('confirmation');
let error = document.getElementById('errorMessage');
if (confirmation === null && error !== null) {
  // without delay, it wouldn't 'scroll into' desired location
  setTimeout(function () {
    error.scrollIntoView();
  }, 60);
}
