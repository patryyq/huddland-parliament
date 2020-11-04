<?php
include('php/config.php');


// $string = "Namesss Abbsc";
// $dob = '1926-11-01';
// $emil = 'info@wgwarmia.pl';
// $interests = array();

// // $interests['interests'] = '16s';
// $interests['dob'] = 12;

// // $interests[2]['interests'] = '17';

$val = new validate();
// echo $val->dateOfBirth($dob);
// echo $val->email($emil);
// echo $val->id(4, 'party');
// echo $val->id(101, 'mp');
// echo $val->foundYear('1820');
// echo var_dump($val->interests($interests));
// echo $val->validatedPostData(0);
// echo $val->validateField('firstname', $string);
// echo $val->validateField('dob', $dob);
// echo $val->validateField('email', $emil);
// echo var_dump($val->validateField('dateOfBirth', $interests));
// //$val->validateField('interests', $interests);
// // echo $val->validateField('party', '5');
// // echo $val->validateField('constituency', '30');
// if (isset($_SESSION['validError'])) {
//     echo var_dump($_SESSION['validError']);
// }
// unset($_SESSION['validError']);
// echo $val->matchColour('lighblue');
echo $val->number('100.,000');
