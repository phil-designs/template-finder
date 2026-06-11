<?php
/**
 * Plugin Name:       PhilDesigns Template Finder
 * Plugin URI:        https://phildesigns.com
 * Description:       Find all pages using a specific page template, with links to view and edit each page directly from the WordPress admin.
 * Version:           1.0.0
 * Author:            PhilDesigns
 * Author URI:        https://phildesigns.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       template-finder
 * Domain Path:       /languages
 * Requires at least: 6.7
 * Tested up to:      7.0
 * Requires PHP:      7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', function () {
    add_management_page(
        'Template Finder',
        'Template Finder',
        'edit_pages',
        'template-finder',
        'template_finder_render_page'
    );
} );

function template_finder_get_all_templates(): array {
    $templates = wp_get_theme()->get_page_templates();
    // wp_get_theme returns [ 'path/file.php' => 'Template Name' ]
    // Add the default template option
    $all = [ 'default' => 'Default Template' ];
    foreach ( $templates as $file => $name ) {
        $all[ $file ] = $name . ' (' . $file . ')';
    }
    return $all;
}

function template_finder_query( string $template ): array {
    $meta_query = $template === 'default'
        ? [
            'relation' => 'OR',
            [ 'key' => '_wp_page_template', 'value' => 'default', 'compare' => '=' ],
            [ 'key' => '_wp_page_template', 'compare' => 'NOT EXISTS' ],
          ]
        : [
            [ 'key' => '_wp_page_template', 'value' => $template, 'compare' => '=' ],
          ];

    $query = new WP_Query( [
        'post_type'      => 'page',
        'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ],
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => $meta_query,
        'no_found_rows'  => true,
    ] );

    return $query->posts;
}

function template_finder_render_page(): void {
    $templates   = template_finder_get_all_templates();
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET search; no state is modified.
    $selected    = isset( $_GET['tpl'] ) ? sanitize_text_field( wp_unslash( $_GET['tpl'] ) ) : '';
    $results     = [];
    $searched    = false;

    if ( $selected !== '' && ( $selected === 'default' || array_key_exists( $selected, $templates ) ) ) {
        $results  = template_finder_query( $selected );
        $searched = true;
    }

    $status_labels = [
        'publish' => 'Published',
        'draft'   => 'Draft',
        'pending' => 'Pending',
        'private' => 'Private',
        'future'  => 'Scheduled',
    ];
    ?>
    <div class="wrap">
        <h1>Template Finder</h1>
        <p>Select a page template to see all pages assigned to it.</p>

        <form method="get" action="">
            <input type="hidden" name="page" value="template-finder">
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="tpl">Page Template</label></th>
                    <td>
                        <select name="tpl" id="tpl" style="min-width:320px;">
                            <option value="">— Choose a template —</option>
                            <?php foreach ( $templates as $file => $label ) : ?>
                                <option value="<?php echo esc_attr( $file ); ?>" <?php selected( $selected, $file ); ?>>
                                    <?php echo esc_html( $label ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php submit_button( 'Search', 'primary', 'submit', false ); ?>
                    </td>
                </tr>
            </table>
        </form>

        <?php if ( $searched ) : ?>
            <hr>
            <h2>
                Results for <em><?php echo esc_html( $templates[ $selected ] ?? $selected ); ?></em>
                <span class="title-count theme-count"><?php echo count( $results ); ?></span>
            </h2>

            <?php if ( empty( $results ) ) : ?>
                <p>No pages found using this template.</p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width:40px;">ID</th>
                            <th>Page Title</th>
                            <th style="width:110px;">Status</th>
                            <th style="width:200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $results as $page ) :
                            $view_url = get_permalink( $page->ID );
                            $edit_url = get_edit_post_link( $page->ID, 'raw' );
                            $status   = $status_labels[ $page->post_status ] ?? ucfirst( $page->post_status );
                        ?>
                        <tr>
                            <td><?php echo esc_html( $page->ID ); ?></td>
                            <td>
                                <strong><?php echo esc_html( $page->post_title ?: '(no title)' ); ?></strong>
                                <?php if ( $page->post_parent ) :
                                    $parent = get_the_title( $page->post_parent );
                                ?>
                                    <br><small style="color:#888;">Child of: <?php echo esc_html( $parent ); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="
                                    display:inline-block;
                                    padding:2px 8px;
                                    border-radius:3px;
                                    font-size:11px;
                                    font-weight:600;
                                    background:<?php echo esc_attr( $page->post_status === 'publish' ? '#d4edda' : '#fff3cd' ); ?>;
                                    color:<?php echo esc_attr( $page->post_status === 'publish' ? '#155724' : '#856404' ); ?>;
                                ">
                                    <?php echo esc_html( $status ); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ( $view_url ) : ?>
                                    <a href="<?php echo esc_url( $view_url ); ?>" target="_blank" class="button button-small">View</a>
                                <?php endif; ?>
                                <?php if ( $edit_url ) : ?>
                                    <a href="<?php echo esc_url( $edit_url ); ?>" class="button button-small">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}
