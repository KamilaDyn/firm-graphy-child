<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Firm Graphy
 * @since Firm Graphy 1.0.0
 */

get_header(); ?>
<div id="inner-content-wrapper" class="wrapper page-section">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="single-wrapper">
                <?php if (is_user_logged_in()) :

                    global $wpdb, $current_user;
                    wp_get_current_user();
                    $results = $wpdb->get_results("SELECT * FROM shopping_list WHERE status_zlecenia = 'oczekujące na przejęcie'  and wolontariusz is null or wolontariusz = 'Wolontariusz' order by id");
                    if (!empty($results)) { ?>

                        <table class='tableList' width='100%'>

                            <thead>
                                <tr>
                                    <th> Id</th>
                                    <th>Zgłaszający</th>
                                    <th> Telefon</th>
                                    <th> Adres</th>
                                    <th> Lista Zakupów</th>
                                    <th>Zakładana kwota</th>
                                    <th>Uwagi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                <?php
                                foreach ($results as $row) { ?>
                                    <tr class='trow '>
                                        <?php if ($row->id != 1) { ?>
                                            <td><?php echo $row->id ?> </td>
                                            <td><?php echo $row->imie . ' ' . $row->nazwisko ?></td>
                                            <td><?php echo  $row->telefon ?></td>
                                            <td><?php echo  $row->kod_pocztowy . ' ' . $row->miasto . ', ' . $row->ulica . ' ' . $row->nr_domu ?></td>
                                            <td><?php echo  $row->lista ?></td>
                                            <td><?php echo $row->kwota ?></td>
                                            <td><?php echo  $row->uwagi ?></td>
                                            <td><?php echo  $row->status_zlecenia ?></td>
                                        <?php   }  ?>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>

                    <?php } ?>
                    <hr>
                    <h3>Chcesz przejąć któreś zlecenie? Wpisz ponizej jego numer i potwierdź przyciskiem! </h3>
                    <form action='' method='post'>
                        <table>
                            <tr>
                                <td>Numer zlecenia:</td>
                                <td><input class="take_nr" type='text' name='numer_zlec' title='Podaj numer zlecenia' value=''></td>
                            </tr>
                            <input id="userlogin" style="display:hidden" type='hidden' name='userlogin' title='Podaj login' value='<?php echo $current_user->user_login;  ?>'>
                        </table>
                        <input class="btntake" type='submit' name='takeitem' value='Przejmij zlecenie'>
                    </form>
                <?php
                else : ?>
                    <h3>Nie masz dostępu do panelu, musisz być zalogowany. Jeśli nie masz konta, a chcesz pomóc to <span> <a href="<?php echo site_url('/register'); ?>" class="login-btn"> przejdz do rejestracji </a></span> </h3>
                <?php endif; ?>
            </div><!-- .single-wrapper -->
        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .page-section -->

<?php get_footer();
