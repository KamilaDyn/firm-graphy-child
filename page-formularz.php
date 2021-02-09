<?php

/**
 * The template for displaying form
 *
 *  Template Name: Shopping-Form
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Firm Graphy
 * @since Firm Graphy 1.0.0
 */

get_header();
?>


<div id="inner-content-wrapper" class="wrapper page-section">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="single-wrapper form-container">
                <?php

                while (have_posts()) : the_post();

                    get_template_part('template-parts/content', 'page');

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
                <form id="form" method="post" name="shopping_list">
                    <div class="form-group">
                        <label for="firstname">Imię:</label>
                        <input id="firstname" class="name" type="text" name="firstname" required>

                    </div>
                    <div class="form-group">
                        <label for="lastname">Nazwisko:</label>
                        <input id="lastname" type="text" name="lastname">

                    </div>
                    <div class="form-group">
                        <label for="phone_number">Numer telefonu</label>
                        <input id="phone_number" type="text" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="street_name">Ulica</label>
                        <input id="street_name" type="text" name="street_name" required>
                    </div>

                    <div class="form-group">
                        <label for="home_number">Numer domu</label>
                        <input id="home_number" type="text" name="home_number">
                    </div>

                    <div class="form-group">
                        <label for="postcode">Kod pocztowy</label>
                        <input id="postcode" type="text" name="postcode" required>
                    </div>
                    <div class="form-group">
                        <label for="city">Miasto</label>
                        <input type="text" name="city" id="city" required>
                    </div>

                    <div class="form-group">
                        <label for="shop_list">Lista zakupów</label>
                        <textarea id="shop_list" name="shop_list" rows="4" cols="50" style="border: 1px solid black;" required></textarea><span><?php echo $listErr; ?></span>
                    </div>

                    <div class=" form-group">
                        <label for="max_money">Maksymalna kwota przeznaczona na zakupy</label>
                        <input type="text" id="max_money" name="max_money" required>
                    </div>

                    <div class="form-group">
                        <label for="extra_info">Dodatkowe uwagi (np. alergie pokarmowe, cukrzyca, forma kontaktu)</label>
                        <textarea id="extra_info" name="extra_info" rows="4" cols="50" style="border: 1px solid black;"></textarea>
                    </div>
                    <input type="hidden" id="status_zl" name="status_zl" value="oczekujące na przejęcie">
                    <div class="form-group">
                        <input type="submit" id="btnSubmit" value="Wyślij" name="btnSubmit">
                    </div>

                </form>
            </div><!-- .page-section -->

            <div id="message"></div>
        </main>
    </div>
</div>
<?php
get_footer();
