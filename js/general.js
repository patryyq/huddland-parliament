const xButton = document.getElementById('xButton');
const header = document.getElementById('header');

function setCookie(value, name) {
  let now = new Date();
  // expiry date; nearly forever
  now.setTime(now.getTime() + 1 * 24 * 60 * 60 * 1000);
  let expires = 'expires=' + now.toUTCString();
  document.cookie = name + '=' + value + ';' + expires + ';path=/';
}

xButton.addEventListener('click', function () {
  if (header.classList.contains('flex')) {
    header.classList.replace('flex', 'none');
    xButton.classList.replace('visible', 'hidden');
    setCookie(0, 'header');
  } else {
    header.classList.replace('none', 'flex');
    xButton.classList.replace('hidden', 'visible');
    setCookie(1, 'header');
  }
});
