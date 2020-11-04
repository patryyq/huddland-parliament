<?php

class colour
{

    // list of available principle colours
    // scraped with JS from:
    // http://www.colors.commutercreative.com/grid/
    //
    private $principleColoursList = [
        'aliceblue' => 'alice blue',
        'antiquewhite' => 'antique white',
        'aqua' => 'aqua',
        'aquamarine' => 'aquamarine',
        'azure' => 'azure',
        'beige' => 'beige',
        'bisque' => 'bisque',
        'black' => 'black',
        'blanchedalmond' => 'blanched almond',
        'blue' => 'blue',
        'blueviolet' => 'blue violet',
        'brown' => 'brown',
        'burlywood' => 'burly wood',
        'cadetblue' => 'cadet blue',
        'chartreuse' => 'chartreuse',
        'chocolate' => 'chocolate',
        'coral' => 'coral',
        'cornflowerblue' => 'cornflower blue',
        'cornsilk' => 'cornsilk',
        'crimson' => 'crimson',
        'cyan' => 'cyan',
        'darkblue' => 'dark blue',
        'darkcyan' => 'dark cyan',
        'darkgoldenrod' => 'dark golden rod',
        'darkgray' => 'dark gray',
        'darkgreen' => 'dark green',
        'darkgrey' => 'dark grey',
        'darkkhaki' => 'dark khaki',
        'darkmagenta' => 'dark magenta',
        'darkolivegreen' => 'dark olive green',
        'darkorange' => 'dark orange',
        'darkorchid' => 'dark orchid',
        'darkred' => 'dark red',
        'darksalmon' => 'dark salmon',
        'darkseagreen' => 'dark sea green',
        'darkslateblue' => 'dark slate blue',
        'darkslategray' => 'dark slate gray',
        'darkslategrey' => 'dark slate grey',
        'darkturquoise' => 'dark turquoise',
        'darkviolet' => 'dark violet',
        'deeppink' => 'deep pink',
        'deepskyblue' => 'deep sky blue',
        'dimgray' => 'dim gray',
        'dimgrey' => 'dim grey',
        'dodgerblue' => 'dodger blue',
        'firebrick' => 'fire brick',
        'floralwhite' => 'floral white',
        'forestgreen' => 'forest green',
        'fuchsia' => 'fuchsia',
        'gainsboro' => 'gainsboro',
        'ghostwhite' => 'ghost white',
        'gold' => 'gold',
        'goldenrod' => 'goldenrod',
        'gray' => 'gray',
        'green' => 'green',
        'greenyellow' => 'green yellow',
        'grey' => 'grey',
        'honeydew' => 'honey dew',
        'hotpink' => 'hot pink',
        'indianred' => 'indian red',
        'indigo' => 'indigo',
        'ivory' => 'ivory',
        'khaki' => 'khaki',
        'lavender' => 'lavender',
        'lavenderblush' => 'lavender blush',
        'lawngreen' => 'lawn green',
        'lemonchiffon' => 'lemon chiffon',
        'lightblue' => 'light blue',
        'lightcoral' => 'light coral',
        'lightcyan' => 'light cyan',
        'lightgoldenrodyellow' => 'light golden rod yellow',
        'lightgray' => 'light gray',
        'lightgreen' => 'light green',
        'lightgrey' => 'light grey',
        'lightpink' => 'light pink',
        'lightsalmon' => 'light salmon',
        'lightseagreen' => 'light sea green',
        'lightskyblue' => 'light sky blue',
        'lightslategray' => 'light slate gray',
        'lightslategrey' => 'light slate grey',
        'lightsteelblue' => 'light steel blue',
        'lightyellow' => 'light yellow',
        'lime' => 'lime',
        'limegreen' => 'lime green',
        'linen' => 'linen',
        'magenta' => 'magenta',
        'maroon' => 'maroon',
        'mediumaquamarine' => 'medium aqua marine',
        'mediumblue' => 'medium blue',
        'mediumorchid' => 'medium orchid',
        'mediumpurple' => 'medium purple',
        'mediumseagreen' => 'medium sea green',
        'mediumslateblue' => 'medium slate blue',
        'mediumspringgreen' => 'medium spring green',
        'mediumturquoise' => 'medium turquoise',
        'mediumvioletred' => 'medium violet red',
        'midnightblue' => 'midnight blue',
        'mintcream' => 'mint cream',
        'mistyrose' => 'misty rose',
        'moccasin' => 'moccasin',
        'navajowhite' => 'navajo white',
        'navy' => 'navy',
        'oldlace' => 'old lace',
        'olive' => 'olive',
        'olivedrab' => 'olive drab',
        'orange' => 'orange',
        'orangered' => 'orange red',
        'orchid' => 'orchid',
        'palegoldenrod' => 'pale golden rod',
        'palegreen' => 'pale green',
        'paleturquoise' => 'pale turquoise',
        'palevioletred' => 'pale violet red',
        'papayawhip' => 'papaya whip',
        'peachpuff' => 'peach puff',
        'peru' => 'peru',
        'pink' => 'pink',
        'plum' => 'plum',
        'powderblue' => 'powder blue',
        'purple' => 'purple',
        'rebeccapurple' => 'rebecca purple',
        'red' => 'red',
        'rosybrown' => 'rosy brown',
        'royalblue' => 'royal blue',
        'saddlebrown' => 'saddle brown',
        'salmon' => 'salmon',
        'sandybrown' => 'sandy brown',
        'seagreen' => 'sea green',
        'seashell' => 'sea shell',
        'sienna' => 'sienna',
        'silver' => 'silver',
        'skyblue' => 'sky blue',
        'slateblue' => 'slate blue',
        'slategray' => 'slate gray',
        'slategrey' => 'slate grey',
        'snow' => 'snow',
        'springgreen' => 'spring green',
        'steelblue' => 'steel blue',
        'tan' => 'tan',
        'teal' => 'teal',
        'thistle' => 'thistle',
        'tomato' => 'tomato',
        'turquoise' => 'turquoise',
        'violet' => 'violet',
        'wheat' => 'wheat',
        'white' => 'white',
        'whitesmoke' => 'white smoke',
        'yellow' => 'yellow',
        'yellowgreen' => 'yellow green'
    ];
    public function dropdownMenu()
    {
        foreach ($this->principleColoursList as $key => $value) {
            echo '<option class="colourOption">' . $value . '</option>';
        }
    }

    public function matchColour($givenColour)
    {
        if (array_key_exists(preg_replace('/\s+/', '', $givenColour), $this->principleColoursList)) {
            return $givenColour;
        }
        echo 'error ty kurwo';
    }
}



// let li = document.getElementsByTagName('li');
// let colour = ;
// for (let i = 1; i < li.length; i++) {
//   if (lii.firstElementChild.getAttribute('href') != '#') {
//     colour.push(
//       lii.firstElementChild.innerText
//         .replace(/(a-z)(A-Z)/g, '$1 $2')
//         .toLowerCase()
//     );
//   }
// }

// colour.forEach(function(col){
//     result.innerHTML = result.innerHTML + "'"+ col.replace(/\s/g,'')\ + "' => '" + col + "', " + '<br>'})
