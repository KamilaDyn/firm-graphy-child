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

get_header();
global $wpdb, $current_user;
wp_get_current_user();
$login = $current_user->user_login;

?>
<div id="inner-content-wrapper" class="wrapper page-section">
  <div class="content-area">
    <main id="main" class="site-main" role="main">
      <div class="single-wrapper">
        <div class="in_progress">

          <?php
          $results = $wpdb->get_results("SELECT * FROM shopping_list WHERE status_zlecenia = 'wykonane'  and  wolontariusz = '$login'   or wolontariusz = 'Wolontariusz' order by id");

          if (is_user_logged_in()) {
            if (count($results) > 0) {
          ?>
              <h3> Poniżej znajdują się Twoje wszystkie zrealizowane zadania</h3>
              <input type='button' value='utwórz pdf' id='btPrint' onclick='createPDF()' />
              <div id='table-content'>
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
                          <td><?php echo $row->status_zlecenia; ?>
                          <?php   }  ?>
                      </tr>
                    <?php } ?>
                  </tbody>

                </table>

              <?php } else { ?>
                <div>
                  <p>Nie masz zrealizowanych zleceń. </p>
                  <p>Przejdź do strony <a href="<?php echo site_url('/wolontariusz/moje-zlecenia/'); ?>">moje zlecenia </a> i zrealizuj zadanie.</p>
                  <p>Jeśli nie masz wybranego zadania, przejdź do zakładki <a href="<?php echo site_url('/wolontariusz/przegladaj-zlecenia/'); ?>"> przeglądaj zlecenia </a> i wybierz zlecenie do realizacji. </p>
                  <p>Dziękujemy, że pmagasz innym ❤️</p>
                </div>
              <?php  }
          } else { ?>
              <div>
                <p>Ten panel, jest dla zalogowanych użytkoników.</p>
                <p> Jeśli chcesz pomóc, <a href="<?php echo site_url('/register'); ?>"> zarejestruj się.</a> </p>
                <p> Jeśli masz już konto, <a href="<?php echo site_url('/my-account'); ?>">zaloguj się</a> i przeglądaj swoje zlecenia.</p>
              </div>
            <?php } ?>

              </div>

        </div><!-- .single-wrapper -->

      </div><!-- .single-wrapper -->
    </main><!-- #main -->
  </div><!-- #primary -->
</div><!-- .page-section -->

<?php get_footer();
