// Show an element
var show = function (elem) {
  // Get the natural height of the element
  var getHeight = function () {
    elem.style.display = 'block'; // Make it visible
    var height = elem.scrollHeight + 'px'; // Get it's height
    elem.style.display = ''; //  Hide it again
    return height;
  };

  var height = getHeight(); // Get the natural height
  elem.classList.add('is-visible'); // Make the element visible
  elem.style.height = height; // Update the max-height
  elem.parentElement.previousElementSibling.style.backgroundImage =
    "url('img/arrow-down-1.png')";

  // Once the transition is complete, remove the inline max-height so the content can scale responsively
  window.setTimeout(function () {
    elem.style.height = '';
  }, 250);
};

// Hide an element
var hide = function (elem) {
  // Give the element a height to change from
  elem.style.height = elem.scrollHeight + 'px';
  elem.parentElement.previousElementSibling.style.backgroundImage =
    "url('img/arrow-right-1.png')";

  // Set the height back to 0
  window.setTimeout(function () {
    if (elem.classList.contains('toggle-more')) {
      elem.style.height = '160px';
    } else {
      elem.style.height = '0';
    }
  }, 1);

  // When the transition is complete, hide it
  window.setTimeout(function () {
    elem.classList.remove('is-visible');
  }, 350);
};

// Toggle element visibility
var toggle = function (elem) {
  // If the element is visible, hide it
  if (elem.classList.contains('is-visible')) {
    hide(elem);
    return;
  }

  // Otherwise, show it
  show(elem);
};

// Listen for click events
document.addEventListener(
  'click',
  function (event) {
    if (!event.target.classList.contains('manageTitle')) return;
    let element = event.target.nextElementSibling.firstElementChild;
    if (!element) return;
    // Toggle the element
    toggle(element);
  },
  false
);

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

  //
  //
  // manage MP tab
  //
  //
  mp: document.getElementById('mp'),
  openMp: function () {
    this.mp.nextElementSibling.firstElementChild.classList.add('is-visible');
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  //
  //
  // manage PARTY tab
  //
  //
  party: document.getElementById('parties'),
  openParty: function () {
    this.party.nextElementSibling.firstElementChild.classList.add('is-visible');
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  //
  //
  // manage INTEREST tab
  //
  //
  interest: document.getElementById('interests'),
  openInterest: function () {
    this.interest.nextElementSibling.firstElementChild.classList.add(
      'is-visible'
    );
    this.interest.style.backgroundImage = "url('img/arrow-down-1.png')";
  },
  //
  //
  // manage CONSTITUENCY tab
  //
  //
  constituency: document.getElementById('constituencyTab'),

  openConstituency: function () {
    this.constituency.nextElementSibling.firstElementChild.classList.add(
      'is-visible'
    );
    this.constituency.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  confirmAction: function (event) {
    if (
      event.target.getAttribute('type') == 'submit' &&
      !confirm('Are you sure?')
    ) {
      event.preventDefault();
    }
  },

  wrapper: document.getElementById('manage'),
  // keep all eventListeners in one method
  eventListeners: function () {
    this.wrapper.addEventListener('click', this.confirmAction);
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
  // without delay, it wouldn't 'scroll into' desired location
  setTimeout(function () {
    error.scrollIntoView();
  }, 60);
}
