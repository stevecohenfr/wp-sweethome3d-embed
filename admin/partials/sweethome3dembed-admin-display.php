<?php

esh3d_handle_upload();
?>
    <div class="wrap">
        <h1>SweetHome3D Manager</h1>
        <!-- Form to handle the upload - The enctype value here is very important -->
        <form  method="post" enctype="multipart/form-data">
            <input type='file' id='sh3d_upload' name='sh3d_upload' accept=".zip" />
            <?php submit_button('Upload') ?>
        </form>

        <h2>Your models</h2>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="post">
                            <?php
                            $this->sh3ds_obj->prepare_items();
                            $this->sh3ds_obj->display(); ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>
<?php

function esh3d_handle_upload(){
    // First check if the file appears on the _FILES array
    if(isset($_FILES['sh3d_upload'])){
        $uploaded=media_handle_upload('sh3d_upload', 0);
        // Error checking using WP functions
        if(is_wp_error($uploaded)){
            echo '<div class="notice notice-error is-dismissible"><p>Upload fail!</p></div>';
            wp_redirect( get_permalink() );
        }else{
            require_once plugin_dir_path( dirname( __FILE__ ) ). '../includes/class-sweethome3dembed-database.php';
            Sweethome3dembed_Database::getInstance()->add($uploaded);
            echo '<div class="notice notice-success is-dismissible"><p>Upload success!</p></div>';
            wp_redirect( get_permalink() );
        }
    }
}