const xButton = document.getElementById('xButton');
const header = document.getElementsByClassName('header')[0];
const topDesc = document.getElementById('topDesc');
const menuBurger = document.getElementById('menuBurger');
const headerHeight = document.getElementById('toggleHeader').scrollHeight;

function setCookie(value, name) {
  let now = new Date();
  // expiry date; nearly forever
  now.setTime(now.getTime() + 1 * 24 * 60 * 60 * 1000);
  let expires = 'expires=' + now.toUTCString();
  document.cookie = name + '=' + value + ';' + expires + ';path=/';
}

function getCookie(name) {
  let cookieArray = document.cookie.split(';');
  for (let i = 0; i < cookieArray.length; i++) {
    let cookieValue = cookieArray[i].split('=');
    if (name == cookieValue[0].trim()) {
      return decodeURIComponent(cookieValue[1]);
    }
  }
  return false;
}

function menuResponsive(event) {
  let menu = event.target.previousElementSibling;

  // All class/display menu states and window resize taken into consideration.
  if (menu.classList.contains('vis-resp') && menu.style.display === 'none') {
    menu.classList.remove('vis-resp');
    menu.style.display = 'block';
  } else if (menu.classList.contains('vis-resp')) {
    menu.classList.remove('vis-resp');
    menu.style.display = 'none';
  } else if (menu.style.display === 'block') {
    menu.style.display = 'none';
  } else {
    menu.classList.add('vis-resp');
    menu.style.display = 'block';
  }
}

// Show an element
var show = function (elem, header = false) {
  // Get the natural height of the element
  var getHeight = function (header) {
    if (!header) {
      elem.style.display = 'block'; // Make it visible
      var height = elem.scrollHeight + 'px'; // Get it's height
      elem.style.display = ''; //  Hide it again
    } else {
      var height = headerHeight;
    }
    return height;
  };

  var height = getHeight(header); // Get the natural height
  if (!header) {
    elem.style.height = height;
  } else {
    elem.style = 'height:' + height + 'px';
  } // Update the max-height

  if (!header) {
    elem.parentElement.previousElementSibling.style.backgroundImage =
      "url('img/arrow-down-1.png')";
    elem.classList.add('is-visible'); // Make the element visible
  } else {
    elem.classList.remove('headerSmall');
    topDesc.style = 'height:auto;opacity: 1';
    xButton.classList.replace('hidden', 'visible');
    elem.classList.add('header-vis'); // Make the element visible
    setCookie(1, 'header');
  }
  // Once the transition is complete, remove the inline max-height so the content can scale responsively
  window.setTimeout(function () {
    elem.style.height = '';
    //  elem.classList.remove('headerSmall');
  }, 350);
};

// Hide an element
function hide(elem, header = false) {
  // Give the element a height to change from
  elem.style.height = elem.scrollHeight + 'px';
  if (!header) {
    elem.parentElement.previousElementSibling.style.backgroundImage =
      "url('img/arrow-right-1.png')";
  } else {
    if (getCookie('header') == 0) {
      topDesc.style = 'height:0px;opacity:0;transition:unset';
    } else {
      topDesc.style = 'height:0px;opacity:0';
    }
    xButton.classList.replace('visible', 'hidden');
    elem.classList.add('headerSmall');
    setCookie(0, 'header');
  }

  // Set the height back to 0
  window.setTimeout(function () {
    if (elem.classList.contains('header')) {
      elem.style.height = '100px';
    } else {
      elem.style.height = '0';
    }
  }, 1);

  // When the transition is complete, hide it
  window.setTimeout(function () {
    if (!header) {
      elem.classList.remove('is-visible');
    } else {
      elem.classList.remove('header-vis');
    }
  }, 350);
}

// Toggle element visibility
var toggle = function (elem, header = false) {
  // If the element is visible, hide it
  if (elem.classList.contains('is-visible')) {
    hide(elem, header);
    return;
  } else if (elem.classList.contains('header-vis')) {
    hide(elem, header);
    return;
  }
  show(elem, header);
};

menuBurger.addEventListener('click', menuResponsive);

// If user is on Desktop and opens 'Responsive Menu' with
// screen width < 790 and then resizes screen to width > 790 =>
// change menu display from 'block' to 'flex' so it looks better
window.addEventListener('resize', function () {
  let menu = document.getElementById('menuItems');
  if (window.innerWidth > 790) {
    if (
      menu.getAttribute('style') === null ||
      menu.getAttribute('style') === '' ||
      menu.style.display === 'none' ||
      menu.style.display === 'block'
    ) {
      menu.style.display = 'flex';
    }
  } else {
    menu.style.display = 'none';
  }
});

// Show/hide tabs in manage.php.
// Show/hide Huddland Parliament header.
document.addEventListener(
  'click',
  function (event) {
    if (event.target.classList.contains('manageTitle')) {
      let element = event.target.nextElementSibling.firstElementChild;
      toggle(element, false);
    } else if (event.target.getAttribute('id') === 'xButton') {
      let element = document.getElementById('toggleHeader');
      toggle(element, true);
    }
  },
  false
);

if (getCookie('header') == 0) {
  let element = document.getElementById('toggleHeader');
  element.style.transition = 'none';
  hide(element, true);
}
