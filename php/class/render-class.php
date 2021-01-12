<?php

class render
{

    function __construct()
    {
        $this->parliament = new parliament();
        $this->validate = new validate();
        $this->db = new db();
    }

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

    public function renderColoursDropdown()
    {
        foreach ($this->parliament->principalColoursList as $key => $value) {
            echo '<option class="colourOption">' . $value . '</option>';
        }
    }


    public function renderInterests($type)
    {
        $interests = $this->db->getAllInterests();
        $checkedInterests = isset($_SESSION['interests']) ? $_SESSION['interests'] : [];
        $input = '';

        if ($type === 'checkbox') {
            foreach ($interests as $int) {
                (in_array($int['id'], $checkedInterests)) ? $checked = ' checked' : $checked = '';
                $input .= '<div class="interests"><input type="checkbox"' . $checked . ' name="interests[]" value="' . $int['id'] . '">' . $this->validate->entitiesHTML($int['name']) . '</div>';
            }
        } else if ($type === 'list') {
            foreach ($interests as $int) {
                $input .= '<option value="' . $int['id'] . '">' . $this->validate->entitiesHTML($int['name']) . '</option>';
            }
            $input = '<select id="interestSearch" name="interestSearch"><option value=""></option>' . $input . '</select>';
        }
        return $input;
    }


    public function renderConstituenciesList($page = false)
    {
        if ($constituencies = $this->db->getAllConstituencies()) {
            $selectStart = '<select id="constituency" name="constituency"><option value=""></option>';
            $selectEnd = '</select>';
            $options = '';

            foreach ($constituencies as $constituency) {
                $id = $constituency['id'];
                $region = $this->validate->entitiesHTML($constituency['region']);
                if ($this->parliament->isConstituencySelected($id, $page) === 1) {
                    $options .= '<option selected value="' . $id . '">' . $region . '</option>';
                } else if ($this->parliament->isConstituencySelected($id, $page) === 2) {
                    $options .= '<option value="' . $id . '">' . $region . '</option>';
                } else if ($this->parliament->isConstituencySelected($id, $page) === 3) {
                    $options .= '<option value="' . $id . '">' . $region . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        }
        return false;
    }

    public function renderPartiesList($valueFromSession = false)
    {
        if ($parties = $this->db->getAllParties()) {
            $selectStart = '<select id="party" name="party"><option value=""></option>';
            $selectEnd = '</select>';
            $options = '';

            foreach ($parties as $party) {
                (isset($_SESSION['party']) && $_SESSION['party'] == $party['id'] && $valueFromSession === 'valueFromSession') ?
                    $selected = ' selected' :
                    $selected = '';
                $options .= '<option' . $selected . ' value="' . $party['id'] . '">' . $this->validate->entitiesHTML($party['name']) . '</option>';
            }
            return $selectStart . $options . $selectEnd;
        }
        return false;
    }

    public function renderMpList()
    {
        $mps = $this->db->getAllMp();
        $list = '';

        foreach ($mps as $mp) {
            $firstname = $this->validate->entitiesHTML($mp['firstname']);
            $lastname = $this->validate->entitiesHTML($mp['lastname']);
            $partyname = $this->validate->entitiesHTML($mp['name']);
            $colour = str_replace(' ', '', $mp['principal_colour']);
            $border = 'border-left:8px solid ' . $colour;
            $list .= '
            <a href="mp.php?mpID=' . $mp['id'] . '">
                <div class="mpBrowse" style="' . $border . '">
                    <b>' . $firstname . ' ' . $lastname . '</b>,
                    <span class="partyName"> ' . $partyname . '</span>
                </div>
            </a>';
        }
        return $list;
    }
}
