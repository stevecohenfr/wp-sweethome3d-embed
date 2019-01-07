<div class="sweethome3d-model" data-model="{{model_url}}">
    <!-- Copy the following canvas and components in your page, changing their size / texts and other values if needed  -->
    <canvas id="viewerCanvas" class="viewerComponent sh3dcanvas" style="background-color: transparent; outline:none" tabIndex="1"></canvas>
    <div id="viewerProgressDiv" style="width: 400px; position: relative; top: -350px; left: 200px; background-color: rgba(128, 128, 128, 0.7); padding: 20px; border-radius: 25px">
        <progress id="viewerProgress"  class="viewerComponent" value="0" max="200" style="width: 400px"></progress>
        <label id="viewerProgressLabel" class="viewerComponent" style="margin-top: 2px; display: block; margin-left: 10px"></label>
    </div>
    <div class="copyright">
        <a href="http://www.sweethome3d.com">Sweet Home 3D</a> HTML5 Viewer / Version 1.2 - Distributed under GNU General Public License
    </div>
 <!--   <div style="margin-top: -60px">
        <input  id="aerialView"   class="viewerComponent" name="cameraType" type="radio" style="visibility: hidden;"/>
        <label class="viewerComponent" for="aerialView" style="visibility: hidden;">Aerial view</label>
        <input  id="virtualVisit" class="viewerComponent" name="cameraType" type="radio" style="visibility: hidden;"/>
        <label class="viewerComponent" for="virtualVisit" style="visibility: hidden;">Virtual visit</label>
        <select id="levelsAndCameras" class="viewerComponent" style="visibility: hidden;"></select>
    </div>-->
</div>