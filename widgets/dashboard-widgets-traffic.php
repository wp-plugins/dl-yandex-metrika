<?php
function dl_yandex_metrika_add_dashboard_widgets_traffic() {
    wp_add_dashboard_widget(
        'traffic_dashboard_widget',         // Идентификатор виджета.
        'Посещаемость',           // Заголовок виджета.
        'traffic_dashboard_widget_function' // Функция отображения.
    );	
}
add_action('wp_dashboard_setup', 'dl_yandex_metrika_add_dashboard_widgets_traffic');

function traffic_dashboard_widget_function() {
	$dl_metrika_id = get_option('dl_yandex_metrika_id');
	$dl_token = get_option('dl_yandex_metrika_token');

	$url = 'https://api-metrika.yandex.ru/stat/traffic/summary.json?id='.$dl_metrika_id.'&oauth_token='.$dl_token;
	$data_traffic = file_get_contents($url);
	$data_traffic = json_decode($data_traffic, true);
	
	$data_traffic = array_reverse($data_traffic[data]);
	
	
	echo '<table width=100%>
		<tr align="center">
			<td align="left">Дата</td>
			<td>Визиты</td>
			<td>Просмотры</td>
			<td>Посетители</td>
		</tr>
		<tr>
			<td>Сегодня</td>
			<td align="center">'.$data_traffic[0][visits].'</td>
			<td align="center">'.$data_traffic[0][page_views].'</td>
			<td align="center">'.$data_traffic[0][visitors].'</td>
		</tr>
		<tr>
			<td>Вчера</td>
			<td align="center">'.$data_traffic[1][visits].'</td>
			<td align="center">'.$data_traffic[1][page_views].'</td>
			<td align="center">'.$data_traffic[1][visitors].'</td>
		</tr>
		';
	
	unset ($data_traffic['0']);
	unset ($data_traffic['1']);
	
	foreach($data_traffic as $key => $value) { 
	
		$data = date('Y.m.d',strtotime($data_traffic[$key][date]));
		
		
		echo '<tr>
				<td>' .$data. '</td>
				<td align="center">' .$data_traffic[$key][visits]. '</td>
				<td align="center">' .$data_traffic[$key][page_views]. '</td>
				<td align="center">' .$data_traffic[$key][visitors]. '</td>
			</tr>';
	}
	
	echo '</table>';
}

  

	
	
	