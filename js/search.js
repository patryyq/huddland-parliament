const searchButton = document.getElementById('searchButton');
const filters = document.getElementById('filters');
const filtersHeight = filters.scrollHeight;
const MPname = document.getElementById('MPname');
const party = document.getElementById('party');
const constituency = document.getElementById('constituency');
const interest = document.getElementById('interestSearch');
const browseResults = document.getElementById('browseResults');
const browseResultsInitial = document.getElementById('browseResults').innerHTML;
const browseText = document.getElementById('browseText');
const filterMP = document.getElementById('filterMP');
const filterParty = document.getElementById('filterParty');
const filterConstituency = document.getElementById('filterConstituency');
const filterInterest = document.getElementById('filterInterest');
const filterNone = document.getElementById('filterNone');
const showHideButton = document.getElementById('showHideFilters');
const searchBarHeight = document.getElementById('searchInputsWrapper').scrollHeight;

let lastParams = '';
async function searchRequest(pageLoad = true) {
  let send = false;
  let url = new URL(window.location.href);
  url = new URLSearchParams(url.search);

  // user clicks Search button
  if (pageLoad) {
    if (MPname.value !== '' || party.value !== '' || interest.value !== '' || constituency.value !== '') send = true;
    var params = {
      MPname: MPname.value,
      party: party.value,
      constituency: constituency.value,
      interest: interest.value,
    };
    // If some param in URL (user copy/pasted url with param), send request with data from URL.
    // (only on page load cause !pageLoad)
  } else {
    if (url.get('MPname') !== null || url.get('party') !== null || url.get('constituency') !== null || url.get('interest') !== null) send = true;
    var params = {
      MPname: url.get('MPname'),
      party: url.get('party'),
      constituency: url.get('constituency'),
      interest: url.get('interest'),
    };
  }

  // Set string variable with currently used parameters to compare it with last request.
  let currentParams = params.MPname + params.party + params.constituency + params.interest;

  // If some parameter is set (send === true), and parameters
  // are different than in last request, send request.
  // (avoid needless requests)
  if (send === true && currentParams !== lastParams) {
    lastParams = params.MPname + params.party + params.constituency + params.interest;
    console.log('Request:');
    console.log(params);
    const path = 'php/';
    const requestUrl = path + 'search.php';
    const opts = {
      headers: { Accept: 'application/json' },
      method: 'POST',
      body: JSON.stringify(params),
    };
    const response = await fetch(requestUrl, opts);
    let data = await response.json();
    console.log('Response:');
    console.log(data);

    // Set URL parameters based on PHP's response.
    // Slice() to remove '&' from end of URL.
    let urlParam = data.urlParameters;
    window.history.pushState({ path: urlParam }, '', urlParam.slice(0, -1));

    // Fill fields in the serach bar based on PHP's response (only validated parameters).
    // If parameter is not sent back, it means that it didn't get thourgh validation.
    // When user pastes URL with params, do the same - set respective fields based on response.
    //
    // Can't take data directly from URL cause it's huge
    // security issue, so instead will take it from PHP response
    // which is validated, therefore more secure.
    MPname.value = data.validParameters.MPname ? data.validParameters.MPname : '';
    party.value = data.validParameters.party ? data.validParameters.party : '';
    constituency.value = data.validParameters.constituency ? data.validParameters.constituency : '';
    interest.value = data.validParameters.interest ? data.validParameters.interest : '';

    // display search results
    // display used params/filter
    displaySearchResults(data);
    displayActiveFilters();
  } else if (
    send === false &&
    (url.has('MPname') || MPname.value.length === 0 || url.has('party') || url.has('constituency') || url.has('interest'))
  ) {
    // User click Search button, but no filters are set (all fields clear)
    // and some param in URL => reset url and display all MPs (saved on page load).
    let urlParam = '?';

    // If single filter was set and user removed it, then tried to set the same
    // filter again it wouldn't let it, cause lastParams and currentParams would be the same => don't send.
    // Assigning '' (default value) to lastParams here, helps to avoid this unwanted behavior.
    lastParams = '';
    window.history.pushState({ path: urlParam }, '', urlParam);
    browseResults.innerHTML = browseResultsInitial;

    // timeout; otherwise, the opacity transition doesn't work
    setTimeout(function () {
      let MPs = browseResults.getElementsByTagName('a');
      for (let i = 0; i < MPs.length; i++) {
        MPs[i].style.opacity = 1;
      }
    }, 100);
    displayActiveFilters();
  }
}

// This function to be called by searchRequest();
// Display results. If no results - display error message.
//
function displaySearchResults(data) {
  if (data.MPs.length > 0) {
    browseResults.innerHTML = '';

    for (let i = 0; i < data.MPs.length; i++) {
      // create 'a' element; link to MP details
      let a = document.createElement('a');
      a.setAttribute('href', 'mp.php?mpID=' + data.MPs[i].id);

      // create 'div' element (child of 'a')
      let div = document.createElement('div');
      div.setAttribute('class', 'mpBrowse');

      // create 'span' element (child of 'div'); contains PARTY name
      let span = document.createElement('span');
      span.classList.add('partyName');
      span.innerText = ', ' + data.MPs[i].name;

      // create 'b' element (child of 'div'); contains MP name
      let b = document.createElement('b');
      b.innerText = data.MPs[i].firstname + ' ' + data.MPs[i].lastname;

      div.style.borderLeft = '8px solid ' + data.MPs[i].principal_colour.replace(/\s/g, '');

      div.appendChild(b);
      div.appendChild(span);

      a.appendChild(div);
      browseResults.appendChild(a);
    }
    // If no results with current filters, display error message.
  } else {
    browseResults.innerHTML =
      '<a style="width:100%"><div style="border-left: 8px solid #071c4a;"><div class="mpBrowse"><b>No search results. Change your filters please.</b></div></div></a>';
  }

  // timeout; otherwise, the opacity transition doesn't work
  setTimeout(function () {
    let MPs = browseResults.getElementsByTagName('a');
    for (let i = 0; i < MPs.length; i++) {
      MPs[i].style.opacity = 1;
    }
  }, 50);
}

// Check each filter (parameter) and behave accordingly.
//
function displayActiveFilters() {
  let url = new URL(window.location.href);
  url = new URLSearchParams(url.search);
  let anyFilterSet = false;
  // let filterBorder = 'border: 4px solid #071c4a;padding: 8px 8px 7px 6px;';
  let filterBorder = 'background-color:#c9d9ff;';
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
    browseText.innerText = 'Huddland Parliament MPs:';
    browseResults.innerHTML = browseResultsInitial;
  } else {
    filterNone.style.display = 'none';
    browseText.innerText = 'MPs matching your filters:';
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

// (false) - get data from URL rather than search/input fields
// So, on page load, check if parameters in URL are set and behave accordingly.
searchRequest(false);

// If no parameters set and 'filter' cookie to 0 => hide filters
let urlSearch = urlParams();
if (
  !urlSearch.has('MPname') &&
  !urlSearch.has('party') &&
  !urlSearch.has('interest') &&
  !urlSearch.has('constituency') &&
  getCookie('filters') == 0
) {
  let element = document.getElementById('searchInputsWrapper');
  hide(element, 3, 0);
} else {
  // Scenario: User clicks 'Hide Filters', but some filters are active. Right now, the search bar will close
  // and cookie set to 0. So, if users refreshes the page (with params), the search bar will open,
  // but if user removes filters and refreshes again, it will close, because cookie was set to 0.
  //
  // Same scenario as above, but below line uncommented - if user refreshes page, with cookie 0 but params set,
  // the search bar after reload will be open and cookie set to 1. So, on next page reload (even if no params set)
  // search bar will open anyways.
  // (not sure if that makes sense...)
  //
  // It's one of them where it's difficult to choose the right behavior. For some
  // users this way will be natural way, for others the other.
  //
  // setCookie(1, 'filters');
}
