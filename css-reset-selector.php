<?php

/**
 * Plugin Name:     CSS Reset Selector
 * Plugin URI:      https://github.com/JACGWD/css-reset-selector
 * Description:     Easily manage CSS reset files.
 * Version:         0.0.3
 * Author:          Dave Woodhall & Eric Girouard
 * Author URI:      https://graphicandwebdesign.ca
 * Text Domain:     css_reset_selector
 * Domain Path:     /languages/
 * License:         GPL v3
 *
 * @package         JACGWD
 */


if( !class_exists('jacgwd_reset') ) {
    // If the class does not already exist, create it.
    
    
    class jacgwd_reset {
        /**
         * The __construct is called with new {class_name}.
         */
        function __construct(){
            // On "admin_init" (a WP hook), do something cool.
            // Second parameter is an array *because we are in a class*.
            // $this refers to this class, 'register_settings' is the function in our class.
            add_action('admin_init', array($this, 'register_settings'));
            
            // Tell WP to generate the admin options page for this plugin
            add_action('admin_menu', array($this, 'register_options_page'));

            // Tell WordPress to include the reset file
            add_action('wp_enqueue_scripts', array($this, "include_reset"));
        }
        
        /**
         * Registers the option in the database, table "{prefix}options"
         */
        function register_settings(){
            add_option(
                'jacgwd_reset_selector', // This is saved in the database
                '' // Default value
            );
            register_setting(
                'jacgwd_reset_options', // This is a group of options, I like to stick to 1 group (CREATIVELY lazy like that)
                'jacgwd_reset_selector' // The name of the option as named above
            );
        }
        
        /**
         * Creates plugin options page
         */
        function register_options_page(){
            add_options_page(
                'CSS Reset Selector', // <title>...</title>
                'CSS Reset Selector', // in admin menu
                'manage_options', // WP User Capabilities (permissions) required
                'jacgwd-reset', // slug/URL
                array($this, 'output_options_page') // Function that outputs the HTML in the admin page ($this being "in this class/instance")
            );
        }
        
        /**
         * Outputs the HTML in the admin page
         */
        function output_options_page(){
            // Wrap inside admin page container
            ?>

            <div class="wrap">
                <h1>CSS Reset Selector</h1>
                <p class="description">Choose which CSS Reset method you wish to use.</p>
                
                <?php
                    // options.php -> WP file that handles saving to database
                    // uses POST method
                ?>
                <form action="options.php" method="post">
                    <?php
                       // settings_errors(); // Outputs any errors here
            
                        settings_fields('jacgwd_reset_options'); // The group of options
                        do_settings_sections('jacgwd_reset_selector'); // Our custom setting name
                    ?>
                    
                    <?php
                        // START OUR FIELDS
                        // ==================================================
                        
                        // Look in {prefix}options table for option_name('THIS_VALUE')
                        $preference = get_option('jacgwd_reset_selector');
                    ?>
                    
                    
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Option</th>
                                <th>Your preference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Reset type</td>
                                <td>
                                    <label><input type="radio" name="jacgwd_reset_selector" value="" <?php if($preference == '') echo "checked='checked'"; ?> /> None</label> <br />
                                    <label><input type="radio" name="jacgwd_reset_selector" value="eric_meyer_20" <?php if($preference == 'eric_meyer_20') echo "checked='checked'"; ?> /> Eric Meyer Classic</label> <br />
                                    <label><input type="radio" name="jacgwd_reset_selector" value="eric_meyer_22" <?php if($preference == 'eric_meyer_22') echo "checked='checked'"; ?> /> Eric Meyer (with Position: Relative for Everything)</label> <br />
                                    <label><input type="radio" name="jacgwd_reset_selector" value="normalize"  <?php if($preference == 'normalize')  echo "checked='checked'"; ?> /> Normalize</label> <br />
                                    <label><input type="radio" name="jacgwd_reset_selector" value="andy_bell"  <?php if($preference == 'andy_bell')  echo "checked='checked'"; ?> /> Andy Bell</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        // END OUR FIELDS
                        // ==================================================
                    ?>
                    
                    <?php
                     /**
                      * Submit button, using WP classes and admin design
                      */
                    ?>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save reset method" />
                    </p>
                </form>
            </div>

            <?php
        
    }
    // Includes the CSS reset
    function include_reset() {

        // Look in {prefix}options table for option_name('THIS_VALUE')
        $preference = get_option('jacgwd_reset_selector');

        // if (empty($preference)) return;  // stop if preference not set

        // if ($preference == "eric_meyer") {
        //     wp_enqueue_style("reset-meyer", plugin_dir_url(__FILE__)."reset/simple-css-reset-v2.1.css");
        // } 
        
        // else if ($preference == "normalize") {
        //     wp_enqueue_style("reset-normalize", plugin_dir_url(__FILE__)."reset/normalize.v8.01.css");
        // } 
    

        // else if ($preference == "andy_bell") {
        //     wp_enqueue_style("reset-simple", plugin_dir_url(__FILE__)."reset/modern-css-reset.css"); 
        // }


        switch($preference){  // you can use switch or the if/else above - they do the same thing
            default: 
            // if no case is active do nothing
            break; 
            
            case "eric_meyer_20": 
                wp_enqueue_style("reset-meyer_20", plugin_dir_url(__FILE__)."reset/simple-css-reset-v2.0.css");
            break;

            case "eric_meyer_22": 
                wp_enqueue_style("reset-meyer_22", plugin_dir_url(__FILE__)."reset/simple-css-reset-v2.2.css");
            break;

            case "normalize": 
                wp_enqueue_style("normalize", plugin_dir_url(__FILE__)."reset/normalize.v8.0.1.css");
            break;

            case "andy_bell": 
                wp_enqueue_style("andy_bell", plugin_dir_url(__FILE__)."reset/modern-css-reset.css");
            break;
        }


    }  // end function

} //  end class
    
    // We need to call the class for it to work
    $jacgwd_reset = new jacgwd_reset();
}
