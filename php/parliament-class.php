<?php

class parliament
{
    public $error = [];

    function __construct()
    {
        $this->mpID = isset($_GET['mpID']) && is_numeric($_GET['mpID']) ? $_GET['mpID'] : false;
        // // is_numeric($_GET['partyID']) === true; should be enough 
        // $this->partyID = isset($_GET['partyID']) && is_numeric($_GET['partyID']) ? $_GET['partyID'] : false;
        $this->db = new db();
    }

    // get all details of single MP
    public function getMpDetails()
    {
        // used GROUP_CONCAT to gather all interests as single string
        // rather than having duplicated results/rows with only different interest
        $query =
            "SELECT members.id, members.firstname, members.party_id, members.lastname, members.date_of_birth, 
            parties.name, parties.date_of_foundation, parties.principal_colour, constituencies.region, 
            constituencies.electorate, constituencies.id AS constiID,
            GROUP_CONCAT(interests.name SEPARATOR ', ') AS interests,
            GROUP_CONCAT(interests.id SEPARATOR ',') AS interestsID
            FROM members
            LEFT JOIN parties ON parties.id = members.party_id
            LEFT JOIN constituencies ON constituencies.id = members.constituency_id 
            LEFT JOIN interest_member ON interest_member.member_id = members.id
            LEFT JOIN interests ON interests.id = interest_member.interest_id
            WHERE members.id = ?";
        $param = [$this->mpID];
        $mp = $this->db->selectQuery($query, $param);
        if ($mp[0]['firstname'] != NULL) {
            return $mp;
        } else {
            return false;
        }
    }

    // calculate age, from format YYYY-MM-DD
    public function getAge($dateOfBirth)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');
        $mpYear = substr($dateOfBirth, 0, 4);
        $mpMonth = substr($dateOfBirth, 5, 2);
        $mpDay = substr($dateOfBirth, 8, 2);

        // had birthday this year already?
        // has birthday today? 
        $age = ($currentYear - $mpYear) - 1;
        if (($currentMonth > $mpMonth) ||
            ($currentMonth == $mpMonth && $currentDay >= $mpDay)
        ) {
            $age = $age + 1;
        }
        return $age;
    }

    // display links to MP details
    public function displayMpList()
    {
        if ($mps = $this->db->getAllMp()) {
            foreach ($mps as $mp) {
                $firstname = $mp['firstname'];
                $lastname = $mp['lastname'];
                $colour = str_replace(' ', '', $mp['principal_colour']);
                $border = 'border-left:8px solid ' . $colour;
                echo '<a href="mp.php?mpID=' . $mp['id'] . '"><div class="mpBrowse" style="' . $border . '"><b>' . $firstname . ' ' . $lastname . '</b>, <span style="font-size:0.9em;color:#494949">' . $mp['name'] . '</span></div></a>';
            }
        } else {
            return false;
        }
    }

    // generate dropdown menu with parties
    public function displayPartiesList($topMessage, $selected = false)
    {
        if ($parties = $this->db->getAllParties()) {
            $selectStart = '<select id="party" name="party"><option value="">' . $topMessage . '</option>';
            $selectEnd = '</select>';
            $options = '';
            foreach ($parties as $party) {
                if (isset($_SESSION['party']) && $_SESSION['party'] == $party['id'] && $selected === false) {
                    $options .= '<option selected value="' . $party['id'] . '">' . $party['name'] . '</option>';
                } else {
                    $options .= '<option class="partyOption" value="' . $party['id'] . '">' . $party['name'] . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        } else {
            return false;
        }
    }

    // generate dropdown menu with contituencies
    public function displayConstituenciesList($topMessage, $selected = false)
    {
        if ($constituencies = $this->db->getAllConstituencies()) {
            $selectStart = '<select id="constituency" name="constituency"><option value="">' . $topMessage . '</option>';
            $selectEnd = '</select>';
            $options = '';
            foreach ($constituencies as $constituency) {
                if (isset($_SESSION['constituency']) && $_SESSION['constituency'] == $constituency['id'] && $selected === false) {
                    $options .= '<option selected value="' . $constituency['id'] . '">' . $constituency['region'] . '</option>';
                } else {
                    $options .= '<option class="constituencyOption" value="' . $constituency['id'] . '">' . $constituency['region'] . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        } else {
            return false;
        }
    }

    // generate checkboxes with interests
    public function displayInterests($type)
    {
        $interests = $this->db->getAllInterests();
        $input = '';
        if ($type == 'checkbox') {
            foreach ($interests as $interest) {
                if (in_array($interest['id'], (isset($_SESSION['interests']) ? $_SESSION['interests'] : array()))) {
                    $input .= '<div class="interests"><input type="checkbox" checked name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
                } else {
                    $input .= '<div class="interests"><input type="checkbox" name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
                }
            }
        } else if ($type == 'list') {
            $selectStart = '<select id="interestSearch" name="interestSearch"><option value=""></option>';
            $selectEnd = '</select>';
            $input = '';
            foreach ($interests as $interest) {
                $input .= '<option class="interestOption" value="' . $interest['id'] . '">' . $interest['name'] . '</option>';
            }
            $input = $selectStart . $input . $selectEnd;
        }
        return $input;
    }

    public function search($encodedData)
    {
        // check if given data has valid JSON structure 
        // (avoid simple JS/JSON manipulation)
        if ($this->isJSON($encodedData)) {

            $searchData = $this->validateAndSearch($encodedData); // $searchData hold all matching IDs
            $numberOfValidFields = $searchData->validFields;
            $allIDs = []; // array to hold all IDs
            foreach ($searchData as $key => $value) {
                // foreach $value (in MP, party etc.) which is not false (some IDs found) AND 
                // $key is not equal urlParamateres AND not equal validFields
                if ($value && $key !== 'urlParameters' && $key !== 'validFields' && $key !== 'usedParams') {
                    // loop through all IDs and put them to array
                    foreach ($value as $values) {
                        array_push($allIDs, $values['id']);
                    }
                }
            }
            // count occurrence of each MP ID
            $allIDs = array_count_values($allIDs);
            $IDmatchingAllCriteria = []; // array to hold all MP IDs which meet search criteria

            // if count of MP ID equals 'numberOfValidFields' -> given MP ID meets all search criteria
            foreach ($allIDs as $k => $v) {
                if ($v === $numberOfValidFields) {
                    array_push($IDmatchingAllCriteria, $k);
                }
            }

            // get details of all MPs who match the search criteria
            $details = $this->db->getMatchingSearchMP($IDmatchingAllCriteria);
            $result = new stdClass();
            $result->MPs = $details;
            $result->validParameters = $searchData->usedParams;
            $result->urlParameters = $searchData->urlParameters;
            return $result;
        } else {
            return false;
        }
    }

    private function validateAndSearch($encodedData)
    {
        // validate all POST data from JS
        $data = json_decode($encodedData);
        $validate = new validate();
        $validData = new stdClass();
        $validData->MPname = $validate->multipleWords($data->MPname) ? $data->MPname : false;
        $validData->party = $validate->id($data->party, 'party') ? $data->party : false;
        $validData->interest = $validate->id($data->interest, 'interestSearch') ? $data->interest : false;
        $validData->constituency = $validate->id($data->constituency, 'constituency') ? $data->constituency : false;
        $validData->usedParams = ['MPname' => $validData->MPname, 'party' => $validData->party, 'constituency' => $validData->constituency, 'interest' => $validData->interest];
        // create link based on validated fields 
        // and return alongside with search results (matching MP IDs)
        $numberValidField = 0;
        $url = '?';
        if ($validData->MPname) {
            $url .= 'MPname=' . $validData->MPname . '&';
            $validData->MPname = $this->db->searchMPname($validData->MPname);
            $numberValidField++;
        }
        if ($validData->party) {
            $url .= 'party=' . $validData->party . '&';
            $validData->party = $this->db->searchMpPartyID($validData->party);
            $numberValidField++;
        }
        if ($validData->constituency) {
            $url .= 'constituency=' . $validData->constituency . '&';
            $validData->constituency = $this->db->searchMpConstituencyID($validData->constituency);
            $numberValidField++;
        }
        if ($validData->interest) {
            $url .= 'interest=' . $validData->interest . '&';
            $validData->interest = $this->db->searchMpInterestID($validData->interest);
            $numberValidField++;
        }
        $validData->urlParameters = $url;
        // number of valid fields to compare with number of same MP IDs
        // if number of same MP IDs === number of fields
        // means that the MP ID meets all search criteria
        $validData->validFields = $numberValidField;
        return $validData;
    }

    private function isJSON($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    // function called after ADD functions (MP/party/interest/constituency)
    // to destroy $_SESSION values which are used to populate form data
    // so, after a record is added to DB, the form is empty
    public function unsetSession()
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

    // display confirmation messages
    // 0 => add MP
    // 1 => add party
    // 2 => add interest
    // 3 => add constituency
    public function displayMessage()
    {
        if (isset($_SESSION['confirmationMessage'])) {
            $data = $_SESSION['confirmationMessage'][0];
            if (array_key_exists('firstname', $data[2])) {
                echo '<div id="confirmation" class="confirmation">' . $data[0] . $data[2]['firstname'] . ' ' . $data[2]['lastname'] . $data[1] . '</div>';
            } else if (array_key_exists('partyName', $data[2])) {
                echo '<div id="confirmation" class="confirmation">' . $data[0] . $data[2]['partyName'] . $data[1] . '</div>';
            } else if (array_key_exists('interestName', $data[2])) {
                echo '<div id="confirmation" class="confirmation">' . $data[0] . $data[2]['interestName'] . $data[1] . '</div>';
            } else if (array_key_exists('electorate', $data[2])) {
                echo '<div id="confirmation" class="confirmation">' . $data[0] . $data[2]['constituencyRegion'] . $data[1] . '</div>';
            }
        }
    }

    // display error messages
    public function displayError()
    {
        if (isset($_SESSION['errorMessage'])) {
            echo '<div id="errorMessage" class="none">';
            foreach ($_SESSION['errorMessage'] as $error) {
                echo '<div class="manageError">' . $error . '</div>';
            }
            echo '</div>';
        }
    }

    // generate options for principal colours select field
    public function coloursDropdown()
    {
        foreach ($this->principleColoursList as $key => $value) {
            echo '<option class="colourOption">' . $value . '</option>';
        }
    }

    // list of all (if not, then at least most) css colour names
    //
    // scraped the data/colours with simple JS program
    // source: http://www.colors.commutercreative.com/grid/
    //
    // 1) used in process of generating the colours select field
    // 2) used in colour validation process
    public $principleColoursList = [
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
