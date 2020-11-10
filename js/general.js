const xButton = document.getElementById('xButton');
const header = document.getElementsByClassName('header')[0];
const topDesc = document.getElementById('topDesc');

function setCookie(value, name) {
  let now = new Date();
  // expiry date; nearly forever
  now.setTime(now.getTime() + 1 * 24 * 60 * 60 * 1000);
  let expires = 'expires=' + now.toUTCString();
  document.cookie = name + '=' + value + ';' + expires + ';path=/';
}

xButton.addEventListener('click', function () {
  if (topDesc.classList.contains('flex')) {
    topDesc.classList.replace('flex', 'none');
    xButton.classList.replace('visible', 'hidden');
    header.classList.add('headerSmall');
    setCookie(0, 'header');
  } else {
    topDesc.classList.replace('none', 'flex');
    xButton.classList.replace('hidden', 'visible');
    header.classList.remove('headerSmall');
    setCookie(1, 'header');
  }
});
