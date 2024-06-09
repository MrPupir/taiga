<?php
include 'include/init.php';

$page = new Page();
$page->Header(["plugin-dateFormatter"]);
?>

<div class="main">
    <div class="container">
        <div class="line"></div>
        <div class="welcome">
            Ласкаво просимо в наш
        <br>
            готель TAIGA!
        </div>
        <a id="details" href="#" scroll="#book">Детальніше</a>
    </div>
</div>
<div class="book" id="book">
    <div class="container">
        <div class="input">
            Дата заїзду
            <input type="text" id="from">
        </div>
        <div class="input">
            Дата виїзду
            <input type="text" id="to">
        </div>
        <div class="input">
            Кількість дорослих
            <input type="number" id="adults" min="1" max="4" placeholder="Від 1 до 4">
        </div>
        <div class="input">
            Кількість дітей
            <input type="number" id="children" min="0" max="3" placeholder="Від 0 до 3">
        </div>
        <br>
        <div class="input">
            <a onclick="reserveRoom()">Бронювати</a>
        </div>
    </div>
</div>
<div class="info">
    <div class="container">
        <div class="header">Чому саме ми?</div>
        Готель Taiga – це затишне місце у самому серці гарних Карпатських гір. 
        <br>
        Розташований в оточенні густих лісів та гірських потоків
        <br>
        Наш готель пропонує ідеальне поєднання комфорту, розкоші та природної краси.
    </div>
</div>
<div class="other">
    <div class="container">
        <div class="header">Ми пропонуємо вам</div>
        <div class="item-grid">
            <div class="item">
                <img src="images/icon1.png" alt="Комфорт">
                <div class="title">Проживання у комфорті</div>
                Просторі номери та люкси з панорамними видами на гори та ліси, обладнані всім необхідним для приємного відпочинку.
            </div>
            <div class="item">
                <img src="images/icon2.png" alt="Харчування">
                <div class="title">Харчування у номерах</div>
                Ми пропонуємо замовлення в номер з різноманітним меню із вишуканих страв місцевої та європейської кухні, які можна замовити у будь-який час дня чи ночі.
            </div>
            <div class="item">
                <img src="images/icon3.png" alt="Спа">
                <div class="title">Спа-центр</div>
                У нашому спа-центрі ви можете насолодитися широким спектром процедур та масажів для розслаблення та відновлення після активного відпочинку.
            </div>
            <div class="item">
                <img src="images/icon4.png" alt="Розваги">
                <div class="title">Екскурсії та розваги</div>
                Ми організуємо цікаві екскурсії визначними пам'ятками Карпат та різноманітні розважальні заходи для наших гостей.
            </div>
        </div>
    </div>
    <div class="container">
        <div class="grid">
            <img src="images/photo.png" alt="Фото">
            <div class="desc">
                <div>
                    <div class="header">Відчуйте Дух Карпат</div>
                    Якщо Ви полюбляете  гори, чисте повітря, найкрасивішу природу та український колорит, тоді Вам саме до насготель "TAIGA"
                    <a href="/rooms">Детальніше</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var from = $('#from');
        var to = $('#to');
        var adults = $('#adults');
        var children = $('#children');
        var details = $('#details');
    
        from.datepicker({
            minDate: 1,
            maxDate: "+1y",
            dateFormat: 'dd/mm/yy',
            onSelect: function(selected) {
                var fromDate = $.datepicker.parseDate('dd/mm/yy', selected);
                var toDate = new Date(fromDate);
                toDate.setDate(toDate.getDate() + 15);
                var minDate = new Date(fromDate);
                minDate.setDate(minDate.getDate() + 1);
                to.datepicker("option", "minDate", minDate);
                to.datepicker("option", "maxDate", toDate);
            },
            showAnim: 'fadeIn'
        });

        to.datepicker({
            minDate: 2,
            maxDate: "+1y",
            dateFormat: 'dd/mm/yy',
            onSelect: function(selected) {
                var toDate = $.datepicker.parseDate('dd/mm/yy', selected);
                var fromDate = new Date(toDate);
                fromDate.setDate(fromDate.getDate() - 15);
                var today = new Date();
                today.setDate(today.getDate() + 1);
                fromDate = new Date(Math.max(fromDate, today));
                from.datepicker("option", "minDate", fromDate);
                var maxDate = new Date(toDate);
                maxDate.setDate(maxDate.getDate() - 1);
                from.datepicker("option", "maxDate", maxDate);
            },
            showAnim: 'fadeIn'
        });

        from.attr('placeholder', $.fn.dateFormatter({ offset: 1 }));
        to.attr('placeholder', $.fn.dateFormatter({ offset: 2 }));

        adults.change(function() {
            if (adults.val() > 4) {
                adults.val(4);
            }

            if (adults.val() < 1) {
                adults.val(1);
            }
        });

        children.change(function() {
            if (children.val() > 3) {
                children.val(3);
            }

            if (children.val() <= 0) {
                children.val(0);
            }
        });

        details.smoothScroll();
    });

    function reserveRoom() {
        var from = $('#from').val();
        var to = $('#to').val();
        var adults = $('#adults').val();
        var children = $('#children').val();

        if (from == '' || to == '' || adults == '' || children == '') {
            alert('Заповніть всі поля');
            return;
        }

        window.location.href = '/rooms?from=' + from + '&to=' + to + '&adults=' + adults + '&children=' + children;
    }
</script>

<?php
$page->Footer();
?>