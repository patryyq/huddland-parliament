<?php

class parliament
{

    function __construct()
    {
        $this->mpID = isset($_GET['mpID']) && is_numeric($_GET['mpID']) ? $_GET['mpID'] : false;
        $this->db = new db();
        $this->validate = new validate();
    }

    // from format YYYY-MM-DD
    public function calculateAge($dateOfBirth)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');
        $mpYear = substr($dateOfBirth, 0, 4);
        $mpMonth = substr($dateOfBirth, 5, 2);
        $mpDay = substr($dateOfBirth, 8, 2);

        // had birthday this year?
        // has birthday today? 
        $age = $currentYear - $mpYear - 1;
        if (($currentMonth > $mpMonth) || ($currentMonth == $mpMonth && $currentDay >= $mpDay)) $age = $age + 1;
        return $age;
    }

    // return:
    // 1 => constituency ID same as SESSION[constituency] AND constituency not represented by any MP; manage.php
    // 2 => constituency not represented by any MP; manage.php
    // 3 => render all constituencies; index.php
    public function isConstituencySelected($givenConstID, $page = false)
    {
        if (
            isset($_SESSION['constituency']) &&
            $_SESSION['constituency'] == $givenConstID &&
            $page === 'manage' &&
            !$this->db->isConstRepresented($givenConstID)
        ) {
            $result = 1;
        } else if (
            $page === 'manage' &&
            !$this->db->isConstRepresented($givenConstID)
        ) {
            $result = 2;
        } else if ($page !== 'manage') {
            $result = 3;
        } else {
            $result = false;
        }
        return $result;
    }

    public function search($encodedData)
    {
        if ($this->isJSON($encodedData)) {
            $searchData = $this->validateAndSearch($encodedData);
            $gatherAllIDs = [];

            foreach ($searchData as $key => $value) {
                if ($value && $key !== 'urlParameters' && $key !== 'numberOfValidFields' && $key !== 'usedParams') {
                    foreach ($value as $val) {
                        array_push($gatherAllIDs, $val['id']);
                    }
                }
            }

            // if count of particular MP ID equals 'numberOfValidFields' -> given MP's ID meets all search criteria
            $countOfEachID = array_count_values($gatherAllIDs);
            $IDmatchingAllCriteria = [];
            $numberOfValidFields = $searchData->numberOfValidFields;

            foreach ($countOfEachID as $ID => $count) {
                if ($count === $numberOfValidFields) array_push($IDmatchingAllCriteria, $ID);
            }

            $responseToJS = new stdClass();
            $responseToJS->MPs = $this->db->getMatchingSearchMP($IDmatchingAllCriteria);
            $responseToJS->validParameters = $searchData->usedParams;
            $responseToJS->urlParameters = $searchData->urlParameters;
            return $responseToJS;
        }
        return false;
    }

    private function validateAndSearch($encodedData)
    {
        $data = json_decode($encodedData);
        $validate = $this->validate;
        $valid = new stdClass();

        $valid->MPname = $validate->multipleWords($data->MPname) ? $data->MPname : false;
        $valid->party = $validate->id($data->party, 'party', 'noSession') ? $data->party : false;
        $valid->interest = $validate->id($data->interest, 'interestSearch') ? $data->interest : false;
        $valid->constituency = $validate->id($data->constituency, 'constituency', 'noSession') ? $data->constituency : false;
        $valid->usedParams = [
            'MPname' => $valid->MPname,
            'party' => $valid->party,
            'constituency' => $valid->constituency,
            'interest' => $valid->interest
        ];

        $numberValidField = 0;
        $url = '?';
        if ($valid->MPname) {
            $url .= 'MPname=' . $valid->MPname . '&';
            $valid->MPname = $this->db->searchMPname($valid->MPname);
            $numberValidField++;
        }
        if ($valid->party) {
            $url .= 'party=' . $valid->party . '&';
            $valid->party = $this->db->searchMpPartyID($valid->party);
            $numberValidField++;
        }
        if ($valid->constituency) {
            $url .= 'constituency=' . $valid->constituency . '&';
            $valid->constituency = $this->db->searchMpConstituencyID($valid->constituency);
            $numberValidField++;
        }
        if ($valid->interest) {
            $url .= 'interest=' . $valid->interest . '&';
            $valid->interest = $this->db->searchMpInterestID($valid->interest);
            $numberValidField++;
        }

        $valid->numberOfValidFields = $numberValidField;
        $valid->urlParameters = $url;
        return $valid;
    }

    private function isJSON($encodedDataFromJS)
    {
        json_decode($encodedDataFromJS);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    // these $_SESSIONs are used to populate input fields on reload after validation error
    public function unsetInputFieldSessions()
    {
        unset($_SESSION['firstname']);
        unset($_SESSION['lastname']);
        unset($_SESSION['dateOfBirth']);
        unset($_SESSION['party']);
        unset($_SESSION['constituency']);
        unset($_SESSION['interests']);
        unset($_SESSION['partyName']);
        unset($_SESSION['principalColour']);
        unset($_SESSION['dateOfFoundation']);
        unset($_SESSION['interestName']);
        unset($_SESSION['electorate']);
        unset($_SESSION['constituencyRegion']);
    }

    // list of all/most CSS colour names
    //
    // scraped the data/colours with simple JS program
    // source of colours: http://www.colors.commutercreative.com/grid/
    //
    // 1) used in process of generating the colours select field
    // 2) used in colour validation process
    public $principalColoursList = [
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
}
