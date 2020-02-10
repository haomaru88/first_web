<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_gematek_site_name ($name) {

   $gematek_site_name = array('AI51', 'AI52', 'AI53', 'AI57', 'AI59', 'ZI45');

   if (in_array($name, $gematek_site_name)) {
      return OK;
   }
   return NG;
}


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
                     if (is_gematek_site_name($para['site_name']) == OK) {
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
                                    // echo "<audio autoplay='autoplay'> <source src='/assets/siren.mp3' type='audio/mpeg' /> </audio>";
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
                     if (is_gematek_site_name($para['site_name']) == OK) {
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
   <div class="row">
      <?php
      foreach ($buoy_data as $item) {
         print_table_row($item);
      }
      ?>
   </div>
</div>
