<?php

function firm_graphy_customize_register($wp_customize)
{
    $options = firm_graphy_get_theme_options();

    // Load custom control functions.
    require get_template_directory() . '/inc/customizer/custom-controls.php';

    // Load customize active callback functions.
    require get_template_directory() . '/inc/customizer/active-callback.php';

    // Load partial callback functions.
    require get_template_directory() . '/inc/customizer/partial.php';

    // Load validation callback functions.
    require get_template_directory() . '/inc/customizer/validation.php';

    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    // Remove the core header textcolor control, as it shares the main text color.
    $wp_customize->remove_control('header_textcolor');

    // Header title color setting and control.
    $wp_customize->add_setting('firm_graphy_theme_options[header_title_color]', array(
        'default' => $options['header_title_color'],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'firm_graphy_theme_options[header_title_color]', array(
        'priority' => 5,
        'label' => esc_html__('Header Title Color', 'firm-graphy'),
        'section' => 'colors',
    )));

    // Header tagline color setting and control.
    $wp_customize->add_setting('firm_graphy_theme_options[header_tagline_color]', array(
        'default' => $options['header_tagline_color'],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'firm_graphy_theme_options[header_tagline_color]', array(
        'priority' => 6,
        'label' => esc_html__('Header Tagline Color', 'firm-graphy'),
        'section' => 'colors',
    )));






    // Site identity extra options.
    $wp_customize->add_setting('firm_graphy_theme_options[header_txt_logo_extra]', array(
        'default' => $options['header_txt_logo_extra'],
        'sanitize_callback' => 'firm_graphy_sanitize_select',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control('firm_graphy_theme_options[header_txt_logo_extra]', array(
        'priority' => 50,
        'type' => 'radio',
        'label' => esc_html__('Site Identity Extra Options', 'firm-graphy'),
        'section' => 'title_tagline',
        'choices' => array(
            'hide-all' => esc_html__('Hide All', 'firm-graphy'),
            'show-all' => esc_html__('Show All', 'firm-graphy'),
            'title-only' => esc_html__('Title Only', 'firm-graphy'),
            'tagline-only' => esc_html__('Tagline Only', 'firm-graphy'),
            'logo-title' => esc_html__('Logo + Title', 'firm-graphy'),
            'logo-tagline' => esc_html__('Logo + Tagline', 'firm-graphy'),
        )
    ));

    // Add panel for common theme options
    $wp_customize->add_panel('firm_graphy_theme_options_panel', array(
        'title' => esc_html__('Theme Options', 'firm-graphy'),
        'description' => esc_html__('Firm Graphy Theme Options.', 'firm-graphy'),
        'priority' => 150,
    ));


    // Login in Header
    $wp_customize->add_setting('firm_graphy_theme_options[login_enable]', array(
        'default' => $options['login_enable'],
        'sanitize_callback' => 'firm_graphy_sanitize_switch_control',
    ));

    $wp_customize->add_control(new Firm_Graphy_Switch_Control($wp_customize, 'firm_graphy_theme_options[login_enable]', array(
        'label' => esc_html__('Login Enalble', 'firm-graphy'),
        'section' => 'title_tagline',
        'on_off_label' => firm_graphy_switch_options(),
    )));

    // load layout
    require get_template_directory() . '/inc/customizer/theme-options/homepage-static.php';

    // breadcrumb
    require get_template_directory() . '/inc/customizer/theme-options/breadcrumb.php';

    // load layout
    require get_template_directory() . '/inc/customizer/theme-options/layout.php';

    // load archive option
    require get_template_directory() . '/inc/customizer/theme-options/excerpt.php';

    // load archive option
    require get_template_directory() . '/inc/customizer/theme-options/archive.php';

    // load single post option
    require get_template_directory() . '/inc/customizer/theme-options/single-posts.php';

    // load pagination option
    require get_template_directory() . '/inc/customizer/theme-options/pagination.php';

    // load footer option
    require get_template_directory() . '/inc/customizer/theme-options/footer.php';

    // load reset option
    require get_template_directory() . '/inc/customizer/theme-options/reset.php';

    // Add panel for front page theme options.
    $wp_customize->add_panel('firm_graphy_front_page_panel', array(
        'title' => esc_html__('Front Page', 'firm-graphy'),
        'description' => esc_html__('Front Page Theme Options.', 'firm-graphy'),
        'priority' => 140,
    ));

    // load banner option
    require get_template_directory() . '/inc/customizer/sections/banner.php';

    // load service option
    require get_template_directory() . '/inc/customizer/sections/service.php';

    // load about option
    require get_template_directory() . '/inc/customizer/sections/about.php';

    // load latest option
    require get_template_directory() . '/inc/customizer/sections/latest.php';

    // load introduction option
    require get_template_directory() . '/inc/customizer/sections/introduction.php';

    // load event option
    require get_template_directory() . '/inc/customizer/sections/event.php';

    // load client option
    require get_template_directory() . '/inc/customizer/sections/client.php';

    // load blog option
    require get_template_directory() . '/inc/customizer/sections/blog.php';
}
add_action('customize_register', 'firm_graphy_customize_register');
