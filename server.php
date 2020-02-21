<?php 
$tokens = array(
'w5wNXGxz58ccXRT7jvymFZpGicUT2ldw');
$token = array_rand($tokens, 1);
$name=htmlspecialchars($_POST['nick']);
$json =  file_get_contents("https://api.vime.world/online/streams?token=$tokens[$token]");
$url = json_decode($json, true);
$streams = implode('<br>', array_column($url, 'url'));
$json =  file_get_contents("http://api.vime.world/user/name/$name?token=$tokens[$token]");
$answerjson = json_decode($json, true);
$usernamefromapi = implode('', array_column($answerjson, 'username'));
$id = implode('', array_column($answerjson, 'id'));
$level = implode('', array_column($answerjson, 'level'));
$rank = implode('', array_column($answerjson, 'rank'));
$seconds = implode('', array_column($answerjson, 'playedSeconds'));
$date = implode('', array_column($answerjson, 'lastSeen'));
$json =  file_get_contents("https://api.vime.world/user/$id/session?token=$tokens[$token]");
$arr = json_decode($json, true);
$online_user = $arr['online']['value'];//Общий онлайн 
$online_user_where = $arr['online']['message'];//Общий онлайн 
$online_user_os = $arr['online']['game'];//Общий онлайн 
$json =  file_get_contents("https://api.vime.world/online/staff?token=$tokens[$token]");
$staffonl = json_decode($json, true);
$staff = implode(', ', array_column($staffonl, 'username'));
$json =  file_get_contents("http://api.vime.world/online?token=$tokens[$token]");
$arr = json_decode($json, true);
$online = $arr['total'];
$ann = $arr['separated']['ann'];
$bb = $arr['separated']['bb'];
$gg = $arr['separated']['gg'];
$sw = $arr['separated']['sw'];
$mw = $arr['separated']['mw'];
$duels = $arr['separated']['duels'];
$cp = $arr['separated']['cp'];
$kpvp = $arr['separated']['kpvp'];
$dr = $arr['separated']['dr'];
$bp = $arr['separated']['bp'];
$lobby = $arr['separated']['lobby'];
$hg = $arr['separated']['hg'];
$bw = $arr['separated']['bw'];
?>

<?php 
include 'core/init.php';
include 'includes/overall/header.php'; 
include 'core/MinecraftQueryDetailed.php';
require_once('core/functions/recaptchalib.php');

$server_id  = (INT)$_GET['id'];
$user_id	= id_to_user_id($server_id);
$addedBy 	= username_from_user_id($user_id);

$result  = mysql_query("SELECT * FROM `servers` WHERE `id` = '$server_id'");
$server_data = mysql_fetch_array($result, MYSQL_ASSOC);

$last_update = time() - $server_data['cache_time'];
$last_updateM = intval($last_update/60);
$category_name = mysql_result(mysql_query("SELECT `name` FROM `categories` WHERE `category_id` = '{$server_data['category_id']}'"),0);

?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#vote').click(function(){
			$.ajax({
				url : 'vote_updater.php',
				type : 'POST',
				data : {
					action : 'vote_server',
					sid : $('#sid').data('sid')
				},
				dataType : 'JSON',
				success : function(result) {
					if (result.xhr == 'success') {
						$('#vote').attr('disabled','true');
						$('#votes').html(parseInt($('#votes').html()) + 1);
						alert('Вы успешно проголосовали !');
					} else if (result.xhr == 'voted_already')
						alert('Вы уже голосовали за этот сервер !')
				}
			});
		});
	})
</script>

<div id="sid" data-sid="<?php echo $server_data['id']; ?>" style="margin: auto;margin-bottom: -39px;">


<div class="alert alert-info">
	<p style="font-size: 24px;margin-top: 7px;">Подключится: <strong><?php echo $server_data['ip']; ?></strong></p>
</div>

<ul class="nav nav-pills" id="tabs" >
	<li class="active"><a href="#info"><i class="icon-signal"></i> Информация об игроке</a></li>
	<li><a href="#votestats"><i class="icon-thumbs-up"></i> Голоса</a></li>
</ul>

<div class="tab-content" >
	<div class="tab-pane active" id="info">
		<table width="434" height="319" class="table table-bordered" style="background:white;">
			<tr>
				<td style="
    width: 25%;
"><i class="icon-time"></i> <strong>Статус</strong></td>
				<td ><?php

echo "<center>";
if($online_user == true)
{
    echo "<p>Статус: игрок онлайн! $online_user_where. Приставка игры: $online_user_os</p>"; 
}
else 
{
    echo "<p>Статус: игрок оффлайн!</p>";
}
echo "<p>Уровень: $level </p>";
if($rank == PLAYER)
{
    // если условие выполнено, то выполняем это действие
    echo "Ранг: <b>Игрок</b>"; 
}
if($rank == VIP)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#00be00'><b> VIP </b></font></p>"; 
}
if($rank == PREMIUM)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#00dada'><b> PREMIUM </b></font></p>"; 
}
if($rank == HOLY)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#ffba2d'><b> HOLY </b></font></p>"; 
}
if($rank == IMMORTAL)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#e800d'><b> IMMORTAL </b></font></p>"; 
}
if($rank == BUILDER)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#009c00'><b> Билдер </b></font></p>"; 
}
if($rank == MAPLEAD)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#009c00'><b> Главный билдер </b></font></p>"; 
}
if($rank == YOUTUBE)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#fe3f3f'><b> YouTube </b></font></p>"; 
}
if($rank == DEV)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#00bebe'><b> Разработчик </b></font></p>"; 
}
if($rank == ORGANIZER)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#00bebe'><b> Организатор </b></font></p>"; 
}
if($rank == MODER)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#1b00ff'><b> Модератор </b></font></p>"; 
}
if($rank == WARDEN)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#1b00ff'><b> Проверенный модератор </b></font></p>"; 
}
if($rank == CHIEF)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#1b00ff'><b> Главный модератор </b></font></p>"; 
}
if($rank == ADMIN)
{
    // если условие выполнено, то выполняем это действие
    echo "<p>Ранг: <font color='#00bebe'><b> Главный админ </b></font></p>"; 
}
?>
 </td>
			</tr>
			<tr>
				<td><i class="icon-comment"></i> <strong>MOTD</strong></td>
				<td>   </td>
			</tr>
			<tr>
				<td><i class="icon-random"></i> <strong>IP Адрес</strong></td>
				<td><?php echo gethostbyname($server_data['ip']); ?></td>
			</tr>
					<tr>
				<td><i class="icon-user"></i> <strong>Всего людей</strong></td>
				<td>  </td>
			</tr>
			<tr>
				<td><i class="icon-wrench"></i> <strong>Версия сервера</strong></td>
				<td> <a href="http://api.vime.world/user/name/<?php $name; ?> "></a> </td>
			</tr>
		
			<tr>
				<td><i class="icon-user"></i> <strong>Добавил</strong></td>
				<td><a href="profile.php?username="><?php echo $addedBy; ?></a></td>
			</tr>
			
			
			<tr>
				<td><i class="icon-refresh"></i> <strong>Обновление</strong></td>
				<td><?php echo $last_updateM; ?> минут назад</td>
			</tr>
			<tr>
				<td><i class="icon-check"></i> <strong>Голосов</strong></td>
				<td>
					<div id="votes" style="display:inline;"><?php echo $server_data['votes']; ?></div> <a type="button" id="vote">Проголосовать !</a> 
					<?php if($server_data['votifier_key'] !== 'false') { ?> <a href="votifier.php?id=<?php echo $server_data['id']; ?>"><input type="button" class="btn btn-warning" value="Votifier Vote" /></a>  <?php } ?>
				</td>
			</tr>
		</table>
		
		<?php if(!empty($server_data['description'])){ ?>
			<table class="table table-bordered" style="background:white;">
				<tr><td><strong>Описание</strong></td></tr>
				<tr>
					<td style="padding: 0px 20px 0px 20px;"><?php echo bbcode($server_data['description']); ?></td>
				</tr>
			</table>
		<?php } ?>
		
		<?php if(!empty($server_data['youtube_id'])){ ?>
			<table class="table table-bordered" style="background:white;">
				<tr><td><strong>Видео Презентация</strong></td></tr>&nbsp;</td>
				</tr>
			</table>
		<?php } ?>
		
	</div>
	<div class="tab-pane" id="votestats">
		<table class="table table-condensed" style="table-layout:fixed;">
			<thead>
				<tr>
					<th>IP</th>
					<th>Год / Месяц / День</th>
					<th>Час / Минут / Секунд</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(mysql_result(mysql_query("SELECT COUNT(`ip`) FROM `votes` WHERE `server_id` = '$server_id'"), 0) > 0){
						$voteResult  = mysql_query("SELECT * FROM `votes` WHERE `server_id` = '$server_id' ORDER BY `timestamp` DESC LIMIT 50");
						while($votes_data = mysql_fetch_array($voteResult, MYSQL_ASSOC)){
						$ip = explode(".", $votes_data['ip']);
						$ip[3] = "***";$ip[2] = "***";
						$ip = implode(".", $ip);
						echo "
							<tr>
								<td>" . $ip . "</td>
								<td>" . date('Y.m.d', $votes_data['timestamp']) . "</td>
								<td>" . date('h:i:s', $votes_data['timestamp']) . "</td>
							</tr>";
						}
					} else {
						echo "<tr><td>Currently there are no votes!</td><td></td><td></td></tr>";
					}
				?>
			</tbody>
		</table>	
	</div>
	
	
	
	<div class="tab-pane" id="banners">
	<?php 
	$link   = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	
	if($server_data['banner'] !== ""){
	?>
		<img src="<?php echo $server_data['banner']; ?>" />
		<h4>BB/HTML код </h4>
		<textarea id="bb_small_code" rows="3" style="width: 95%;">[url=<?php echo $link; ?>/server.php?id=<?php echo $id1; ?>][img]<?php echo $server_data['banner']; ?>[/img][/url]</textarea>
		<textarea id="html_small_code" rows="3" style="width: 95%;"><a href="<?php echo $link; ?>/server.php?id=<?php echo $id1; ?>"><img src="<?php echo $server_data['banner']; ?>"></a></textarea>
	<?php } ?>
		<img src="dynamic_image.php?s=<?php echo $server_id; ?>&type=background" />
		<h4>BB/HTML код </h4>
		<textarea id="bb_small_code" rows="3" style="width: 95%;">[url=<?php echo $link; ?>/server.php?id=<?php echo $id1; ?>][img]<?php echo $link; ?>/dynamic_image.php?s=<?php echo $id1; ?>&type=background[/img][/url]</textarea>
		<textarea id="html_small_code" rows="3" style="width: 95%;"><a href="<?php echo $link; ?>/server.php?id=<?php echo $id1; ?>"><img src="<?php echo $link; ?>/dynamic_image.php?s=<?php echo $id1; ?>&type=background"></a></textarea>
	
	</div>
	
	
	
	
	<?php
	//try for detailed stats//
	$Query = new MinecraftQuery( );
    try { $Query->Connect( $server_data['ip'], $server_data['port'] ); $fail = 0;}
    catch( MinecraftQueryException $e )
    {
		$fail = 1;
    }
	$infoz = $Query->GetInfo();
	$plugins = $infoz['Plugins'];
	?>
	<div class="tab-pane" id="plugins">
	<?php
	if($fail == 1 || !is_array($plugins)){
		echo "This minecraft server does not allow a detailed query or it doesn't respond correctly to the challenge ! Please change your <code>server.proprieties</code> so you would have:<br/>";
		echo "<br /><pre>enable-query=true</pre>";
	} else {
		echo "<h3>Active Plugins</h3>";
		echo "<pre><ul>";
		foreach($plugins as $plugin){
			echo "<li>" . $plugin . "</li>";
		}
		echo "</ul></pre>";
	}
	?>
	</div>
	
	
	
	<div class="tab-pane" id="players">
		<?php
		if($fail == 1){
			echo "This minecraft server does not allow a detailed query or it doesn't respond correctly to the challenge ! Please change your <code>server.proprieties</code> so you would have:<br/>";
			echo "<br /><pre>enable-query=true</pre>";
		}elseif($Query->GetPlayers() === false){
			echo "Unfortunately there are no players online, vote this server so it will gain popularity !";
		} else {
		foreach($Query->GetPlayers() as $key => $val){
			$players[] = $val;
		}
		sort($players);
		?>
		<h3>Онлайн игроки ( <?php echo $info['Players']; ?> )&nbsp;<span class="label label-success" style="cursor:pointer;" onclick="$('#playerList').toggle('slow')"> <i class="icon-plus icon-white"></i> Посмотреть Список</span></h3>
		
		<div>
			<?php
			foreach($players as $player){
				echo "<a rel='tooltip' title='" . $player . "'><img src='https://minotar.net/avatar/" . $player . "/50'  style='margin-bottom:5px;margin-right:5px;' /></a>&nbsp;";
			}
			?>
		</div>
		<br />
		<div id="playerList" style="display: none;">
			<?php
			echo "<pre><ul>";
			foreach($players as $player){
				echo "<li>" . $player . "</li>";
			}
			echo "</ul></pre>";
			?>
		</div>
		<?php } ?>
	</div>
</div>

<script>
$('#tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
 $(function () {
	$("[rel='tooltip']").tooltip();
});
</script>

<table class="table table-bordered list" style="table-layout: fixed;">
<tbody>
		<tr>
			<td style="width:7%">
								<b>Коментарий</b> 
 (<?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments` WHERE `server_id` = '$server_id'"), 0);?>) <?php if(logged_in() == false){ ?><span style="
float: right;
" class="label label-warning">Авторизуйтесь чтобы добавить комментарий !</span><?php } ?> <?php if(logged_in() == true){ ?><span class="label label-success" style="cursor:pointer;float: right;" onClick="$('#comments').toggle('slow');">Добавить Комментарий</span><?php } ?></h3>

							</td>
</tr></tbody></table>
<?php
if(empty($_POST) == false){
	
	if(strlen($_POST['comment']) > 254){
		$errors[] = 'Comment too long, maximum 255 characters!';
	}
	
	if(empty($errors) == true){
		$comment	= htmlspecialchars($_POST['comment'], ENT_QUOTES);
		mysql_query("INSERT INTO `comments` (`server_id`, `user_id`, `comment`) VALUES ('$server_id', '$session_user_id', '$comment')");
	}else{
		echo output_errors($errors);
	}
}
?>

<?php
$query = mysql_query("SELECT * FROM `comments` WHERE `server_id` = '$server_id'");
while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
$comment_user_id  = $row['user_id'];
@$comment_added_by = mysql_result(mysql_query("SELECT `username` FROM `users` WHERE `user_id` = '$comment_user_id'"), 0) ? mysql_result(mysql_query("SELECT `username` FROM `users` WHERE `user_id` = '$comment_user_id'"), 0) : "Unknown User";
?>
<table class="table table-bordered" style="background:white;">
	<tr>
		<td>
			<strong>Коментарий от:</strong> <?php echo $comment_added_by; ?>
			<?php if(logged_in() == true && is_admin($session_user_id)){ ?>
			<div class="pull-right">
				<a href="server.php?id=<?php echo $server_id; ?>&delete=<?php echo $row['id']; ?>">
					<span class="label label-important"><i class="icon-remove icon-white"></i> Удалить </span>
				</a>
			</div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td><?php echo $row['comment']; ?></td>
	</tr>
</table>
<?php } ?>

<br />

<?php if(logged_in() == true){ ?>
<form action="" method="post" id="comments" style="display:none;">
	<textarea style="width: 665px;height: 125px;margin-top: -15px;" name="comment"></textarea><br />
	
	<div style="
    float: right;
    margin-top: -137px;
"><?php $error = null; echo recaptcha_get_html($settings['recaptcha_public'], $error); ?></div>

	<input type="submit" class="btn btn-primary" value="Добавить комментарий" />
</form>
<?php } ?>

<?php
if(empty($_GET['delete']) == false && logged_in() && is_admin($session_user_id)){
	$comment_id = (INT)$_GET['delete'];
	mysql_query("DELETE FROM `comments` WHERE `id` = '$comment_id'");
	header('Location: server.php?id=' . $server_id);
}
?>
</div>
<?php include 'includes/overall/footer.php'; ?>
