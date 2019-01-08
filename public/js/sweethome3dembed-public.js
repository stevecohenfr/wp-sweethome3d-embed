(function( $ ) {
    'use strict';
    $(document).ready(function() {
        if ($('.sweethome3d-model').length > 0) {
            var w = $('.sweethome3d-model').width();
            var h = w * 600 / 800;
            $('#viewerCanvas').attr('width', w).attr('height', h);
            $('#viewerProgressDiv').css('top', '-'+((h/2)+50)+'px').css('left', w/2-200-20 + 'px');
            var homeUrl = $('.sweethome3d-model').attr('data-model');
            var onerror = function(err) {
                if (err == "No WebGL") {
                    alert("Sorry, your browser doesn't support WebGL.");
                } else {
                    console.log(err.stack);
                    alert("Error: " + (err.message  ? err.constructor.name + " " +  err.message  : err));
                }
            };
            var onprogression = function(part, info, percentage) {
                var progress = document.getElementById("viewerProgress");
                if (part === HomeRecorder.READING_HOME) {
                    // Home loading is finished
                    progress.value = percentage * 100;
                    info = info.substring(info.lastIndexOf('/') + 1);
                } else if (part === Node3D.READING_MODEL) {
                    // Models loading is finished
                    progress.value = 100 + percentage * 100;
                    if (percentage === 1) {
                        document.getElementById("viewerProgressDiv").style.display = "none";
                    }
                }

                document.getElementById("viewerProgressLabel").innerHTML =
                    (percentage ? Math.floor(percentage * 100) + "% " : "") + part + " " + info;
            };

            // Display home in canvas 3D
            // Mouse and keyboard navigation explained at
            // http://sweethome3d.cvs.sf.net/viewvc/sweethome3d/SweetHome3D/src/com/eteks/sweethome3d/viewcontroller/resources/help/en/editing3DView.html
            // You may also switch between aerial view and virtual visit with the space bar
            // For browser compatibility, see http://caniuse.com/webgl
            viewHome("viewerCanvas",    // Id of the canvas
                homeUrl,           // URL or relative URL of the home to display
                onerror,           // Callback called in case of error
                onprogression,     // Callback called while loading
                {roundsPerMinute: 1,                    // Rotation speed of the animation launched once home is loaded in rounds per minute, no animation if missing or equal to 0
                    navigationPanel: "none",               // Displayed navigation arrows, "none" or "default" for default one or an HTML string containing elements with data-simulated-key
                                                           // attribute set "UP", "DOWN", "LEFT", "RIGHT"... to replace the default navigation panel, "none" if missing
                    //aerialViewButtonId: "aerialView",      // Id of the aerial view radio button, radio buttons hidden if missing
                    //virtualVisitButtonId: "virtualVisit",  // Id of the aerial view radio button, radio buttons hidden if missing
                    //levelsAndCamerasListId: "levelsAndCameras"   // Id of the levels select component, hidden if missing
                    /*, selectableLevels: ["Level 0", "Level 1"] */  // List of displayed levels, all viewable levels if missing
                });
        }
    });
})( jQuery );
