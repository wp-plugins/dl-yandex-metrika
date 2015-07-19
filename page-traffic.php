<?php
$dl_metrika_id = get_option('dl_yandex_metrika_id');
$dl_token = get_option('dl_yandex_metrika_token');
$date = date('Ymd',strtotime("-1 month"));

$url = 'https://api-metrika.yandex.ru/stat/traffic/summary.json?id='.$dl_metrika_id.'&oauth_token='.$dl_token.'&date1='.$date;
$json_data = file_get_contents($url);
$json_data = json_decode($json_data, true);
?>
<div class="wrap">
<h2>Отчет Посещаемость <a href="https://metrika.yandex.ru/stat/traffic?id=<?php echo $dl_metrika_id; ?>" target="_blank" style="float: right" class="button">Отчет на Yandex.Metrika</a></h2>
<script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Визиты', 'Просмотры', 'Посетители'],
<?php
foreach($json_data[data] as $key => $value) { 
	
	$date = date('d.m',strtotime($json_data[data][$key][date]));
	$visites = $json_data[data][$key][visits];
	$page_views = $json_data[data][$key][page_views];
	$visitors = $json_data[data][$key][visitors];
	
	echo '[\''. $date .'\','.$visites.','.$page_views.','.$visitors.'],';

}
?>
        ]);

         var options = {
          chart: {
            title: 'Данные о посещаемости сайта за месяц'
          }
        };

        var chart = new google.charts.Bar(document.getElementById('visites_div'));

        chart.draw(data, options);
      }
    </script>
	

<div class="wrap">
    <div class="postbox-container" style="width: 100%">
        <div class="metabox-holder">
            <div class="meta-box-sortables">
			
			
                <div class="postbox" id="first">
                    <div class="inside">
						<div id="visites_div" style="width: 98%; height: 250px;"></div>
                    </div>
                </div>
				

<table class="wp-list-table widefat fixed striped posts">
<thead>
<tr>
	<th class="manage-column column-title"><a>Дата</a></th>
	<th class="manage-column column-author">Визиты</th>
	<th class="manage-column column-author">Просмотры</th>
	<th class="manage-column column-author">Посетители</th>
	<th class="manage-column column-author">Новые посетители</th>
	<th class="manage-column column-author">Глубина просмотра</th>
	<th class="manage-column column-author">Отказы</th>
	<th class="manage-column column-author">Среднее время</th>
</tr>
</thead>

<tbody>
<?php

$json_data = array_reverse($json_data[data]);

foreach($json_data as $key => $value) { 
	$traffic_date 			= $json_data[$key][date];
	$traffic_visits 		= $json_data[$key][visits];
	$traffic_page_views		= $json_data[$key][page_views];
	$traffic_visitors		= $json_data[$key][visitors];
	$traffic_depth			= $json_data[$key][depth];
	$traffic_new_visitors	= $json_data[$key][new_visitors];
	$traffic_denial			= $json_data[$key][denial];
	$traffic_visit_time		= $json_data[$key][visit_time];
	
	$traffic_visit_time		= $traffic_visit_time/60;
?>  
<tr>
  <th class="manage-column column-title"><a><?php echo date('Y.m.d',strtotime($traffic_date)); ?></a></th>
  <th class="manage-column column-author"><?php echo $traffic_visits; ?></th>
  <th class="manage-column column-author"><?php echo $traffic_page_views; ?></th>
  <th class="manage-column column-author"><?php echo $traffic_visitors; ?></th>
  <th class="manage-column column-author"><?php echo $traffic_new_visitors; ?></th>
  <th class="manage-column column-author"><?php echo round($traffic_depth, 1); ?></th>
  <th class="manage-column column-author"><?php echo round($traffic_denial, 1); ?></th>
  <th class="manage-column column-author"><?php echo round($traffic_visit_time, 1); ?></th>
</tr>
<?php } ?>
</tbody>
</table>                        

<br>

				<?php if(get_option('dl_yandex_metrika_developer') <> '') { ?>
				<div class="postbox" id="second">
                    <h3 class="hndle" style="cursor: default">Массив данных</h3>
                    <div class="inside">
						<?php if(get_option('dl_yandex_metrika_developer_url') <> '') { ?>
						<a href="<?php echo $url.'&pretty=1'; ?>" target="_blank"><?php echo $url; ?></a><?php } ?>						
                        <pre><?php print_r($json_data); ?></pre>
                    </div>
                </div>
				<?php } ?>
				
            </div>
        </div>
    </div>
</div>