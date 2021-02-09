<?php

/*remove parent function */
// function child_remove_parent_functions()
// {
//     remove_action('customize_register', 'firm_graphy_customize_register');
// }
// add_action('init', 'child_remove_parent_functions');


function firm_graphy_scripts_child()
{
    wp_enqueue_style('parent-style',   get_stylesheet_directory_uri()  . '/style.css');
    wp_enqueue_style('child-style',  get_template_directory_uri() .  '/style.css', array('parent-style'));
    wp_enqueue_style('leaflet',   get_stylesheet_directory_uri() . '/assets/leaflet/leaflet.css');
    // $variables = array(
    //     'ajaxurl' => admin_url('admin-ajax.php')
    // );
    wp_enqueue_script('submit-ajax',   get_stylesheet_directory_uri() . '/assets/js/submitform.js', array('jquery'));

    wp_localize_script('submit-ajax', 'ajax_object', ['ajax_url' => admin_url('admin-ajax.php')]);

    // pass Ajax Url to script.js

    wp_localize_script('firm-graphy-custom', 'firmgraphyData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
    wp_enqueue_script('submit-ajax');

    if (is_page('Mapa z zleceniami')) {
        wp_enqueue_script('leaflet', get_stylesheet_directory_uri()  . '/assets/leaflet/leaflet.js');
        wp_enqueue_script('leaflet-src', get_stylesheet_directory_uri()  . '/assets/leaflet/leaflet-src.js');
    }
    if (is_page('Moje zlecenia')) {
        wp_enqueue_script('create-pdf', get_stylesheet_directory_uri() . '/assets/js/create-pdf.js');
    }
}
add_action('wp_enqueue_scripts', 'firm_graphy_scripts_child');
add_action('wp_ajax_set_form', 'set_form');
add_action('wp_ajax_nopriv_set_form', 'set_form');

add_action('wp_ajax_return_item', 'return_item');
add_action('wp_ajax_finished_item', 'finished_item');
add_action('wp_ajax_take_item', 'take_item');
// add_action('wp_ajax_nopriv_return_item', 'return_item');


/* ajax action */


function take_item()
{
    global $wpdb;
    $id_zlec =  $_POST['numer_zlec'];
    $login = $_POST['userlogin'];
    echo $id_zlec;
    $updateData =  $wpdb->update('shopping_list', array('wolontariusz' => $login, 'status_zlecenia' => 'do realizacji'), array('id' => $id_zlec));
    if ($updateData) {

        echo json_encode(array('res' => true, 'message' => __('New row has been inserted.')));
    } else {
        echo json_encode(array('res' => true, 'message' => __('New row has not been inserted.')));
    };
    exit;
}


function return_item()
{
    global $wpdb;
    $id_zlec =  $_POST['nr_return'];
    echo $id_zlec;
    $updateData =  $wpdb->update('shopping_list', array('wolontariusz' => null, 'status_zlecenia' => 'oczekujące na przejęcie'), array('id' => $id_zlec));
    if ($updateData) {

        echo json_encode(array('res' => true, 'message' => __('New row has been inserted.')));
    } else {
        echo json_encode(array('res' => true, 'message' => __('New row has not been inserted.')));
    };
    exit;
}


function finished_item()
{
    global $wpdb, $currentUser;
    $id_zlec = $_POST['nr_finished'];
    $login = $_POST['login'];
    echo $id_zlec;
    $updateData = $wpdb->update('shopping_list', array('wolontariusz' => $login, 'status_zlecenia' => 'wykonane'), array('id' => $id_zlec));
    if ($updateData) {

        echo json_encode(array('res' => true, 'message' => __('New row has been inserted.')));
    } else {
        echo json_encode(array('res' => true, 'message' => __('New row has not been inserted.')));
    };
    exit;
}

function set_form()
{
    global $wpdb;
    $name = sanitize_text_field($_POST['firstname']);
    $lastname = sanitize_text_field($_POST['lastname']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $street_name = sanitize_text_field($_POST['street_name']);
    $home_number = sanitize_text_field($_POST['home_number']);
    $postalcode = sanitize_text_field($_POST['postcode']);
    $city = sanitize_text_field($_POST['city']);
    $shopping_list = sanitize_text_field($_POST['shop_list']);
    $max_money = sanitize_text_field($_POST['max_money']);
    $extra_info = sanitize_text_field($_POST['extra_info']);


    // $city = $_POST['city'];
    // $street = $_POST['street_name'];
    // $postcode = $_POST['postcode'];
    // $home_nr = $_POST['home_number'];

    $tableName = 'shopping_list';
    $insert_row = $wpdb->insert(
        $tableName,
        array(
            'imie' => $name,
            'nazwisko' => $lastname,
            'telefon' => $phone_number,
            'ulica' => $street_name,
            'miasto' => $city,
            'nr_domu' => $home_number,
            'kod_pocztowy' => $postalcode,
            'lista' => $shopping_list,
            'kwota' => $max_money,
            'uwagi' => $extra_info,
            'status_zlecenia' => 'oczekujące na przejęcie',
        )
    );



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
    // $rowResult = $wpdb->insert($table_name, $data_array, $format = NULL);

    $last_id = $wpdb->insert_id;
    // wysłanie lat i long do tabeli ades id jest takie jak przy wysłaniu danych do listy
    $json_insert = $wpdb->prepare("INSERT INTO adres (`id`,`latitude`, `longtitude`) VALUES('" . $last_id . "','" . $lat . "', '" . $lon . "' )");
    $table_adress = "adres";
    // potwierdzenie wysłania,

    if ($insert_row) {
        $wpdb->query($json_insert);
        echo json_encode(array('res' => true, 'message' => __('New row has been inserted.')));
    } else {
        echo json_encode(array('res' => false, 'message' => __('Something went wrong. Please try again later.')));
    }
    exit;
}



/* customize login page */
function ourheaderurl()
{
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'ourheaderurl');

/*change login style */
function my_login_CSS()
{
    wp_enqueue_style('custom-login',  get_stylesheet_directory_uri() . '/style.css');
}
add_action('login_enqueue_scripts', 'my_login_CSS');


/*change login headline title */

function myLoginTitle()
{
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'myLoginTitle');



if (!function_exists('firm_graphy_site_branding')) :
    /**
     * Site branding codes
     *
     * @since Firm Graphy 1.0.0
     *
     */
    function firm_graphy_site_branding()
    {
        $options  = firm_graphy_get_theme_options();
        $header_txt_logo_extra = $options['header_txt_logo_extra'];

?>
        <div class="login-container">
            <?php if (is_user_logged_in()) : ?>

                <a href="<?php echo wp_logout_url(); ?>" class="login-btn">Wyloguj</a>
            <?php else : ?>
                <a href="<?php echo site_url('/my-account'); ?>" class="login-btn">Zaloguj</a>
                <a href="<?php echo site_url('/register');  ?>" class="login-btn">Zarejestruj się</a>

            <?php endif ?>
        </div>
        <div class="site-branding">

            <?php if (in_array($header_txt_logo_extra, array('show-all', 'logo-title', 'logo-tagline'))) { ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php }
            if (in_array($header_txt_logo_extra, array('show-all', 'title-only', 'logo-title', 'show-all', 'tagline-only', 'logo-tagline'))) : ?>
                <div id="site-details">
                    <?php
                    if (in_array($header_txt_logo_extra, array('show-all', 'title-only', 'logo-title'))) {
                        if (firm_graphy_is_latest_posts()) : ?>
                            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                        <?php else : ?>
                            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                        <?php
                        endif;
                    }
                    if (in_array($header_txt_logo_extra, array('show-all', 'tagline-only', 'logo-tagline'))) {
                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) : ?>
                            <p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
                    <?php
                        endif;
                    }
                    $about_enable = apply_filters('firm_graphy_section_status', true, 'login_enable');
                    if (true !== $about_enable) {
                        return false;
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div><!-- .site-branding -->
<?php
    }
endif;
add_action('firm_graphy_header_action', 'firm_graphy_site_branding', 20);


/* hide admin bar */

function noSubsAdminBar()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and  $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

add_action('admin_init', 'noSubsAdminBar');
