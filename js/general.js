const xButton = document.getElementById('xButton');
const menu = document.getElementById('menuItems');
const menuBurger = document.getElementById('menuBurger');
const header = document.getElementsByClassName('header')[0];
const topDesc = document.getElementById('topDesc');
const headerHeight = document.getElementById('toggleHeader').scrollHeight;

// Get URL parameters.
function urlParams() {
  let url = new URL(window.location.href);
  let params = new URLSearchParams(url.search);
  return params;
}

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

// Get the natural height of an element
function getHeight(elem, eventTarget) {
  if (eventTarget === 1) {
    elem.style.display = 'block';
    var height = elem.scrollHeight + 'px';
    elem.style.display = '';
  } else if (eventTarget === 2) {
    var height = headerHeight;
  } else if (eventTarget === 3) {
    var height = searchBarHeight;
  }
  return height;
}

// Show an element
function show(elem, eventTarget) {
  let height = getHeight(elem, eventTarget); // Get the natural height

  if (eventTarget === 1) {
    elem.style.height = height;
    elem.parentElement.previousElementSibling.style.backgroundImage = "url('img/arrow-down-1.png')";
    elem.classList.add('is-visible'); // Make the element visible
  } else if (eventTarget === 2) {
    elem.style = 'height:' + height + 'px';
    elem.classList.remove('headerSmall');
    topDesc.style = 'opacity: 1';
    xButton.classList.replace('hidden', 'visible');
    elem.classList.add('vis'); // Make the element visible
    setCookie(1, 'header');
  } else if (eventTarget === 3) {
    elem.style = 'height:' + height + 'px;margin-top:0.8em';
    showHideButton.innerText = 'Hide Filters';
    filters.style = 'height:' + filtersHeight + 'px';
    elem.classList.add('vis'); // Make the element visible
    setCookie(1, 'filters');
  }

  // Remove fixed height after transition, so the element is still responsive
  window.setTimeout(function () {
    elem.style.height = '';
  }, 350);
}

function toggle(elem, eventTarget) {
  elem.classList.contains('is-visible') || elem.classList.contains('vis') ? hide(elem, eventTarget, 30) : show(elem, eventTarget);
}

// Hide an element
// Without delay, transitions doesn't work properly
function hide(elem, eventTarget, delay) {
  // Give the element a height to change from
  elem.style.height = elem.scrollHeight + 'px';

  if (eventTarget === 1) {
    hideManageTabs(elem);
  } else if (eventTarget === 2) {
    hidePageHeader(elem);
  } else if (eventTarget === 3) {
    hideSearchFilters(elem);
  }

  // change height; need small delay to make it work on Firefox
  window.setTimeout(function () {
    elem.classList.contains('header') ? (elem.style.height = '100px') : (elem.style.height = '0');
  }, delay);

  // after transition, remove 'visibile' classes
  window.setTimeout(function () {
    eventTarget === 1 ? elem.classList.remove('is-visible') : elem.classList.remove('vis');
  }, 350 + delay);
}

function hideManageTabs(elem) {
  elem.parentElement.previousElementSibling.style.backgroundImage = "url('img/arrow-right-1.png')";
}

function hidePageHeader(elem) {
  elem.classList.add('headerSmall');
  topDesc.style = 'opacity:0';
  setCookie(0, 'header');
  xButton.classList.replace('visible', 'hidden');
}

function hideSearchFilters(elem) {
  if (filterNone.style.display === 'inline-block') filters.style = 'height: 0;margin-top: 0';
  elem.style.marginTop = '0';
  showHideButton.innerText = 'Show Filters';
  setCookie(0, 'filters');
}

function windowResizeMobileMenuBehaviour() {
  if (window.innerWidth > 790) {
    if (menu.getAttribute('style') === null || menu.getAttribute('style') === '' || menu.style.display === 'none' || menu.style.display === 'block')
      menu.style.display = 'flex';
  } else {
    menu.style.display = 'none';
  }
}

function toggleMobileMenu() {
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

function whichElementToToggle(event) {
  // Toggle tabs in manage.php.
  if (event.target.classList.contains('manageTitle')) {
    const elementToToggle = event.target.nextElementSibling.firstElementChild;
    toggle(elementToToggle, 1);

    // Toggle Huddland Parliament header.
  } else if (event.target.getAttribute('id') === 'xButton') {
    const elementToToggle = document.getElementById('toggleHeader');
    toggle(elementToToggle, 2);

    // Toggle search bar
  } else if (event.target.getAttribute('id') === 'showHideFilters') {
    const elementToToggle = document.getElementById('searchInputsWrapper');
    toggle(elementToToggle, 3);
  }

  // Toggle mobile menu
  else if (event.target.getAttribute('id') === 'menuBurger') {
    toggleMobileMenu();
  }
}

window.addEventListener('resize', windowResizeMobileMenuBehaviour);
document.addEventListener('click', whichElementToToggle);

// hide Huddland Parliament header on load?
if (getCookie('header') == 0) {
  topDesc.style = 'opacity:0;transition:none';
  const element = document.getElementById('toggleHeader');
  element.style.transition = 'none';
  hide(element, 2, 0);
  topDesc.style = 'opacity:0';
}
