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
                     if (is_gematek_site_name_2020($para['site_name']) == OK) {
                        echo "<thead class='table-header-bg2'>";
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
                     if (is_gematek_site_name_2020($para['site_name']) == OK) {
                        echo "<thead class='table-header-bg2'>\n";
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
      var lat = 34.90795;
      var lng = 128.22814;

      var mapContainer = document.getElementById('map'), // 지도를 표시할 div  
      mapOption = { 
         center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
         level: 10 // 지도의 확대 레벨
      };

      var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

      var mapTypeControl = new kakao.maps.MapTypeControl();
      map.addControl(mapTypeControl, kakao.maps.ControlPosition.RIGHT);

      var zoomControl = new kakao.maps.ZoomControl();
      map.addControl (zoomControl, kakao.maps.ControlPosition.RIGHT);      // 마커를 표시할 위치와 title 객체 배열입니다 

      var positions = [
         <?php foreach ($buoy_index_data as $item): ?>
         {
            title: '<?= convert_site_name($item->site_name)?>',
            latlng: new kakao.maps.LatLng(<?=$item->latitude?>, <?=$item->longitude?>)
         },
         <?php endforeach; ?>
      ];

      // 마커 이미지의 이미지 주소입니다
      var imageSrc = "https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_red.png"; 
         
      // 마커 이미지의 이미지 크기 입니다
      var imageSize = new kakao.maps.Size(32, 35); 
      
      // 마커 이미지를 생성합니다    
      var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize); 
         
      for (var i = 0; i < positions.length; i ++) {
         // 마커를 생성합니다
         var marker = new kakao.maps.Marker({
            map: map, // 마커를 표시할 지도
            position: positions[i].latlng, // 마커를 표시할 위치
            title : positions[i].title, // 마커의 타이틀, 마커에 마우스를 올리면 타이틀이 표시됩니다
            image : markerImage // 마커 이미지 
         });
      }

      map.setMapTypeId(kakao.maps.MapTypeId.HYBRID);

      // 커스텀 오버레이에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
      var content =
         '<div class="customoverlay">' +
         '  <a href="https://map.kakao.com/link/map/11394059" target="_blank">' +
         '    <span class="title">구의야구공원</span>' +
         '  </a>' +
         '</div>';

      // 커스텀 오버레이가 표시될 위치입니다 
      var position = new kakao.maps.LatLng(37.54699, 127.09598);  

      // 커스텀 오버레이를 생성합니다
      var customOverlay = new kakao.maps.CustomOverlay({
         map: map,
         position: position,
         content: content,
         yAnchor: 1 
      });

      </script>

   <div class="row">
      <?php
      foreach ($buoy_data as $item) {
         print_table_row($item);
      }
      ?>
   </div>
</div>
