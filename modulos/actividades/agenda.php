<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../css/agenda.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<!-- Contact Form CSS files -->
<link type='text/css' href='css/thickbox.css' rel='stylesheet' media='screen' />
<link type='text/css' href='css/contact.css' rel='stylesheet' media='screen' />
<!-- Load JavaScript files -->
<script src="js/jquery-1.1.3.1.pack.js" type="text/javascript"></script>
<script src="js/thickbox.js" type="text/javascript"></script>

<script type="text/javascript">
function pageLoad(sender, args)
            {
                if(args.get_isPartialLoad())
                {
                    //  reapply the thick box stuff
                    tb_init('a.thickbox');
                }
            }
</script> 

<title>Agenda</title>
</head>

<body>

<?php

include("../../config/config.php");


/* Open up a connection to the mysql database on the same server as website */

/* Select our database (there is more than one in my server) */
mysql_select_db($basedatos, $db);
 
 
/* draws a calendar */
function draw_calendar($month,$year,$events = array()){
 
    /* draw table */
    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
 
    /* table headings */
    $headings = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
 
    /* days and weeks vars now ... */
    $running_day = date('w',mktime(0,0,0,$month,1,$year));
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();
 
    /* row for week one */
    $calendar.= '<tr class="calendar-row">';
 
    /* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++):
        $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
        $days_in_this_week++;
    endfor;
 
    /* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++):
    $calendar.= '';
/* add leading zero in the day number */
    if($list_day < 10) {
         $list_day = str_pad($list_day, 2, '0', STR_PAD_LEFT);
         }
/* add leading zero in the month number */
    if($month < 10) {
         $month = str_pad($month, 2, '0', STR_PAD_LEFT);
         }
 
    $event_day = $year.'-'.$month.'-'.$list_day;
     
    $calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
     
     
    /* add in the day number */
            $calendar.= '<div class="day-number"><div id="contact-form"><a href="#" class="contact">'.$list_day.'</a></div></div>';
             
            $event_day = $year.'-'.$month.'-'.$list_day;
            //echo $event_day;
            //echo "<br />";
            if(isset($events[$event_day])) {
                foreach($events[$event_day] as $event) {
                    $calendar.= '<div class="event">'.htmlentities($event['evento']).'</div>';
                }
            }
            else {
                $calendar.= str_repeat('<p>&nbsp;</p>',2);
            }
			
        $calendar.= '<a class="thickbox" href="evento2.php?fecha='.$event_day.'&TB_iframe=true&height=600&width=600 title="Please Sign In">Agregar</a>';
        if($running_day == 6):
            $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month):
                $calendar.= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++; $running_day++; $day_counter++;
    endfor;
 
    /* finish the rest of the days in the week */
    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
        endfor;
    endif;
 
    /* final row */
    $calendar.= '</tr>';
     
 
    /* end the table */
    $calendar.= '</table>';
 
    /** DEBUG **/
    $calendar = str_replace('</td>','</td>'."\n",$calendar);
    $calendar = str_replace('</tr>','</tr>'."\n",$calendar);
     
    /* all done, return result */
    return $calendar;
}
 
function random_number() {
    srand(time());
    return (rand() % 7);
}
 
/* date settings */
$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));
 
/* select month control */
$select_month_control = '<select name="month" id="month">';
for($x = 1; $x <= 12; $x++) {
    $select_month_control.= '<option value="'.$x.'"'.($x != $month ? '' : ' selected="selected"').'>'.date('F',mktime(0,0,0,$x,1,$year)).'</option>';
}
$select_month_control.= '</select>';
 
/* select year control */
$year_range = 7;
$select_year_control = '<select name="year" id="year">';
for($x = ($year-floor($year_range/2)); $x <= ($year+floor($year_range/2)); $x++) {
    $select_year_control.= '<option value="'.$x.'"'.($x != $year ? '' : ' selected="selected"').'>'.$x.'</option>';
}
$select_year_control.= '</select>';
 
/* "next month" control */
$next_month_link = '<a href="?month='.($month != 12 ? $month + 1 : 1).'&year='.($month != 12 ? $year : $year + 1).'" class="control">Next Month &gt;&gt;</a>';
 
/* "previous month" control */
$previous_month_link = '<a href="?month='.($month != 1 ? $month - 1 : 12).'&year='.($month != 1 ? $year : $year - 1).'" class="control">&lt;&lt;  Previous Month</a>';
 
 
/* bringing the controls together */
$controls = '<form method="get">'.$select_month_control.$select_year_control.'&nbsp;<input type="submit" name="submit" value="Go" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$previous_month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_month_link.' </form>';
 
/* get all events for the given month
   I had to rewrite this query to get
   anything usable out of the mysql
   database we already had. */
 
$events = array();

$query = "SELECT tipo, DATE_FORMAT(fecha,'%Y-%m-%d') AS fecha FROM actividades WHERE fecha LIKE '$year-%$month-%'";
$result = mysql_query($query,$db) or die('cannot get results!');

/*$query = ("
  SELECT
    name
    AS title,
    DATE_FORMAT( FROM_UNIXTIME(startdate), '%Y-%m-%d' )
    AS event_date
  FROM
    courses
  LEFT JOIN
    coursedates
  ON courses.courseid = coursedates.courseid
  WHERE
    FROM_UNIXTIME(startdate)
      LIKE '$year-%$month-%'");*/
 
/* verify the query is correct */
/*echo $query;
echo "<hr />";
echo "<br />";*/
 
       
$result = mysql_query($query,$db) or die('error 2');
while($row = mysql_fetch_assoc($result)) {
    $events[$row['fecha']][] = $row;
 
/* verify that the query gets results.
   Also generates a list of this months events */
    //echo $row['evento']." ----- ".$row['fecha'];
    //echo "<br />";
}
//echo "<br />";
//echo "<br />";
 
echo '<h2 style="float:left; padding-right:30px;">'.date('F',mktime(0,0,0,$month,1,$year)).' '.$year.'</h2>';
echo '<div style="float:left;">'.$controls.'</div>';
echo '<div style="clear:both;"></div>';
echo draw_calendar($month,$year,$events);
echo '<br /><br />';
?>

</body>
</html>
