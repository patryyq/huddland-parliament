// get currently set GET parameters
function urlParams() {
  let url = new URL(window.location.href);
  let params = new URLSearchParams(url.search);
  return params;
}

// if user has selected one of the optionsl on refresh keep that tab open
const openTab = {
  //self: this, // workaround to use methods inside other methods
  //
  // on page load, open manage tab, based on url params
  openTabBasedOnUrl: function ($urlParams) {
    if ($urlParams.has('mp')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openMp();
        this.markErrorFields('MPmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('party')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openParty();
        this.markErrorFields('PARTYmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('interest')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openInterest();
        this.markErrorFields('INTERESTSmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    } else if ($urlParams.has('constituency')) {
      let confirmation = document.getElementById('confirmation');
      if (confirmation === null) {
        this.openConstituency();
        this.markErrorFields('CONSTITUENCYmanage');
        let error = document.getElementById('errorMessage');
        error.classList.replace('none', 'flex');
      }
    }
  },

  closeAllTabs: function () {
    this.mp.nextElementSibling.classList.replace('flex', 'none');
    this.addMp.classList.replace('flex', 'none');
    this.mp.style.backgroundImage = "url('img/arrow-right-1.png')";
  },
  //
  //
  // manage MP tab
  //
  //
  mp: document.getElementById('mp'),
  addMp: document.getElementById('addMp'),

  openMp: function () {
    this.mp.nextElementSibling.classList.replace('none', 'flex');
    this.addMp.classList.replace('none', 'flex');
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  //
  //
  // manage PARTY tab
  //
  //
  party: document.getElementById('parties'),
  addParty: document.getElementById('addParty'),

  openParty: function () {
    this.party.nextElementSibling.classList.replace('none', 'flex');
    this.addParty.classList.replace('none', 'flex');
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  //
  //
  // manage INTEREST tab
  //
  //
  interest: document.getElementById('interests'),
  addInterest: document.getElementById('addInterest'),

  openInterest: function () {
    this.interest.nextElementSibling.classList.replace('none', 'flex');
    this.addInterest.classList.replace('none', 'flex');
    this.interest.style.backgroundImage = "url('img/arrow-down-1.png')";
  },
  //
  //
  // manage CONSTITUENCY tab
  //
  //
  constituency: document.getElementById('constituencyTab'),
  addConstituency: document.getElementById('addConstituency'),

  openConstituency: function () {
    this.constituency.nextElementSibling.classList.replace('none', 'flex');
    this.addConstituency.classList.replace('none', 'flex');
    this.constituency.style.backgroundImage = "url('img/arrow-down-1.png')";
  },
  //
  //
  // open/close any tab on click;
  //
  // confirmation on 'submit'
  //
  //
  manage: document.getElementById('manage'),
  wrapper: document.getElementsByClassName('wrapper')[0],
  openTabOnClick: function (event) {
    if (event.target.classList.contains('manageTitle')) {
      let buttonStatus = event.target.nextElementSibling.classList.contains(
        'none'
      );
      let allTitles = document.getElementsByClassName('manageTitle');
      //  // clsoes all tabs
      // for (let i = 0; i < allTitles.length; i++) {
      //   if (allTitles[i].nextElementSibling.classList.contains('flex')) {
      //     allTitles[i].style.backgroundImage = 'url("img/arrow-right-1.png")';
      //     allTitles[i].nextElementSibling.classList.replace('flex', 'none');
      //   }
      // }
      if (buttonStatus) {
        event.target.style.backgroundImage = "url('img/arrow-down-1.png')";
        event.target.nextElementSibling.classList.replace('none', 'flex');
      } else {
        event.target.style.backgroundImage = "url('img/arrow-right-1.png')";
        event.target.nextElementSibling.classList.replace('flex', 'none');
      }
    }
  },

  confirmAction: function (event) {
    if (
      event.target.getAttribute('type') == 'submit' &&
      !confirm('Are you sure?')
    ) {
      event.preventDefault();
    }
  },

  // keep all eventListeners in one method
  eventListeners: function () {
    this.wrapper.addEventListener('click', this.confirmAction);
    this.manage.addEventListener('click', this.openTabOnClick);
  },

  markErrorFields: function (section) {
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
      }
    }
    for (let i = 0; i < input.length; i++) {
      if (input[i].value == false) {
        input[i].classList.add('errorField');
      }
    }
    for (let i = 0; i < select.length; i++) {
      if (select[i].value == false) {
        select[i].classList.add('errorField');
      }
    }
  },
};
const url = urlParams();
openTab.openTabBasedOnUrl(url);
openTab.eventListeners();

let confirmation = document.getElementById('confirmation');
let error = document.getElementById('errorMessage');
if (confirmation === null && error !== null) {
  setTimeout(function () {
    error.scrollIntoView();
  }, 30);
}
