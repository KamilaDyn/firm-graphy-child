<?php

/**
 * Kod dla wysłąnia formularza oraz lat i long
 * do poprawy, przy wysłaniu, żeby wyswietlał sie komunikat na tym samym szablonie
 */

// print_r($_POST);
global $wpdb;
$data_array = array(
    'imie' => $_POST['name'],
    'nazwisko' => $_POST['lastname'],
    'telefon' => $_POST['phone_number'],
    'ulica' => $_POST['street_name'],
    'nr_domu' => $_POST['home_number'],
    'kod_pocztowy' => $_POST['postcode'],
    'miasto' => $_POST['city'],
    'lista' => $_POST['shop_list'],
    'kwota' => $_POST['max_money'],
    'uwagi' => $_POST['extra_info'],
);


$city = $_POST['city'];
$street = $_POST['street_name'];
$postcode = $_POST['postcode'];
$home_nr = $_POST['home_number'];



// foreach ($_POST as $key => $value) {
// 	// echo $key . '=' . $value . '<br />';
// }
// Create a stream
$api_url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&city=$city& postalcode=$postcode&street=$street $home_nr";

$opts = array(
    'http' => array(
        'header' => array("Referer: $api_url\r\n")
    )
);
$context = stream_context_create($opts);
$json_data = file_get_contents($api_url, false, $context);

$response_data = json_decode($json_data, true);

// wyciągnięcie danych lat i long do wysłania dla tabeli adres
$lat = $response_data[0]['lat'];
$lon = $response_data[0]['lon'];


// wysłanie danych do tabeli shopping_list
$table_name = 'shopping_list';
// insert
$rowResult = $wpdb->insert($table_name, $data_array, $format = NULL);

$last_id = $wpdb->insert_id;
// wysłanie lat i long do tabeli ades id jest takie jak przy wysłaniu danych do listy
$json_insert = $wpdb->prepare("INSERT INTO adres (`id`,`latitude`, `longtitude`) VALUES('" . $last_id . "','" . $lat . "', '" . $lon . "' )");
$table_adress = "adres";
// potwierdzenie wysłania,

if ($rowResult) {
    echo 'dane wysłane';
} else {
    echo 'Błąd, dane nie wysłąne';
}

if ($rowResult == 1) {
    // powinna wyświetlać się ta sama trona tylko z komunikatem
    $wpdb->query($json_insert);
    echo '<div>
		<h1>Formularz wysłany</h1>
		<table style="border: 1px solid black; padding: 10px">
		<tr>
		<td>Zgłaszający</td>
		<td>' . $_POST['name'] . " " . $_POST['lastname'] . '</td>
		</tr>
		<tr>
		<td>Telefon</td>
		<td>' . $_POST['phone_number'] . '</td>
		</tr>
		<tr>
		<td>Adres</td>
		<td>' . $_POST['street_name'] . $_POST['home_number'] . $_POST['postcode'] . $_POST['city'] . '</td>
		</tr>
	    <tr>
		<td>Lista Zakupów</td>
		<td>' . $_POST['shop_list'] . '</td>
		</tr>
		<tr>
		<td>Kwota maksymalna</td>
		<td>' . $_POST['max_money'] . '</td>
		</tr>
		<tr>
		<td>Uwagi</td>
		<td>' . $_POST['extra_info'] . '</td>
		</tr>
		</table>
		</div>';
} else {
    echo '<h1> Wypełnij wszystkie pola</h1>';
}
