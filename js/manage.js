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
    if ($urlParams.has('amendMP')) {
      this.openAmendMp();
    } else if ($urlParams.has('removeMP')) {
      this.openRemoveMp();
    } else if ($urlParams.has('addMP')) {
      this.openAddMp();
    } else if ($urlParams.has('amendPARTY')) {
      this.openAmendParty();
    } else if ($urlParams.has('removePARTY')) {
      this.openRemoveParty();
    } else if ($urlParams.has('addPARTY')) {
      this.openAddParty();
    }
  },
  closeAllTabs: function () {
    this.mp.nextElementSibling.classList.replace('flex', 'none');
    this.mpButtons.classList.replace('none', 'flex');
    this.amendMp.classList.replace('flex', 'none');
    this.removeMp.classList.replace('flex', 'none');
    this.addMp.classList.replace('flex', 'none');
    this.mp.innerHTML = 'MPs';
    this.mp.style.backgroundImage = "url('img/arrow-right-1.png')";
  },
  //
  //
  // manage MP tab
  //
  //
  mp: document.getElementById('mp'),
  mpButtons: document.getElementById('mpButtons'),
  addMp: document.getElementById('addMp'),
  amendMp: document.getElementById('amendMp'),
  removeMp: document.getElementById('removeMp'),
  selectAmendMp: document.getElementById('mpList'),
  selectRemoveMp: document.getElementById('mpRemoveList'),

  openAmendMp: function () {
    this.closeAllTabs();
    this.mpButtons.classList.replace('flex', 'none');
    this.mp.nextElementSibling.classList.replace('none', 'flex');
    this.amendMp.classList.replace('none', 'flex');
    this.mp.innerHTML = '<a onclick="openTab.mpBackToChoice()">MPs</a> / Amend';
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  openRemoveMp: function () {
    this.mp.nextElementSibling.classList.replace('none', 'flex');
    this.mpButtons.classList.replace('flex', 'none');
    this.removeMp.classList.replace('none', 'flex');
    this.amendMp.classList.replace('flex', 'none');
    this.addMp.classList.replace('flex', 'none');
    this.mp.innerHTML =
      '<a onclick="openTab.mpBackToChoice()">MPs</a> / Remove';
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  openAddMp: function () {
    this.mp.nextElementSibling.classList.replace('none', 'flex');
    this.mpButtons.classList.replace('flex', 'none');
    this.addMp.classList.replace('none', 'flex');
    this.removeMp.classList.replace('flex', 'none');
    this.amendMp.classList.replace('flex', 'none');
    this.mp.innerHTML = '<a onclick="openTab.mpBackToChoice()">MPs</a> / Add';
    this.mp.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  mpBackToChoice: function () {
    this.mpButtons.classList.replace('none', 'flex');
    this.amendMp.classList.replace('flex', 'none');
    this.removeMp.classList.replace('flex', 'none');
    this.addMp.classList.replace('flex', 'none');
    this.mp.innerHTML = 'MPs';
  },

  openMpAction: function (event) {
    if (event.target.classList.contains('actionButton')) {
      if (event.target.innerText == 'Amend') {
        if (!url.has('amendMP')) {
          window.location.href = 'manage.php?amendMP#mp';
          return true;
        }
        // can't figure out why this.openAmendMp() "is not a function"
        openTab.openAmendMp();
      } else if (event.target.innerText == 'Remove') {
        if (!url.has('removeMP')) {
          window.location.href = 'manage.php?removeMP#mp';
          return true;
        }
        // can't figure out why this.openRemoveMp() "is not a function"
        openTab.openRemoveMp();
      } else if (event.target.innerText == 'Add') {
        if (!url.has('addMP')) {
          window.location.href = 'manage.php?addMP#mp';
          return true;
        }
        // can't figure out why this.openAddMp() "is not a function"
        openTab.openAddMp();
      }
    }
  },
  //
  //
  // manage PARTY tab
  //
  //
  party: document.getElementById('parties'),
  partyButtons: document.getElementById('partyButtons'),
  addParty: document.getElementById('addParty'),
  amendParty: document.getElementById('amendParty'),
  removeParty: document.getElementById('removeParty'),
  selectAmendParty: document.getElementById('partyAmendList'),
  selectRemoveParty: document.getElementById('partyRemoveList'),

  openAmendParty: function () {
    this.closeAllTabs();
    this.partyButtons.classList.replace('flex', 'none');
    this.party.nextElementSibling.classList.replace('none', 'flex');
    this.amendParty.classList.replace('none', 'flex');
    this.party.innerHTML =
      '<a onclick="openTab.partyBackToChoice()">Parties</a> / Amend';
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  openRemoveParty: function () {
    this.closeAllTabs();
    this.partyButtons.classList.replace('flex', 'none');
    this.party.nextElementSibling.classList.replace('none', 'flex');
    this.removeParty.classList.replace('none', 'flex');
    this.party.innerHTML =
      '<a onclick="openTab.partyBackToChoice()">Parties</a> / Remove';
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  openAddParty: function () {
    this.closeAllTabs();
    this.partyButtons.classList.replace('flex', 'none');
    this.party.nextElementSibling.classList.replace('none', 'flex');
    this.addParty.classList.replace('none', 'flex');
    this.party.innerHTML =
      '<a onclick="openTab.partyBackToChoice()">Parties</a> / Add';
    this.party.style.backgroundImage = "url('img/arrow-down-1.png')";
  },

  partyBackToChoice: function () {
    this.partyButtons.classList.replace('none', 'flex');
    this.amendParty.classList.replace('flex', 'none');
    this.removeParty.classList.replace('flex', 'none');
    this.addParty.classList.replace('flex', 'none');
    this.party.innerHTML = 'Parties';
  },

  openPartyAction: function (event) {
    if (event.target.classList.contains('actionButton')) {
      if (event.target.innerText == 'Amend') {
        if (!url.has('amendPARTY')) {
          window.location.href = 'manage.php?amendPARTY#parties';
          return true;
        }
        // can't figure out why this.openAmendMp() "is not a function"
        openTab.openAmendParty();
      } else if (event.target.innerText == 'Remove') {
        if (!url.has('removePARTY')) {
          window.location.href = 'manage.php?removePARTY#parties';
          return true;
        }
        // can't figure out why this.openRemoveMp() "is not a function"
        openTab.openRemoveParty();
      } else if (event.target.innerText == 'Add') {
        if (!url.has('addPARTY')) {
          window.location.href = 'manage.php?addPARTY#parties';
          return true;
        }
        // can't figure out why this.openAddMp() "is not a function"
        openTab.openAddParty();
      }
    }
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
      for (let i = 0; i < allTitles.length; i++) {
        if (allTitles[i].nextElementSibling.classList.contains('flex')) {
          allTitles[i].style.backgroundImage = 'url("img/arrow-right-1.png")';
          allTitles[i].nextElementSibling.classList.replace('flex', 'none');
        }
      }
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
    this.mpButtons.addEventListener('click', this.openMpAction);
    this.partyButtons.addEventListener('click', this.openPartyAction);
    this.selectAmendMp.addEventListener('change', function (event) {
      window.location.href =
        'manage.php?amendMP&mpID=' + event.target.value + '#mp';
    });
    this.selectRemoveMp.addEventListener('change', function (event) {
      window.location.href =
        'manage.php?removeMP&mpID=' + event.target.value + '#mp';
    });
    this.selectAmendParty.addEventListener('change', function (event) {
      window.location.href =
        'manage.php?amendPARTY&partyID=' + event.target.value + '#parties';
    });
    this.selectRemoveParty.addEventListener('change', function (event) {
      window.location.href =
        'manage.php?removePARTY&partyID=' + event.target.value + '#parties';
    });
  },
};
const url = urlParams();
openTab.openTabBasedOnUrl(url);
openTab.eventListeners();
