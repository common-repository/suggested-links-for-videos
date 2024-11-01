<?php


function suggested_links_for_videos_customizer($wp_customize){
    $wp_customize->add_section( 'suggested_links_for_videos_section' , array(
        'title'      => __( 'Suggested links for videos settings', 'clazist' ),
        'priority'   => 99,
    ));

    $wp_customize->add_setting( 'suggested_links_for_videos_icon');
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'suggested_links_for_videos_icon', array(
        'label'      => __( 'Icon', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_icon',
        'description' => __('Recommended size 24x24 px','clazist')
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_direction',['default'=>'left']);
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'suggested_links_for_videos_direction', array(
        'label'      => __( 'Suggestions box direction', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_direction',
        'type'       => 'radio',
        'choices'    => [
            'left' =>  __('Left','clazist'),
            'right' =>  __('Right','clazist')
        ]
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_icon_direction',['default'=>'left']);
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'suggested_links_for_videos_icon_direction', array(
        'label'      => __( 'Suggestions box icon direction', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_icon_direction',
        'type'       => 'radio',
        'choices'    => [
            'left' =>  __('Left','clazist'),
            'right' =>  __('Right','clazist')
        ]
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_background',['default'=>'#4a4949b8']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'suggested_links_for_videos_background', array(
        'label'      => __( 'Suggestions box background', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_background',
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_shadow',['default'=>'#0000001a']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'suggested_links_for_videos_shadow', array(
        'label'      => __( 'Suggestions box shaodw color', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_shadow',
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_color',['default'=>'#fff']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'suggested_links_for_videos_color', array(
        'label'      => __( 'Suggested link color', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_color',
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_font_size',['default'=>'16']);
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'suggested_links_for_videos_font_size', array(
        'label'      => __( 'Suggested link font size', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_font_size',
        'type'       => 'number'
    )));

    $wp_customize->add_setting( 'suggested_links_for_videos_text_align',['default'=>'left']);
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'suggested_links_for_videos_text_align', array(
        'label'      => __( 'Suggested link text align', 'clazist' ),
        'section'    => 'suggested_links_for_videos_section',
        'settings'   => 'suggested_links_for_videos_text_align',
        'type'       => 'radio',
        'choices'    => [
            'left' =>       __('Left','clazist'),
            'center' =>     __('Center','clazist'),
            'right' =>      __('Right','clazist')
        ]
    )));
}
add_action('customize_register','suggested_links_for_videos_customizer');