<?php
/**
 * Project: yii2-stat
 * Author: akiraz2
 * License: MIT
 * Copyright (c) 2018.
 */

use yii\helpers\Html;
    use akiraz2\stat\AlertWidget;
?>

<h3 class="stat_center">Статистика посещений по IP</h3>
<div id="stat_ip">



    <?= AlertWidget::widget() ?>

    <?php echo $this->render('table',[
        'count_ip'=> $count_ip,
        'stat_ip' => $stat_ip,
    ]); ?>


    <div id="filters">
        <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

        <input name="reset" type="hidden" value="1">
        <button class="button-reset" type="submit">Сбросить фильтры</button>

        <?= Html::endForm() ?>
        <hr>
    </div>



    <h3>Сформировать за указанную дату</h3>

    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

    <input name="reset" type="hidden" value="1">
    <input name="date_ip" type="text" class="date_ip">
    <button class="button-reset" type="submit">Отфильтровать</button>

    <?= Html::endForm() ?>
    <hr>




    <h3>Сформировать за выбранный период </h3>

    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

    <div class="form-group">
        <label for="Начало" class="control-label">Начало</label>
        <input class="date_ip" name="start_time" type="text" value="">
    </div>

    <div class="form-group">
        <label for="Конец" class="control-label">Конец</label>
        <input class="date_ip" name="stop_time" type="text" value="">
    </div>

    <input name="period" type="hidden" value="1">
    <button class="button-reset" type="submit">Отфильтровать</button>

    <?= Html::endForm() ?>
    <hr>



    <h3>Сформировать по определенному IP</h3>

    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

    <div class="form-group">
        <label for="ip" class="control-label">IP</label>
        <input name="ip" type="text" placeholder="127.0.0.1">
    </div>

    <input name="search_ip" type="hidden" value="1">
    <button class="button-reset" type="submit">Отфильтровать</button>

    <?= Html::endForm() ?>
    <hr>



    <h3>Черный список IP</h3>
    <p>Под черным списком понимаются IP, по которым не нужна статистика, например IP администратора сайта.
        <br>По данным IP статистика не будет сохраняться с момента добавления в черный список.</p>

    <table>
        <tr class='tr_small'>

            <h4>Сейчас в черном списке:</h4>

            <?php foreach($black_list as $key=>$value) : ?>
                <td>
                    <?php echo $value['ip'] ?>
                    <?php if(!empty($value['comment'])) : ?>
                        - <?php echo $value['comment'] ?>
                    <?php endif ?>
                </td>

            <?php endforeach ?>


            <?php if(count($black_list)==0) : ?>
                <td>Черный список пуст.</td>
            <?php endif ?>

        </tr>
    </table>
    <br>



    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>
    <div class="form-group">
        <label for="IP" class="control-label">IP</label>
        <input placeholder="127.0.0.1" name="ip" type="text">
    </div>
    <div class="form-group">
        <label for="Комментарий" class="control-label">Комментарий</label>
        <input name="comment" type="text">
    </div>

    <input name="add_black_list" type="hidden" value="1">
    <button type="submit">Добавить в черный список</button>
    <?= Html::endForm() ?>
    <br>




    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>
    <div class="form-group">
        <label for="IP" class="control-label">IP</label>
        <input placeholder="127.0.0.1" name="ip" type="text">
    </div>

    <input name="del_black_list" type="hidden" value="1">
    <button type="submit">Удалить из черного списка</button>
    <?= Html::endForm() ?>

    <hr>





    <h3>Очистка базы данных</h3>

    <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

    <input name="del_old" type="hidden" value="1">
    <button class="button-reset" type="submit">Удалить старые данные</button>

    <?= Html::endForm() ?>

    <br>



    <script type="text/javascript">

        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false
        };
        $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );


        $('.date_ip').datepicker({

            dateFormat: "dd-mm-yy", //формат даты
            minDate: "-1y", // выбор не более чем за последний год
            maxDate: "+0d" // максимальная дата выбора - сегодняшняя
        });

    </script>

    <div id="sub-footer">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright">
                        <p class="text-center"><a href="http://klisl.com/" target="_blank"><b>&copy; KSL</b></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>