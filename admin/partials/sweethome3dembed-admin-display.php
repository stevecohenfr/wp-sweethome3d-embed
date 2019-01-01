<?php

test_handle_post();
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
                            $this->customers_obj->prepare_items();
                            $this->customers_obj->display(); ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>
<?php

function test_handle_post(){
    // First check if the file appears on the _FILES array
    if(isset($_FILES['sh3d_upload'])){
        $sh3d = $_FILES['sh3d_upload'];

        // Use the wordpress function to upload
        // sh3d_upload corresponds to the position in the $_FILES array
        // 0 means the content is not associated with any other posts
        $uploaded=media_handle_upload('sh3d_upload', 0);
        // Error checking using WP functions
        if(is_wp_error($uploaded)){
            echo "Error uploading file: " . $uploaded->get_error_message();
        }else{
            echo "File upload successful!<br>";
            require_once plugin_dir_path( dirname( __FILE__ ) ). '../includes/class-sweethome3dembed-database.php';
            Sweethome3dembed_Database::getInstance()->add($uploaded);
        }
    }
}