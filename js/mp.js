// Gender API: https://gender-api.com
// Recognize gender by first name.
//
//
// Random Face API: https://fakeface.rest/
// AI-generated faces, based on age and gender.
// Homepage description: "This API returns image URLs of fake human
// faces generated by the website thispersondoesnotexist.com"
//
//
// Random Quote API: https://api.quotable.io/random
// Author's GitHub: https://github.com/lukePeavey/quotable
//
//
// Fetch()ing data from APIs, when developing locally, produces CORS errors.
// Used PROXY to get around that: https://cors-anywhere.herokuapp.com/
// More info: https://medium.com/swlh/avoiding-cors-errors-on-localhost-in-2020-5a656ed8cefa
//
//
// Gender API has limit of 500 requests a month; line 63 allows to turn ON/OFF the gender API

const PROXY = 'https://cors-anywhere.herokuapp.com/';

async function getRandomQuote() {
  let randomQuote;
  try {
    const response = await fetch('https://api.quotable.io/random');
    const data = await response.json();
    randomQuote = data;
  } catch (e) {
    randomQuote.content = 'Here a random quote should appear, but unfortunately something went wrong.';
  }
  return randomQuote;
}

async function renderRandomQuote() {
  const randomQuote = await getRandomQuote();
  const p = document.createElement('p');
  p.classList.add('randomQuote');
  const quoteDiv = document.getElementById('randomQuote');
  quoteDiv.innerHTML = '';
  p.innerText = '"' + randomQuote.content + '"';
  quoteDiv.appendChild(p);
}

async function getGender() {
  let gender;
  const MPfirstname = document.getElementById('mpName').innerText.split(' ')[0];
  try {
    const APIkey = '&key=oNrWDverHQkAtDXMDW';
    const url = PROXY + 'https://gender-api.com/get?name=' + MPfirstname + APIkey;
    const response = await fetch(url);
    const data = await response.json();
    gender = data;
  } catch (e) {
    gender = { gender: 'unknown' };
  }
  return gender;
}

async function getRandomFace() {
  let randomFace;
  let gender = await getGender(); // GENDER OFF: let gender = { gender: 'unknown' }; // GENDER ON:let gender = await getGender();
  gender = gender.gender === 'unknown' ? '' : gender.gender;
  const age = parseInt(document.getElementById('age').innerText);

  // minAge > 76 ? set to 76, because the API returns '404 Not Found' on ages > 76
  const minAge = (age > 20 ? age - 2 : age) > 76 ? 76 : age - 2;
  const maxAge = age + 2; // maxAge is not affected by the minAge problem/bug(?)

  try {
    const url = PROXY + 'https://fakeface.rest/face/json?minimum_age=' + minAge + '&maximum_age=' + maxAge + '&gender=' + gender;
    const response = await fetch(url);
    const data = await response.json();
    randomFace = data;
  } catch (e) {
    randomFace = { image_url: 'img/api_broken.jpg' };
  }
  return randomFace;
}

async function renderRandomFace() {
  const randomFace = await getRandomFace();
  const img = document.createElement('img');
  img.classList.add('randomFace');
  const randomFaceDiv = document.getElementById('randomFace');
  img.setAttribute('src', randomFace.image_url);
  randomFaceDiv.innerHTML = '';
  randomFaceDiv.appendChild(img);
}

renderRandomQuote();
renderRandomFace();
