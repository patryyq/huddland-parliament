let count = 0;
let li = document.getElementsByTagName('li');
let colour = [];
for (let i = 1; i < li.length; i++) {
  if (li[i].firstElementChild.getAttribute('href') != '#') {
    colour.push(
      li[i].firstElementChild.innerText
        .replace(/([a-z])([A-Z])/g, '$1 $2')
        .toLowerCase()
    );
  }
}

colour.forEach(function(col){
    result.innerHTML = result.innerHTML + "['"+ col.replace(/\s/g,'')\ + "' => '" + col + "'], " + '<br>'})
