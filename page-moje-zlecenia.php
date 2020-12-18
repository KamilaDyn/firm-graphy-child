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
          $results = $wpdb->get_results("SELECT * FROM shopping_list WHERE wolontariusz = '$login'  and status_zlecenia = 'do realizacji'  or wolontariusz
                = 'Wolontariusz' order by id");

          if (is_user_logged_in()) {
            if (count($results) > 0) {
          ?>
              <h3> Poniżej znajdują się Twoje wszystkie zlecenia do realizacji</h3>
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
                      <th>Aktualizuj</th>
                    </tr>
                  </thead>
                  <tbody class="tbody">
                    <?php
                    foreach ($results as $row) { ?>
                      <tr class='trow' id="currentitem">
                        <?php if ($row->id != 1) { ?>
                          <td><?php echo $row->id ?> </td>
                          <td><?php echo $row->imie . ' ' . $row->nazwisko ?></td>
                          <td><?php echo  $row->telefon ?></td>
                          <td>
                            <?php echo  $row->ulica . ' ' . $row->nr_domu  . ', ' . $row->kod_pocztowy . ', ' . $row->miasto ?>
                          </td>
                          <td><?php echo  $row->lista ?></td>
                          <td><?php echo $row->kwota ?></td>
                          <td><?php echo  $row->uwagi ?></td>
                          <td><?php echo $row->status_zlecenia; ?>
                          <td>
                            <?php $updateRow = "{$row->id}"; ?>
                            <form method="post">
                              <input class="finished-item" type='hidden' name='nr_finished' value='<?php echo $row->id; ?>' title='Podaj nowy status zlecenia'>
                              <input id='login' style="display:hidden" type='hidden' name='login' value='<?php echo $current_user->user_login;  ?>'> <input class="btnfinish" type='submit' name='submit_finished' value='wykonane'>
                            </form>
                            <form method="post">
                              <input class="return-item" type='hidden' name='nr_return' value='<?php echo $row->id; ?>' title='Podaj nowy status zlecenia'> <input class="btnreturn" id="<?php echo $row->id; ?>" type='submit' name='submit_return' value='zwróć zadanie'>
                            </form>
                          </td>
                        <?php   }  ?>
                      </tr>
                    <?php } ?>
                  </tbody>

                </table>
                <div id="return-msg"></div>

              <?php } else { ?>
                <div>
                  <p>Nie masz przejętych żadnych zleceń, możesz przejść do sekcji przgląd zleceń i wybrać osobę, której
                    chcesz pomóc.</p>
                </div>
              <?php     }
          } else { ?>
              <div>
                <p>Ten panel, jest dla zalogowanych użytkoników.</p>
                <p> Jeśli chcesz pomóc, <a href="<?php echo site_url('/register'); ?>"> zarejestruj się.</a> </p>
                <p> Jeśli masz już konto, <a href="<?php echo site_url('/my-account'); ?>">zaloguj się</a> i przeglądaj swoje zlecenia.</p>
              </div>
            <?php  } ?>


              </div>
        </div>
    </main><!-- #main -->
  </div><!-- #primary -->
</div><!-- .page-section -->

<?php get_footer();
