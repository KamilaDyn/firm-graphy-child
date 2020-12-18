<?php

/**
 * The template for displaying form
 *
 *  Template Name: Mapa
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Firm Graphy
 * @since Firm Graphy 1.0.0
 */

get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <div class="single-wrapper">
            <div class="wrapper page-section">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="entry-content-page">
                        <?php the_content(); ?>
                        <!-- Page Content -->
                    </div><!-- .entry-content-page -->

                <?php
                endwhile; ?>
                <form class="map-form">
                    <input type="text" name="lat" id="lat" size=12 value="">
                    <input type="text" name="lon" id="lon" size=12 value="">
                </form>
                <b>Wyszukaj adres</b>
                <div id="search_city" class="search">
                    <input class="input" type="text" name="addr" value="" id="addr" size="58" />
                    <input class="btn btn-transparent" type="submit" value="szukaj" onclick="addr_search();" />
                    <div id="results"></div>
                </div>

                <br />

                <div class="map-container">
                    <div id="mapid" style="width: 100%; height: 500px; margin-top: 50px;"> </div>
                </div>
            </div>
        </div>

</div><!-- .single-wrapper end -->
</main>
</div>
<?php

global $wpdb;


$sql_name = "SELECT * FROM `shopping_list`  INNER JOIN `adres` on `shopping_list`.id=`adres`.id  WHERE wolontariusz = 'wolontariusz' or wolontariusz is null";
// $result = $wpdb->query($sql_name);
$result = $wpdb->get_results($sql_name);
// print_r($result);


?>
<script type="text/javascript">
    let data = <?php echo json_encode($result); ?>;
    const map = L.map('mapid').setView([50.320350, 17.578320], 13);

    // Get the tile layer from OpenStreetMaps 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {

        // Specify the maximum zoom of the map 
        maxZoom: 19,

        // Set the attribution for OpenStreetMaps 
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //poczatkowe miasto wyświetlane
    let startlat = 50.320350;
    let startlon = 17.578320;

    var options = {
        center: [startlat, startlon],
        zoom: 9
    }
    document.getElementById('lat').value = startlat;
    document.getElementById('lon').value = startlon;


    var myMarker = L.marker([startlat, startlon], {
        title: "Miasto",
        alt: "Miasto",
        draggable: true,
    }).addTo(map).on('dragend', function() {
        var lat = myMarker.getLatLng().lat.toFixed(8);
        var lon = myMarker.getLatLng().lng.toFixed(8);
        var czoom = map.getZoom();
        if (czoom < 13) {
            nzoom = czoom + 2;
        }
        if (nzoom > 13) {
            nzoom = 13;
        }
        if (czoom != 13) {
            map.setView([lat, lon], nzoom);
        } else {
            map.setView([lat, lon]);
        }


    });


    function chooseAddr(lat1, lng1) {
        myMarker.closePopup();
        map.setView([lat1, lng1], 13);
        myMarker.setLatLng([lat1, lng1]);
        lat = lat1.toFixed(8);
        lon = lng1.toFixed(8);

    }

    // wyszukiwanie miasta 
    function myFunction(arr) {
        var out = "<br />";
        var i;

        if (arr.length > 0) {

            for (i = 0; i < arr.length; i++) {
                out += "<p class='address' title='Pokaż lokalizacje oraz współrzędne' onclick='chooseAddr(" + arr[i].lat + ", " + arr[i].lon + ");return false;'>" + arr[i].display_name + "</p>";
            }
            document.getElementById('results').innerHTML = out;
        } else {
            document.getElementById('results').innerHTML = "Przepraszamy, nie ma takiego miasta, sprawdź pisownie.";
        }

    }

    function addr_search() {
        var inp = document.getElementById("addr");
        var xmlhttp = new XMLHttpRequest();
        var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&q=" + inp.value;
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var myArr = JSON.parse(this.responseText);
                myFunction(myArr);
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    // wyświtlenie pinezek z imieniem i z zakupami


    let userLogged = "<?php echo is_user_logged_in() ?>";

    if (userLogged == 1) {
        for (let i = 0; i < data.length; i++) {
            let location = new L.latLng(data[i].latitude,
                data[i].longtitude);
            var marker = L.marker(location).addTo(map);

            marker.bindPopup(`<b>Imię: ${data[i].imie}</b></br><p>Adres: ${data[i].ulica} ${data[i].nr_domu} ${data[i].kod_pocztowy} ${data[i].miasto}</p><p>lista zakupów: ${data[i].lista}</p><a href="http://localhost:10010/wolontariusz/przegladaj-zlecenia/">Przejdź do wyboru zleceń</a>`).openPopup();
            let obj = data[i];
        }



    } else {
        for (let i = 0; i < data.length; i++) {
            let location = new L.latLng(data[i].latitude,
                data[i].longtitude);
            var marker = L.marker(location).addTo(map);

            marker.bindPopup(`<p>lista zakupów: ${data[i].lista}</p><a href="http://localhost:10010/wolontariusz/przegladaj-zlecenia/">Przejdź do wyboru zleceń</a>`).openPopup();
            let obj = data[i];
        }



    }
</script>
<?php
get_footer()
?>