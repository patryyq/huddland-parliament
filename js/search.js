const searchButton = document.getElementById('searchButton');
const searchBar = document.getElementById('searchBar');
const filters = document.getElementById('filters');
const MPname = document.getElementById('MPname');
const party = document.getElementById('party');
const constituency = document.getElementById('constituency');
const interest = document.getElementById('interestSearch');
const browseResults = document.getElementById('browseResults');
const browseResultsInitial = document.getElementById('browseResults').innerHTML;
const filterMP = document.getElementById('filterMP');
const filterParty = document.getElementById('filterParty');
const filterConstituency = document.getElementById('filterConstituency');
const filterInterest = document.getElementById('filterInterest');
const filterNone = document.getElementById('filterNone');

async function searchRequest() {
  let send = false;
  let url = new URL(window.location.href);
  url = new URLSearchParams(url.search);
  if (
    MPname.value !== '' ||
    party.value !== '' ||
    interest.value !== '' ||
    constituency.value !== ''
  ) {
    send = true;
  }

  let params = {
    MPname: MPname.value,
    party: party.value,
    constituency: constituency.value,
    interest: interest.value,
  };

  // if any parameter set (send === true) => send request
  if (send === true) {
    const path = '';
    const url = path + 'search.php';
    const opts = {
      headers: { Accept: 'application/json' },
      method: 'POST',
      body: JSON.stringify(params),
    };
    const response = await fetch(url, opts);
    let data = await response.json();
    console.log(data);

    // set url parameters based on PHP's response
    // slice() to remove '&' from end of url
    let urlParam = data.urlParameters;
    window.history.pushState({ path: urlParam }, '', urlParam.slice(0, -1));

    // display search results
    displaySearchResults(data);
    displayActiveFilters();

    // if no filter is set and any param in URL, reset url and
    // put all MPs (saved on page load) into results div
  } else if (
    send === false &&
    (url.has('MPname') ||
      MPname.value.length === 0 ||
      url.has('party') ||
      url.has('constituency') ||
      url.has('interest'))
  ) {
    let urlParam = '';
    window.history.pushState({ path: urlParam }, '', urlParam);
    browseResults.innerHTML = browseResultsInitial;
    displayActiveFilters();
  }
}

function displaySearchResults(data) {
  if (data.details.length > 0) {
    browseResults.innerHTML = '';

    for (let i = 0; i < data.details.length; i++) {
      // create 'a' element; link to MP details
      let a = document.createElement('a');
      a.setAttribute('href', 'mp.php?mpID=' + data.details[i].id);

      // create 'div' element (child of 'a')
      let div = document.createElement('div');
      div.setAttribute('class', 'mpBrowse');

      // create 'b' element (child of 'div'); MP name
      let b = document.createElement('b');
      b.innerText = data.details[i].firstname + ' ' + data.details[i].lastname;

      div.style.borderLeft =
        '8px solid ' + data.details[i].principal_colour.replace(/\s/g, '');

      div.appendChild(b);
      a.appendChild(div);
      browseResults.appendChild(a);
    }
  } else {
    browseResults.innerHTML =
      '<div style="font-size:1.3em;margin-top:1em;border-left: 8px solid #071c4a;"><div class="mpBrowse"><b>No search results. Change your filters please.</b></div></div>';
  }
}

function displayActiveFilters() {
  let url = new URL(window.location.href);
  url = new URLSearchParams(url.search);
  let anyFilterSet = false;
  let filterBorder = 'border: 4px solid #071c4a;padding: 8px 8px 7px 6px;';
  if (url.has('MPname') || MPname.value.length > 0) {
    MPname.style = filterBorder;
    filterMP.style.display = 'inline-block';
    filterNone.style.display = 'none';
    anyFilterSet = true;
  } else {
    MPname.style = '';
    filterMP.style.display = 'none';
  }
  if (url.has('party')) {
    party.style = filterBorder;
    filterParty.style.display = 'inline-block';
    filterNone.style.display = 'none';
    anyFilterSet = true;
  } else {
    party.style = '';
    filterParty.style.display = 'none';
  }
  if (url.has('constituency')) {
    constituency.style = filterBorder;
    filterConstituency.style.display = 'inline-block';
    filterNone.style.display = 'none';
    anyFilterSet = true;
  } else {
    constituency.style = '';
    filterConstituency.style.display = 'none';
  }
  if (url.has('interest')) {
    interest.style = filterBorder;
    filterInterest.style.display = 'inline-block';
    filterNone.style.display = 'none';
    anyFilterSet = true;
  } else {
    interest.style = '';
    filterInterest.style.display = 'none';
  }
  if (anyFilterSet === false) {
    filterNone.style.display = 'inline-block';
  } else {
    filterNone.style.display = 'none';
  }
}

function closeFilters(event) {
  if (event.target.classList.contains('filterX')) {
    let id = event.target.parentElement.getAttribute('id');
    if (id === 'filterMP') {
      MPname.value = '';
      displayActiveFilters();
      MPname.style = '';
      event.target.parentElement.style.display = 'none';
      searchRequest();
    } else if (id === 'filterParty') {
      party.value = '';
      displayActiveFilters();
      party.style = '';
      event.target.parentElement.style.display = 'none';
      searchRequest();
    } else if (id === 'filterConstituency') {
      constituency.value = '';
      displayActiveFilters();
      constituency.style = '';
      event.target.parentElement.style.display = 'none';
      searchRequest();
    } else if (id === 'filterInterest') {
      interest.value = '';
      displayActiveFilters();
      interest.style = '';
      event.target.parentElement.style.display = 'none';
      searchRequest();
    }
  }
}

searchButton.addEventListener('click', searchRequest);
filters.addEventListener('click', closeFilters);
searchBar.addEventListener('click', function (event) {
  if (event.target.classList.contains('partyOption')) {
    console.log(event.target);
    //  event.target.innerHTML =
  }
});
