<?php


?>

<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8">

	<title>Pool Tournament</title>
	<meta name="description" content="The Pool Match Challenge">
	<meta name="author" content="João Reis">

	<link rel="stylesheet" href="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./assets/css/styles.css">
	<style>
		.dashblock {
			/*border: 1px dashed #ccc;*/
			/*margin: 10px;*/
			padding: 20px;
		}

		.nav_shadow {
			box-shadow: -3px 5px 10px #ccc;
			margin-bottom: 40px;
			padding: 10px;
			border-radius: 0 0 10px 10px;
			/* Safari 3-4, iOS 1-3.2, Android 1.6- */
			-webkit-border-radius: 0 0 10px 10px;
			/* Firefox 1-3.6 */
			-moz-border-radius: 0 0 10px 10px;
		}

		.table_header{
			font-size: 10px;
			color:'#ccc';
		}

		.btn_match, .btn_player{
			cursor: pointer;
		}
		.btn_match:hover{
			color:#d04444;
		}
		.btn_player:hover{
			color:#af9a33;
		}

		.footer {
			position: fixed;
			bottom: 0;
			font-size: 10px;
			padding: 10px;
		}
	</style>

</head>

<body>

	<div class="container">

		<header>
			<nav class="navbar navbar-expand-lg navbar-light nav_shadow justify-content-between">
				<span class="navbar-brand">Pool Tournament<span style="margin-left:5px;font-size:10px;font-style: italic;">by Bold</span></span>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item ">
						<a class="nav-link" href="javascript:void(0);" id="btn_submit_modal">Update match</a>
					</li>
				</ul>
			</nav>
		</header>

		<section class="row">
			<article id="b_ranking" class="col-md-6 dashblock">
				<header>
					<h2>Ranking</h2>
				</header>
				<input id="filter_input_ranking" type="search" class="form-control" placeholder="Filter">
				<p class="table_ranking">
				<div class="table-responsive">
					<table id="table_ranking" class="table">
						<thead>
							<tr>
								<th>#</th>
								<th width="200px">Name</th>
								<th>Points</th>
								<th>N Balls Left</th>
								<th>Matches</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="5">Loading ...</td>
							</tr>
						</tbody>
					</table>
				</div>
				</p>
			</article>
			<article id="b_matches" class="col-md-6 dashblock">
				<header>
					<h2>Matches</h2>
					<!--<p class="text-muted">Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="#">Writer</a> - <a href="#comments">6 comments</a></p>
				</header>-->
					<input id="filter_input_matches" type="search" class="form-control" placeholder="Filter">
					<p class="table_matches">
					<div class="table-responsive">
						<table id="table_matches" class="table">
							<thead>
								<tr>
									<th width="100px">Date</th>
									<th>Match</th>
									<th>Winner</th>
									<th>Looser</th>
									<th>N Balls Left</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">Loading ...</td>
								</tr>
							</tbody>
						</table>
					</div>
					</p>
			</article>
		</section>

		<footer class="footer">
			&copy; <?php echo date('Y');  ?> Pool Tournament
		</footer>
	</div>

	<?php include('includes/pages/submit_page.php'); ?>

	<?php include('includes/pages/match_page.php'); ?>

	<?php include('includes/pages/player_page.php'); ?>

	<script src="./vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="./vendor/components/jquery/jquery.min.js"></script>
	<script>
		$(document).ready(function() {

			var origin = window.location.href;

			var playersArr = [];
			var matchesArr = [];
			var rankingArr = [];
			readApi('pool_players', getPlayers);

			for (let index = 1; index <= 7; index++) {
				$('#select_looser_balls').append($("<option></option>").attr("value", index).text(index + ' balls'));
			}

			$("#select_matches").on('change', function() {
				var players = $('option:selected', this).text().split('||')[1].split(' Vs ');
				var players_id = $('option:selected', this).attr('players_id').split('-');
				$('#select_winner').empty();
				$('#select_winner').append($("<option></option>").attr("value", '').text('-- select an winner --'));

				$('#select_winner').append($("<option></option>").attr("value", players_id[0]).text(players[0]).attr("looser", players[1]).attr("looser_id", players_id[1]));
				$('#select_winner').append($("<option></option>").attr("value", players_id[1]).text(players[1]).attr("looser", players[0]).attr("looser_id", players_id[0]));
			});

			$("#select_winner").on('change', function() {
				var looser_name = $('option:selected', this).attr("looser");
				$('#looser_name').text(looser_name);
			});

			$("#select_status").on('change', function() {
				if ($(this).val() == 'Absent') {
					$('#select_looser_balls').val(7);
					$('#select_winner').val('');
					$('#select_winner').prop('disabled', false);
					$('#select_looser_balls').prop('disabled', true);
				} else if ($(this).val() == 'Waiting') {
					$('#select_looser_balls').val(-1);
					$('#select_winner').val(-1);
					$('#select_winner').prop('disabled', true);
					$('#select_looser_balls').prop('disabled', true);
				} else {
					$('#select_looser_balls').val(1);
					$('#select_winner').val('');
					$('#select_winner').prop('disabled', false);
					$('#select_looser_balls').prop('disabled', false);
				}

			});
			//////// SUBMIT PAGE
			$("#btn_submit_modal").on("click", function() {
				$('#modal_submit').modal('show');
			});
			$("#btn_close_match").on("click", function() {
				$('#modal_submit').modal('hide');
			});

			$('#modal_submit').on('shown.bs.modal', function() {
				//console.log('open');
				$('#btn_update_match').prop('disabled', true);
			});

			$("#form_match").on("change", function() {
				if(($('option:selected', '#select_matches').val()>-1 && $('option:selected', '#select_status').val()!='' && $('option:selected', '#select_winner').val()>0 && $('option:selected', '#select_looser_balls').val()>0) || $('option:selected', '#select_matches').val()>-1 && $('option:selected', '#select_status').val()=='Waiting'){
					$('#btn_update_match').prop('disabled', false);
				}else{
					$('#btn_update_match').prop('disabled', true);
				}
			});

			$('#modal_submit').on('hide.bs.modal', function() {
				$('#select_matches').val(-1);
				$('#select_status').val(1);
				$('#select_winner').empty();
				$('#select_looser_balls').val(-1);

			});
			//////// END SUBMIT PAGE

			//////// MATCH PAGE

			function readMatch(id){
				for(var i in matchesArr){
					if(matchesArr[i].id == id){
						//console.log(matchesArr[id]);
						var p1 = matchesArr[id-1].players.split(',')[0];
						var p2 = matchesArr[id-1].players.split(',')[1];
						var winner = matchesArr[id-1].winner_id;
						var looser = matchesArr[id-1].looser_id;
						var looser_balls = matchesArr[id-1].looser_balls;
						var status = matchesArr[id-1].status;
						//console.log(playersArr);
						var temp_text = '<h1 class="text-center">'+playersArr[p1]+' Vs '+playersArr[p2]+'</h1>';
						temp_text+='<table class="table"><tr class="table_header"><td>DATE</td><td>WINNER</td><td>LOOSER</td><td>STATUS</td></tr>';
						if(status=="Waiting"){
							temp_text+='<tr><td>'+matchesArr[id-1].date+'</td><td>-</td><td>-</td><td>'+status+'</td></tr></table>';
						}else{
							temp_text+='<tr><td>'+matchesArr[id-1].date+'</td><td>'+playersArr[winner]+'</td><td>'+playersArr[looser]+'<br>'+looser_balls+' balls left</td><td>'+status+'</td></tr></table>';
						}
						
						$('#match_info').html(temp_text);
					}
				}
			}

			$("#btn_close_match_page").on("click", function() {
				$('#modal_match').modal('hide');
			});

			$('#modal_match').on('shown.bs.modal', function(e) {
				//console.log(e);
				//$("#match_info").html('OK');

			});

			$('#modal_match').on('hide.bs.modal', function() {
				//console.log('close match');
				$('#match_info').html('');
			});
			//////// END MATCH PAGE

			//////// PLAYER PAGE

			function readPlayer(id){
				
				if(id>0 ){
					var temp_player = '<h1 class="text-center">'+playersArr[id]+'</h1>';
					$('#player_info').html(temp_player);

					var temp_games = '<table class="table"><tr class="table_header"><td>DATE</td><td>PLAYERS</td><td>STATUS</td></tr>';
					for(var i in matchesArr){
						//console.log(matchesArr[i]);
						var p1 = matchesArr[i].players.split(',')[0];
						var p2 = matchesArr[i].players.split(',')[1];
						var players = matchesArr[i].players.split(',');
						if( players.includes(id) ){
							temp_games += '<tr><td>'+matchesArr[i].date+'</td><td>'+playersArr[p1]+' Vs '+playersArr[p2]+'</td><td>'+matchesArr[i].status+'</td></tr>';
						}
					}
					temp_games += '</table>';
					$('#games_info').html(temp_games);
				}
			}

			$("#btn_close_player_page").on("click", function() {
				$("#modal_player").modal('hide');
			});

			$('#modal_player').on('shown.bs.modal', function() {
				//console.log('open player');

			});

			$('#modal_player').on('hide.bs.modal', function() {
				//console.log('close player');
				$('#games_info').html('');
				$('#player_info').html('');
			});
			//////// END PLAYER PAGE


			$('#btn_update_match').on('click', function(e) {

				var idToUpdate = Number($('option:selected', '#select_matches').val()) + 1;
				var status = $('option:selected', '#select_status').val();

				var data = {};

				if(status=='Played'){
					data = {
						'winner_id': $('option:selected', '#select_winner').val(),
						'looser_id': $('option:selected', '#select_winner').attr('looser_id'),
						'looser_balls': $('option:selected', '#select_looser_balls').val(),
						'status': $('option:selected', '#select_status').val()
					};
				}else if(status=='Absent'){
					data = {
						'winner_id': $('option:selected', '#select_winner').val(),
						'looser_id': $('option:selected', '#select_winner').attr('looser_id'),
						'looser_balls': 7,
						'status': $('option:selected', '#select_status').val()
					};
				}else if(status=='Waiting'){
					data = {
						'winner_id': 0,
						'looser_id': 0,
						'looser_balls': 0,
						'status': $('option:selected', '#select_status').val()
					};
				}else {
					data = {
						'winner_id': 0,
						'looser_id': 0,
						'looser_balls': 0,
						'status': 'Waiting'
					};
				}
				
				//console.log(data);
				$('#modal_submit').modal('hide');
				//console.log(idToUpdate);
				updateMatch('pool_games', idToUpdate, data);
			});


			$("#filter_input_ranking").on("keyup", function() {
				filterTable($(this), '#table_ranking');
			});

			$("#filter_input_matches").on("keyup", function() {
				filterTable($(this), '#table_matches');
			});

			function getPlayers(players){
				rankingArr=[];
				for (var i in players) {
					playersArr[players[i].id] = players[i].name;
					
					rankingArr[players[i].id] = {'id':players[i].id,'name':players[i].name, 'points': 0, 'games': 0, 'looser_balls': 0};
				}
				readApi('pool_games', refreshMatches);
				
			}
			

			
			function refreshMatches(matches) {

				var temp_table = '';
				var temp_match = '';

				matches.sort(function(a, b) {
					return a.date - b.date;
				});

				matchesArr = matches;

				$('#select_matches').empty();
				$('#select_matches').append($("<option></option>").attr("value", '').text('-- select an match --'));
				for (var i in matches) {
					var p_temp = (matches[i].players).split(',');
					var p = playersArr[p_temp[0]] + ' Vs ' + playersArr[p_temp[1]];
					var p_id = p_temp[0] + '-' + p_temp[1];
					var winner = (playersArr[matches[i].winner_id]) ? playersArr[matches[i].winner_id] : '-';
					var winner_id = (playersArr[matches[i].winner_id]) ? matches[i].winner_id : '-';
					var looser = (playersArr[matches[i].looser_id]) ? playersArr[matches[i].looser_id] : '-';
					var looser_id = (playersArr[matches[i].looser_id]) ? matches[i].looser_id : '-';
					var looser_balls = (matches[i].looser_balls>0) ? matches[i].looser_balls : '-';
					temp_table += '<tr><td>' + matches[i].date + '</td><td class="btn_match" id="match_' + matches[i].id + '">' + p + '</td><td class="btn_player" id="player_' + winner_id + '">' + winner + '</td><td class="btn_player" id="player_' + looser_id + '">' + looser + '</td><td>' + looser_balls + '</td><td>' + matches[i].status + '</td></tr>';

					$('#select_matches').append($("<option></option>").attr("value", i).text(matches[i].date + ' || ' + p).attr("players_id", p_id));

					if(matches[i].status!='Waiting'){
						rankingArr[winner_id]['points']+=3;
						rankingArr[looser_id]['points']+=1;
						rankingArr[looser_id]['looser_balls']+=Number(looser_balls);

						rankingArr[winner_id]['games']+=1;
						rankingArr[looser_id]['games']+=1;
					}
					
				}

				refreshRanking(rankingArr);

				$('#table_matches tbody').html(temp_table);

				$(".btn_match").on("click", function(e) {
					$('#modal_match').modal('show');
					readMatch(e.target.id.split('_')[1]);
				});

				$(".btn_player").on("click", function(e) {
					if (e.target.id != '') {
						
						var player_id = e.target.id.split('_')[1];
						if(player_id>0){
							$('#modal_player').modal('show');
							readPlayer( player_id );
						}
						
					}
				});



			}

			function refreshRanking(players) {

				var temp_table = '';
				var temp_rank = '';

				players.sort(function(a, b) {
					return b.points - a.points || b.looser_balls - a.looser_balls;
				});

				for (var i in players) {
					if(players[i].games>0){
						playersArr[players[i].id] = players[i].name;
						temp_rank = parseInt(i) + 1;
						temp_table += '<tr><td>' + temp_rank + '</td><td class="btn_player" id="player_' + players[i].id + '">' + players[i].name + '</td><td>' + players[i].points + '</td><td>' + players[i].looser_balls + '</td><td>' + players[i].games + '</td></tr>';
					}
				}

				$('#table_ranking tbody').html(temp_table);

				
			}

			function readApi(tableName, callback) {

				$.ajax({
					url: origin + '/api/read.php?table=' + tableName,
					type: 'GET',
					success: function(data) {
						if (data) {
							callback(data);
						}
					},
					error: function(data) {
						alert('Error, request error.'); //or whatever
					}
				});
			}


			function updateMatch(tableName, idToUpdate, updateData) {

				$.ajax({
					url: origin + '/api/update.php',
					type: 'POST',
					data: {
						'table': tableName,
						'id': idToUpdate,
						'data': updateData
					},
					success: function() {
						readApi('pool_players', getPlayers);
					},
					error: function(data) {
						alert('Error, request error.'); //or whatever
					}
				});
			}

			function filterTable(el_input, el_table) {
				var value = el_input.val().toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "").trim();
				$(el_table + " tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "").indexOf(value) > -1)
				});
			}


		});
	</script>
</body>

</html>
