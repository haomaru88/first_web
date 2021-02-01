<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function check_sensor_value1 ($key, $item)
{
   $ret = OK;
   
   if (preg_match("/depth/i", $key)) {             // 수심 데이터를 검증한다.
      if ($item <= 0.01) {
         $ret = NG;
      }
   }
   elseif (preg_match("/temperature/i", $key)) {   // 수온 데이터를 검증한다.
      if ($item <= 0 || 40 <= $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/salinity/i", $key)) {      // 염분 데이터를 검증한다.
      if ($item < 10 || 37 < $item) {
         $ret = NG;
      }      
   }
   elseif (preg_match("/oxygen/i", $key)) {        // 용존산소 데이터를 검증한다.
      if ($item < 0 || 20 < $item) {
         $ret = NG;
      }
   }

   return $ret;
}

function check_sensor_value2 ($key, $item)
{
   $ret = OK;

   if (preg_match("/battery/i", $key)) {
      if ($item < 9 || 15 < $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/WindDirection/i", $key)) {

   }
   elseif (preg_match("/WindSpeed/i", $key)) {
      if ($item < 0 || 40 < $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/AirTemp/i", $key)) {
      if ($item < -40 || 55 < $item) {
         $ret = NG;
      }
   }

   return $ret;
}

?>

<?php
$playing = 0;
function print_table_row($para)
{
   $title1 = array ('Depth', 'WaterTemp.', 'Salinity', 'Oxygen');
   $title2 = array ('Battery', 'WindDirection', 'WindSpeed', 'AirTemp.');
?>

   <div class='col-xl-4 mt-4'>
      <div class='card'>
         <div class='card-body'>
            <div class='header-title'>
               <span class='pull-left' style='margin:0em 1em 1em;'> <?=convert_site_name($para['site_name'])?> </span>
               <?php $imsiTime = date ("h:i:s A", strtotime ($para['time'])); ?>
               <span class='pull-right' style='margin: 0em 1em 0em 1em'> <?=$imsiTime?> </span>
               <span class='pull-right'> <?=$para['date']?> </span>
            </div>
            <div class='single-table'>
               <div class='table-responsive'>
                  <table class='table text-center'>
                     <?php 
                     if (is_gematek_site_name_2019($para['site_name']) == OK) {
                        echo "<thead class='table-header-bg'>";
                     }
                     else {
                        echo "<thead class='table-header-geo'>";
                     }
                     ?>
                     <!-- <thead class='text-uppercase table-header-bg'> -->
                        <tr class='text-white'>
                           <?php foreach ($title1 as $item): ?>
                              <th scope="col"> <?=$item?> </th>
                           <?php endforeach; ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        global $playing;
                        foreach ($para['data'] as $key1 => $layer) {
                           echo '<tr>';
                           foreach($layer as $key2 => $item) {
                              $ret = check_sensor_value1($key2, $item);
                              if ($ret == NG) {
                                 echo "<td class='blink'>";
                                 if (!$playing) {
                                    echo "<embed src='/assets/siren.mp3' autostart='true' loop='false' hidden='false'>";
                                    $playing = 1;
                                 }
                              }
                              else {
                                 echo "<td>";
                              }
                              echo $item;
                              echo '</td>';
                           }
                           echo "</tr>\n";
                        }
                        ?>
                     </tbody>
                  </table>

                  <table class='table text-center' style='margin-top:1em'>
                     <?php 
                     if (is_gematek_site_name_2019($para['site_name']) == OK) {
                        echo "<thead class='table-header-bg'>\n";
                     }
                     else {
                        echo "<thead class='table-header-geo'>\n";
                     }
                     ?>
                        <tr class='text-white'>
                           <?php foreach ($title2 as $key => $value): ?>
                           <th scope='col'> <?=$value?> </th>
                           <?php endforeach; ?>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td> <?=$para['battery']?> </td>
                           <td> <?=$para['wind_direction']?> </td>
                           <td> <?=$para['wind_speed']?> </td>
                           <td> <?=$para['air_temperature']?> </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php } ?>

   
<div class="main-content-inner">
   <div id="map" style="width:100%; height:400px;"></div>
   <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=04c36ea06d3b1edd0c1e2303ca4fd6c7"></script>
   <!-- <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=04c36ea06d3b1edd0c1e2303ca4fd6c7&libraries=drawing"></script> -->
   <script>
      var lat = 35.22012;
      var lng = 129.2432;
      var container = document.getElementById('map');
      var options = {
         center: new kakao.maps.LatLng(lat, lng),
         level: 6
      };

      var map = new kakao.maps.Map(container, options);
      
      var imageSrc = 'http://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_red.png', // 마커이미지의 주소입니다    
         imageSize = new kakao.maps.Size(64, 69), // 마커이미지의 크기입니다
         imageOption = {offset: new kakao.maps.Point(27, 69)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

      // function resizeMap() {
      //    var mapContainer = document.getElementById('map');
      //    mapContainer.style.width = '100%';
      //    mapContainer.style.height = '400px'; 
      // }

      // function relayout() {
      //    map.relayout();
      // }

      var mapTypeControl = new kakao.maps.MapTypeControl();
      map.addControl(mapTypeControl, kakao.maps.ControlPosition.RIGHT);

      var zoomControl = new kakao.maps.ZoomControl();
      map.addControl (zoomControl, kakao.maps.ControlPosition.RIGHT);

      var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption);
      // 마커가 표시될 위치입니다 
      var markerPosition  = new kakao.maps.LatLng(lat, lng); 

      // 마커를 생성합니다
      var marker = new kakao.maps.Marker({
         position: markerPosition,
         image: markerImage
      });

      // 마커가 지도 위에 표시되도록 설정합니다
      marker.setMap(map);
   </script>

   <div class="row">
      <?php
      foreach ($buoy_data as $item) {
         print_table_row($item);
      }
      ?>
   </div>
</div>
