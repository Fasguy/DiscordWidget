<?php
/*
	Name:		discordwidgetphp v1.0
	URL:		https://github.com/Fasguy/discordwidgetphp
	Created by:	Fasguy [https://fasguy.net/]
	License:	GNU GPLv3.0
*/

//User Settings -> Appearance -> Enable "Developer Mode"
//Right-Click on your Server -> "Copy ID"
$server_id = "";
$dark_theme = true;
$widget_json = [
	'name' => 'An error occured.',
	'members' => [],
	'instant_invite' => '#'
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://discordapp.com/api/servers/$server_id/widget.json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
$data = curl_exec($curl);
if (curl_errno($curl)) {
    $widget_json['members'] = 'Couldn\'t send request: ' . curl_error($curl);
} else {
    $resultStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($resultStatus != 200) {
        $widget_json['members'] = 'Request failed: HTTP status code: ' . $resultStatus;
    } else {
		$widget_json = json_decode($data, true);
	}
}
curl_close($curl);

?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="widget_general.css"/>
		<link rel="stylesheet" href="<?php echo $dark_theme ? "widget_dark.css" : "widget_light.css" ?>"/>
		<link rel="stylesheet" href="font/whitney.css"/>
	</head>
	<body>
		<div id="server_name">
			<h3><?php echo $widget_json['name']; ?></h3>
		</div>
		<div id="server_userlist">
			<div class="container">
				<ul class="discord_userlist">
					<?php
					if(is_array($widget_json['members'])) {
						foreach($widget_json['members'] as $member) { ?>
							<li class="discord_user">
								<div>
									<img draggable="false" src="<?php echo $member['avatar_url'] ?>" class="discord_avatar">
									<div class="discord_status discord_<?php echo $member['status'] ?>"></div>
								</div>
								<div class="username">
									<?php echo $member['username']; ?>
								</div>
							</li>
						<?php }
					} else {
						echo "<div>" . $widget_json['members'] . "</div>";
					} ?>
				</ul>
			</div>
		</div>
		<div id="server_join">
			<span class="cell">
				<a id="join_button" target="_blank" href="<?php echo $widget_json['instant_invite']; ?>">
					<span id="join_text">JOIN</span>
				</a>
			</span>
		</div>
	</body>
</html>