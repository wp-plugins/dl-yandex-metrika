<?php
add_action('wp_dashboard_setup', 'dl_yandex_metrika_add_dashboard_widgets_traffic');
function dl_yandex_metrika_add_dashboard_widgets_traffic() {
    wp_add_dashboard_widget(
        'traffic_dashboard_widget',         // Идентификатор виджета.
        'Посещаемость',           			// Заголовок виджета.
        'traffic_dashboard_widget_function' // Функция отображения.
    );
}


function traffic_dashboard_widget_function() {
	$dl_metrika_id = get_option('dl_yandex_metrika_id');
	$dl_token = get_option('dl_yandex_metrika_token');

	$url = 'https://api-metrika.yandex.ru/stat/traffic/summary.json?id='.$dl_metrika_id.'&oauth_token='.$dl_token;
	$json_data = file_get_contents($url);
	$json_data = json_decode($json_data, true);
	
	$json_data = array_reverse($json_data[data]);
?>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [' ', ' '],
<?php
foreach($json_data as $key => $value) { 
$data = date('m.d',strtotime($json_data[$key][date]));
echo '["'.$data.'", '.$json_data[$key][visits].'],';
} ?>
        ]);

        var options = {
			'chartArea': {'width': '100%', 'height': '100%'},
			legend: { position: "none" },
			hAxis: { textPosition: 'none' },
			vAxis: { textPosition: 'none' }
		};

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="height: 100px;"></div><hr>
<?php	
	
	echo '<table width=100%>
		<tr align="center">
			<td align="left">Дата</td>
			<td>Визиты</td>
			<td>Просмотры</td>
			<td>Посетители</td>
		</tr>
		<tr>
			<td>Сегодня</td>
			<td align="center">'.$json_data[0][visits].'</td>
			<td align="center">'.$json_data[0][page_views].'</td>
			<td align="center">'.$json_data[0][visitors].'</td>
		</tr>
		<tr>
			<td>Вчера</td>
			<td align="center">'.$json_data[1][visits].'</td>
			<td align="center">'.$json_data[1][page_views].'</td>
			<td align="center">'.$json_data[1][visitors].'</td>
		</tr>
		';
	
	unset ($json_data['0']);
	unset ($json_data['1']);
	
	foreach($json_data as $key => $value) { 
		$data = date('Y.m.d',strtotime($json_data[$key][date]));
		echo '<tr>
				<td>' .$data. '</td>
				<td align="center">' .$json_data[$key][visits]. '</td>
				<td align="center">' .$json_data[$key][page_views]. '</td>
				<td align="center">' .$json_data[$key][visitors]. '</td>
			</tr>';
		}
	echo '</table>';
}