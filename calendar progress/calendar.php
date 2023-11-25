<?php

// php need to be at top of file to avoid including static elements in jquery response 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {


	$calendar = new Calendar();
	ob_start();

	echo $calendar->show();

	// ob_end_flush();
	$response = ob_get_clean();

	echo $response;


	exit;
} else {

	// this one line "echo sad" is problematic
	// echo "sad";
}
?>

<?php
require_once('config.php');


class Calendar
{





	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->naviHref = htmlentities($_SERVER['PHP_SELF']);
	}

	/********************* PROPERTY ********************/
	private $dayLabels = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");

	private $currentYear = null;

	private $currentMonth = null;

	private $currentDay = 0;

	private $currentDate = null;

	private $daysInMonth = 0;

	private $naviHref = null;

	private $response;

	private $newMonthCounter;



	/********************* PUBLIC **********************/

	/**
	 * print out the calendar
	 */
	public function show()
	{


		$ch = curl_init();

		$postdata = array();
		$postdata["action"] = "getEvent";
		$postdata["timestamp"] = "";
		$postdata["signature"] = "";

		// now using https://p9ltix4uqx.siasportsclub.sg/api/v1/index.php
		curl_setopt($ch, CURLOPT_URL, 'https://p9ltix4uqx.siasportsclub.sg/api/v1/index.php');
		curl_setopt($ch, CURLOPT_POST, true);

		// In real life you should use something like:
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		// https://stackoverflow.com/questions/29822686/curl-error-60-ssl-certificate-unable-to-get-local-issuer-certificate#:~:text=was%20for%20WAMP.-,Here%27s%20what%20I%20did%3A,-Download%20the%20certificate
		// troubleshooting
		// var_dump($ch);
		// print curl_error($ch);


		curl_close($ch);

		$this->response = json_decode($server_output, true);

		// if ($server_output != null) {
		// 	echo ' bro not null ';
		// }

		// echo $server_output;

		// echo $server_output;

		$year = null;

		$month = null;

		// $year = $_POST['year'];

		// $month = $_POST['month'];



		if (null == $year && isset($_POST['year'])) {

			$year = $_POST['year'];
		} else if (null == $year) {

			$year = date("Y", time());
		}

		if (null == $month && isset($_POST['month'])) {

			$month = $_POST['month'];
		} else if (null == $month) {

			$month = date("m", time());
		}




		$this->currentYear = $year;

		$this->currentMonth = $month;

		$this->daysInMonth = $this->_daysInMonth($this->currentMonth, $this->currentYear);

		$content = '<div id="calendar">' .
			'<div class="box">' .
			$this->_createNavi() .
			'</div>' .
			// '<div class="loader"></div>' .
			'<div class="box-content">' .
			'<ul class="label">' . $this->_createLabels() . '</ul>';
		$content .= '<div class="clear"></div>';
		$content .= '<ul class="dates">';

		$weeksInMonth = $this->_weeksInMonth($this->currentMonth, $this->currentYear);
		// Create weeks in a month
		for ($i = 0; $i < $weeksInMonth; $i++) {

			//Create days in a week
			for ($j = 1; $j <= 7; $j++) {

				$content .= $this->_showDay($i * 7 + $j);

				// trying sth
				// too hacky, i'll refactor days normally

				// if ($j==1) {
				// 	$content .= $this->_showDay($i * 7 +  7 );
				// }
				// else {
				// 	$content .= $this->_showDay($i * 7 +  fmod($j+6,7)  );
				// }

			}
		}

		// testing
		$content .= ' <li style="

		z-index:10; 
		grid-row: 2; 
		grid-column: 1 / span 7; 
		transform: translate(0, 45px); 

		border: .5pt solid #000000;
		border-radius: 12px;
		margin: 0 5px;
		background-color: #ffa200;
		height: 24px;
		font-size: 12px;

		display:flex;
		justify-content:center;
		align-items:center;
		
		
		
		">Item 1</li> ';

		if ($this->response["status"] == 0) {

			foreach ($this->response["data"] as $event) {

				$rowsInAnEvent = $this->_rowsInAnEvent($event);

				$eventID = $event['programmeid'];
				$eventTitle = $event["programmetitle"];

				$eventPromoStart = strtotime($event["datestartpromo"]);
				$eventPromoEnd = strtotime($event["dateendpromo"]);

				$eventStart = strtotime($event["datestart"]);
				$eventEnd = strtotime($event["dateend"]);

				$currentDateUnix = strtotime($this->currentYear . '-' . $this->currentMonth . '-' . $this->currentDay);

				switch ($rowsInAnEvent) {
					case 1:

						$content .= ' <li 

						class="event_name" 
						
						style="

						z-index:10; 
						grid-row: ' . $this->_convertToGridRowCoordinates($eventPromoStart) . '; 
						grid-column: ' . $this->_convertToGridColumnCoordinates($eventPromoStart) . ' / span ' . $this->_spanLength($event) . '; 
						transform: translate(0, 45px); 

						border: .5pt solid #000000;
						border-radius: 12px;
						margin: 0 5px;
						background-color: #ffa200;
						max-height: 24px;
						font-size: 12px;

						display:flex;
						justify-content:center;
						align-items:center;
						
						
						
						">Item 1</li> ';

						break;
					case 2:
						# code...
						break;
					case 3 <= $rowsInAnEvent:
						# code...
						break;
					default:
						# code...
						break;
				}

				// trying to make that weird thing in fullcalendar.io illusion

				// 1st box?
				// if (
				// 	$eventPromoStart == $currentDateUnix
				// 	// and
				// 	// $currentDateUnix <= $eventPromoEnd
				// ) {
				// 	$cellContent .= '<div class=event_name_start>' .  "<a href=\"event_details.php?programmeid=$eventID\">$eventTitle  promotion </a>". '</div>';
				// }

				// // in between?
				// if (
				// 	$eventPromoStart < $currentDateUnix
				// 	and
				// 	$currentDateUnix < $eventPromoEnd
				// ) {
				// 	$cellContent .= '<div class=event_name>' .  "<a href=\"event_details.php?programmeid=$eventID\"> <br> </a>". '</div>';
				// }

				// // last box?
				// if (
				// 	$currentDateUnix == $eventPromoEnd
				// 	// and
				// 	// $currentDateUnix <= $eventPromoEnd
				// ) {
				// 	$cellContent .= '<div class=event_name_end>' .  "<a href=\"event_details.php?programmeid=$eventID\">$eventTitle hi promotion </a>". '</div>';
				// }


				// if (
				// 	$eventStart <= $currentDateUnix
				// 	and
				// 	$currentDateUnix <= $eventEnd
				// ) {
				// 	$cellContent .= '<div class=event_name>' . $eventTitle . '</div>';
				// }
			}
		}


		$content .= '</ul>';

		$content .= '<div class="clear"></div>';

		$content .= '</div>';

		$content .= '</div>';






		return $content;
	}

	// need to make this public
	public function nextButton()
	{
		$this->currentMonth == 12 ? 1 : intval($this->currentMonth) + 1;
		$this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;
	}


	public function prevButton()
	{
		$this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;
		$this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;
	}



	/********************* PRIVATE **********************/
	/**
	 * create the li element for ul
	 */


	private function _showDay($cellNumber)
	{
		$firstDateOfTheMonth = strtotime($this->currentYear . '-' . $this->currentMonth . '-01');
		// echo $firstDateOfTheMonth;
		$firstDayOfTheMonth = $this->_refactoringMondayToSunday(date('N', $firstDateOfTheMonth));
		// echo $firstDayOfTheMonth;
		$dateOfLastSunday = date('d', strtotime("last Sunday", $firstDateOfTheMonth));
		// echo $dateOfLastSunday;
		
		
		// $lastDateOfLastMonth = date('d', strtotime('-1 day', $firstDateOfTheMonth));


		// if first day of week isn't sunday, meaning there's a gap
		// aka if there's a gap
		if ($firstDayOfTheMonth != 1) {
			
			if (intval($cellNumber) == 1) {
				$this->newMonthCounter = false;
			}
			
			// if ($this->newMonthCounter == false) {
			// 	echo 'false bro';
			// }

			// $this->currentDay == 0 or 
			if ($this->newMonthCounter == false) {
				
				

				if (intval($cellNumber) == 1 ) {
					$this->currentDay = $dateOfLastSunday;

					// echo $this->currentDay;

					$cellContent = $this->currentDay;

					$this->currentDay++;

				} elseif ($this->newMonthCounter == false) {
					$cellContent = $this->currentDay;

					$this->currentDay++;
				}  

				if (intval($cellNumber) == intval($firstDayOfTheMonth) and intval($cellNumber) != 1) {

					$this->currentDay = 1;
					$this->newMonthCounter = true;
					echo 'true bro ';
				}
			}
		}elseif ($firstDayOfTheMonth == 1 and intval($cellNumber) == 1) {
			$this->newMonthCounter = true;
			$this->currentDay = 1;
			// if ($this->newMonthCounter == true) {
			// 	echo 'different true bro ';
			// }
			
		}

		
		// so annoying no syntax error highlighting when i put = instead of ==
		// spent hours on this
		if ( $this->currentDay <= $this->daysInMonth and $this->newMonthCounter == true ) {

			$this->currentDate = date('Y-m-d', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . ($this->currentDay)));
			// commenting this line doesn't do anything

			$cellContent = $this->currentDay;
			// $cellContent .= $this->currentDay;

			$this->currentDay++;

		} else if ($this->newMonthCounter == true) {
			
				// $this->currentDate = null;
	
				$cellContent = null;
				// $cellContent .= 'hey';
			
		}
		
		


		return '<li id="li-' . $this->currentDate . '" class="' . ' dates ' . ($cellNumber % 7 == 1 ? ' start ' : ($cellNumber % 7 == 0 ? ' end ' : 'e')) . ' " ' .
			// forcing grid-row & grid-column thing
			// quite hard
			// actually hard
			' style=" z-index:1; grid-row: ' .
			(ceil(($cellNumber) / 7)) . ';' .
			' grid-column: ' .
			(fmod($cellNumber, 7) == 0 ? 7 : fmod($cellNumber, 7)) .
			' " ' .
			($cellContent == null ? 'mask' : '') . '">'  . $cellContent . '</li>';
		// }
	}

	/**
	 * create navigation
	 */
	private function _createNavi()
	{

		$currentMonthYearUnix = strtotime($this->currentYear . '-' . $this->currentMonth);

		return

			// $this->currentMonth > date("m", time()) and $this->currentYear == date("Y", time()) 
			// these work separately, but this doesn't work for some reason, tired rn , may need to use strtotime

			// ($this->currentYear >= date("Y", time()) ?
			// ($this->currentMonth > date("m", time()) ? '<button class="btnPrev" type="button"><</button>' : ($this->currentYear > date("Y", time()) ?
			// '<button class="btnPrev" type="button"><</button>'
			// : '<div class="invisiblePlaceHolder"> </div>'))
			// : '<div class="invisiblePlaceHolder"> </div>') .
			// this works but too long


			'<div class="headerButNotActualHeader">' .
			($currentMonthYearUnix > time() ? '<button class="btnPrev" type="button"><</button>' : '<div class="invisiblePlaceHolder"> </div>') .
			'<div class="title">' . date('M Y', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</div>' .
			'<button class="btnNext" type="button">></button>' .
			'</div>';
	}

	/**
	 * create calendar week labels
	 */
	private function _createLabels()
	{

		$content = '';

		foreach ($this->dayLabels as $index => $label) {

			$content .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';
		}

		return $content;
	}



	/**
	 * calculate number of weeks in a particular month
	 */
	private function _weeksInMonth($month = null, $year = null)
	{
		// interesting assigning default values, in this case, null
		if (null == ($year)) {
			$year =  date("Y", time());
		}

		if (null == ($month)) {
			$month = date("m", time());
		}

		// find number of days in this month
		$daysInMonths = $this->_daysInMonth($month, $year);

		$numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);

		$monthEndingDay = $this->_refactoringMondayToSunday(date('N', strtotime($year . '-' . $month . '-' . $daysInMonths)));

		$monthStartDay = $this->_refactoringMondayToSunday(date('N', strtotime($year . '-' . $month . '-01')));

		if ($monthEndingDay < $monthStartDay) {

			$numOfweeks++;
		}

		// slight addition to above if statement , making day 7 (sunday) , into 0
		// if ( fmod($monthEndingDay,7) == 0 ) {
		// 	$numOfweeks++;
		// }



		return $numOfweeks;
	}

	/**
	 * calculate number of days in a particular month
	 */
	private function _daysInMonth($month = null, $year = null)
	{

		if (null == ($year))
			$year =  date("Y", time());

		if (null == ($month))
			$month = date("m", time());

		return date('t', strtotime($year . '-' . $month . '-01'));
	}

	private function _refactoringMondayToSunday($day)
	{

		// specifically for date('N'), i want sunday to be 1, not monday
		$day += 1;
		if ($day == 8) {
			$day = 1;
		}

		return $day;
	}

	private function _daysInAnEvent($event)
	{
	}

	private function _rowsInAnEvent($event)
	{
		$eventPromoStart = strtotime($event["datestartpromo"]);
		$eventPromoEnd = strtotime($event["dateendpromo"]) + 1;
		// ceil and plus 1 are for making $eventPromoEnd inclusive
		$daysInAnEvent = ceil(($eventPromoEnd - $eventPromoStart) / (60 * 60 * 24));

		$rowsInAnEvent = ($daysInAnEvent % 7 == 0 ? 0 : 1) + intval($daysInAnEvent / 7);

		// $eventEndingDay = $this->_refactoringMondayToSunday( date('N', strtotime($year . '-' . $month . '-' . $daysInMonths)) );


		$eventStartingDay = $this->_refactoringMondayToSunday(date('N', strtotime($eventPromoStart - 1)));

		$eventEndingDay = $this->_refactoringMondayToSunday(date('N', strtotime($eventPromoEnd - 1)));

		if ($eventEndingDay < $eventStartingDay) {

			$rowsInAnEvent++;
		}

		return $rowsInAnEvent;
	}

	private function _convertToGridRowCoordinates($date)
	{
		$yearOfEvent = date('Y', $date);
		$MonthOfEvent = date('m', $date);
		$dayOfMonthOfEvent = date('d', $date);
		$firstDayOfThatMonth = $this->_refactoringMondayToSunday(date('N', strtotime($yearOfEvent . '-' . $MonthOfEvent . '-01')));

		$row = ceil(($dayOfMonthOfEvent + $firstDayOfThatMonth - 1) / 7);

		return $row;
	}

	private function _convertToGridColumnCoordinates($date)
	{
		$yearOfEvent = date('Y', $date);
		$MonthOfEvent = date('m', $date);
		$dayOfMonthOfEvent = date('d', $date);
		$firstDayOfThatMonth = $this->_refactoringMondayToSunday(date('N', strtotime($yearOfEvent . '-' . $MonthOfEvent . '-01')));

		$column = (fmod($date, 7) + $firstDayOfThatMonth - 1 == 0 ? 7 : fmod($date, 7) + $firstDayOfThatMonth - 1);
		return $column;
	}

	private function _spanLength($event)
	{
		$eventPromoStart = strtotime($event["datestartpromo"]);
		$eventPromoEnd = strtotime($event["dateendpromo"]) + 1;
		// ceil and plus 1 are for making $eventPromoEnd inclusive
		$daysInAnEvent = ceil(($eventPromoEnd - $eventPromoStart) / (60 * 60 * 24));

		return $daysInAnEvent;
	}
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="description" content="description" />
	<meta name="keywords" content="keywords" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>SIA Group Sports Club</title>
	<!-- styles-->
	<link rel="stylesheet" href="css/styles.min.css" />
	<link rel="stylesheet" href="css/terence.css" />

	<!-- css for calendarPrinter -->
	<link rel="stylesheet" href="css/calendarPrinter.css">

	<!-- jQueryForButton -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>


	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-M0J71HR59D"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'G-M0J71HR59D');
	</script>
	<!-- END Google tag (gtag.js) -->

	<!-- web-font loader-->
	<script>
		WebFontConfig = {

			google: {

				families: ['Nunito+Sans:300,400,500,700,900', 'Quicksand:300,400,500,700'],

			}

		}

		function font() {

			var wf = document.createElement('script')

			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js'
			wf.type = 'text/javascript'
			wf.async = 'true'

			var s = document.getElementsByTagName('script')[0]

			s.parentNode.insertBefore(wf, s)

		}

		font()
	</script>
</head>

<body>

	<div class="page-wrapper">
		<?php include_once "menu.php" ?>
		<main class="main">
			<!-- promo start-->
			<section class="promo-primary">
				<picture>
					<source srcset="img/event_cover.jpg" media="(min-width: 992px)" /><img class="img--bg" src="img/event_cover.jpg" alt="img" />
				</picture>
				<div class="container">
					<div class="row">
						<div class="col-auto">
							<div class="align-container">
								<div class="align-container__item"><span class="promo-primary__pre-title">Event Calendar</span>
									<h1 class="promo-primary__title"><span>SIA Group Sports Club</span> <span>Event</span></h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- promo end-->
			<!-- section start-->
			<section class="section no-padding-bottom">
				<div class="container">
					<div class="row bottom-50">
						<div class="col-12">
							<div class="heading heading--style-2 text-center"><span class="heading__pre-title">Event Calendar</span>
								<h2 class="heading__title no-margin-bottom"><span>OUR</span> <span>Event List</span></h2>
							</div>
						</div>
					</div>
					<div class="container">



						<div class="row">
							<!-- svg thing -->
							<!-- <div class="col-12">
								<div class="heading heading--style-2 text-center">
									<img src="img/coming-soon.svg" width="230">
									<h2 class="heading__title no-margin-bottom"><span></span></h2>
								</div>
							</div> -->

							<!-- adding stuff here -->

						</div>

						<div id="phpCalendar">

							<!-- calendar will be here -->

						</div>

					</div>
			</section>
			<!-- section end-->



			<?php include_once("section_app.php") ?>

			<!-- section start-->
			<!--<section class="section no-padding-top no-padding-bottom">
					<div class="row no-gutters">
						<div class="col-lg-6"><a class="action-block" href="reciprocal_club.php">
							<div class="action-block__inner"><img class="img--bg blackcover" src="img/club_local_1.jpg" alt="img"/>
								<h3 class="action-block__title"><span>Explore</span><br/> <span>Reciprocal Club Local</span></h3>
							</div></a></div>
						<div class="col-lg-6"><a class="action-block" href="oversea_club.php">
							<div class="action-block__inner"><img class="img--bg blackcover" src="img/club_oversea_1.jpg" alt="img"/>
								<h3 class="action-block__title"><span>Explore</span><br/> <span>Reciprocal Club Overseas</span></h3>
							</div></a></div>
					</div>
				</section>-->

			<!-- section end-->
		</main>
		<?php require_once "footer.php" ?>
	</div>
	<!-- libs-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="js/libs.min.js"></script>
	<!-- scripts-->
	<script src="js/common.js"></script>
	<?php require_once "svg.php" ?>
	<script>
		// $(document).on('click', '.btnNext', function() {
		// 	loaderDiv = document.createElement("div");

		// 	loaderDiv.classList.add("loader");



		// 	loaderDiv.classList.add("loader--hidden");

		// 	document.getElementsByClassName("box-content").appendChild(loaderDiv);

		// 	loaderDiv.addEventListener("transitionend", () => {
		// 		document.body.removeChild(loaderDiv);
		// 	});
		// });
	</script>
	<script defer>
		// loader needs to be on client side, not server side, cuz reasons
		// $('.loader').hide().ajaxStart(function() {
		// 	$(this).show(); // show Loading Div
		// }).ajaxStop(function() {
		// 	$(this).hide(); // hide loading div
		// });

		$(document).ready(function() {

			var thisYear = new Date().getFullYear();
			var thisMonth = new Date().getMonth();
			thisMonth += 1;

			var loaderSpinner = '<div class="loader"></div>';

			// loaderDiv = document.createElement("div");

			// loaderDiv.classList.add("loader");



			// loaderDiv.classList.add("loader--hidden");

			// document.getElementsByClassName("box-content").appendChild(loaderDiv);

			// loader.addEventListener("transitionend", () => {
			// 	document.body.removeChild(loader);
			// });

			$.ajax({
				url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
				method: 'POST',
				data: {
					year: thisYear,
					month: thisMonth
				},
				success: function(response) {
					// html method is the answer
					$("#phpCalendar").html(response);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});

			// $('#loader').hide().ajaxStart(function() {
			// 	$(this).show(); // show Loading Div
			// }).ajaxStop(function() {
			// 	$(this).hide(); // hide loading div
			// });

			$(document).on('click', '.btnNext', function() {


				thisYear = (thisMonth == 12) ? thisYear += 1 : thisYear += 0;
				thisMonth = (thisMonth == 12) ? 1 : thisMonth += 1;

				$(".box-content").addClass('box-content-borderless')

				$(".box-content").html(loaderSpinner);

				$.ajax({
					url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
					method: 'POST',
					data: {
						year: thisYear,
						month: thisMonth
					},
					success: function(response) {

						// replace loader after success function
						$("#phpCalendar").html(response);



					},
					error: function(xhr, status, error) {
						console.error(error);
					}
				});



				// loaderDiv = document.createElement("div");

				// loaderDiv.classList.add("loader");

				// // loaderDiv.classList.add("loader--hidden"); 

				// document.getElementById("phpCalendar").appendChild(loaderDiv);

				// loaderDiv.addEventListener("transitionend", () => {
				// 	document.body.removeChild(loaderDiv);
				// });







				// loader thingy
				// window.addEventListener("load", () => {
				// const loader = document.querySelector(".loader");

				// loader.classList.add("loader--hidden");

				// loader.addEventListener("transitionend", () => {
				// 	document.body.removeChild(loader);
				// });
				// });

				// loader.addEventListener("transitionend", () => {
				// 	document.body.removeChild(loader);
				// });


			});

			$(document).on('click', '.btnPrev', function() {


				// bruh the order of these 2 are important
				thisYear = (thisMonth == 1) ? thisYear -= 1 : thisYear += 0;
				thisMonth = (thisMonth == 1) ? 12 : thisMonth -= 1;

				$(".box-content").addClass('box-content-borderless')

				$(".box-content").html(loaderSpinner);



				$.ajax({
					url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
					method: 'POST',
					data: {
						year: thisYear,
						month: thisMonth
					},
					success: function(response) {




						$("#phpCalendar").html(response);

					},
					error: function(xhr, status, error) {
						console.error(error);
					}
				});



			});





		});
	</script>



</body>

</html>