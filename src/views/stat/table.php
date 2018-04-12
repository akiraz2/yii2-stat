<?php

$now_date = ''; //текущая дата которая выводится в таблице
$show_new_date = false; //показать смену даты в таблице
$transition = 1; //счетчик переходов на страницы
$count = 0; //общий счетчик посетителей за период
$count_day = 0; //счетчик посетителей за 1 день
$num_ip = ''; //хранение текущего IP
$old = 0; //кол-во найденных строк с IP за данный день

//Получение первой даты по которой отображать статистику
if (isset($count_ip[0])){

    $date = date("d.m.Y",$count_ip[0]->date_ip);

} else $date = date("d.m.Y",time()); //Если дата не установлена, то выводим за сегодняшний день
?>

<?php if (!$stat_ip) { ?>
    <h5>Количество уникальных посетителей по дням:</h5>
<?php } else { ?>
    <h5>Количество посещений пользователя с указанным IP:</h5>
<?php } ?>




<table class='get_table'>
    <thead>
    <tr>
        <th>Переходы на страницы сайта</th>
        <th>IP</th>
        <th colspan="2">URL просматриваемой страницы</th>
        <th>Время посещения</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($count_ip as $key => $value){


        //кол-во посетителей по дням (вывод последнего дня после цикла)
        if($date != date("d.m.Y",$value->date_ip)) {
            echo $date . ' - '. $count_day;
            echo "<a href=\"#$date\">&nbsp;&nbsp; перейти</a>" . '<br>';
            $date = date("d.m.Y",$value->date_ip);
            $count_day = 0;
        }

        if ($stat_ip) $count_day++; //для фильтра по определенному IP


        //Если сменился IP или дата, то включаем счетчики
        if (($num_ip != $value->ip) || ($now_date !=date("Y-m-d",$value->date_ip))){

            $num_ip = $value->ip; //сохраняем текущий IP

            if($now_date !=date("Y-m-d",$value->date_ip)){

                $now_date = date("Y-m-d",$value->date_ip); //сохраняем текущую дату

                $show_new_date = true;
            } else $show_new_date = false;


            $transition = 1;

            /*
             * тут проверка был ли такой IP в течении текущих суток (0-24)
             * Если да, то не добавляем в общий счетчик посетителей за день
             */
            $find = $value->find_ip_by_day($value->ip, $value->date_ip);

            //Если такого IP еще не было в этот день
            if(!count($find)){
                $count++;
                if (!$stat_ip) $count_day++; //для фильтра по определенному IP
                $old = 0;
            } else {
                $old = count($find);
            }


        } else {
            $transition++;
        }


        if ($transition == 1 ) {

            $date_new = date("d.m.Y",$value->date_ip);
            if ($show_new_date && !$old) {
                echo "<tr id=\"$date_new\"></tr>";
                echo "<tr class='tr_first red'><td colspan='5'>$date_new</td></tr>";
                if(!$stat_ip) echo "<tr class='tr_first'><td colspan='5'>НОВЫЙ ПОСЕТИТЕЛЬ</td></tr>";
            }
            else if ($show_new_date && $old) {
                echo "<tr id=\"$date_new\"></tr>";
                echo "<tr class='tr_first red'><td colspan='5'>$date_new</td></tr>";
                echo "<tr class='tr_first'><td colspan='5'>уже был</td></tr>";
            }
            elseif ($old) {
                echo "<tr class='tr_first'><td colspan='5'>уже был</td></tr>";
            } else {
                echo "<tr class='tr_first'><td colspan='5'>НОВЫЙ ПОСЕТИТЕЛЬ</td></tr>";
            }
        }

        echo "<td>$transition</td>
            <td><a id='api' href='".$value->ip."'>".$value->ip."</a></td>  
            	
            	
            <td colspan=\"2\"><a href='".$value->str_url."' target=\"_blank\">".$value->str_url."</a></td>                     
            <td>".date('d.m.Y H:i:s',$value->date_ip)."</td></tr>";


    }

    //вывод кол-ва посетителей за последнее число
    if(isset($value)){
        $date = date("d.m.Y",$value->date_ip);

        if($date){
            echo $date . ' - '. $count_day;
            echo "<a href=\"#$date\">&nbsp;&nbsp; перейти</a>" . '<br>';
        }
    }
    ?>

    <p>Всего посетителей за период - <?=$count?></p>
    <p><a href='#filters'>перейти к фильтрам</a></p>
    </tbody>
</table>