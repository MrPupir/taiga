<?php
include 'include/init.php';

$page = new Page();
$page->Header(["plugin-dateFormatter", "plugin-photoCarousel", "plugin-moneyFormatter", "plugin-pagination"]);
?>

<div class="hotel">
    <div class="container">
        <div class="filters-left">
            <div class="filters">
                <div class="category">
                Тип номерів
                </div>
                <div id="roomTypes"></div>
            </div>
            <div class="filters">
                <div class="category">
                Ціна
                </div>
                <input type="text" id="amount" readonly="">
                <div id="slider-range"></div>
            </div>
        </div>
        <div class="main-right">
            <div class="inputs">
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
            </div>
            <div class="rooms-list">
            </div>
            <div class="pagination" id="pagination-container"></div>
        </div>
    </div>
</div>

<div class="reserve-bg">
    <div class="reserve">
        <div class="title">
            Бронювання
        </div>
        <form>
            <div class="grid">
                <div class="input">
                    Дата заїзду
                    <input type="text" id="reserve-from" disabled>
                </div>
                <div class="input">
                    Дата виїзду
                    <input type="text" id="reserve-to" disabled>
                </div>
                <input type="text" id="reserve-id" hidden>
                <div class="input">
                    Тип номеру
                    <input type="text" id="reserve-type" disabled>
                </div>
                <div class="input">
                    Кількість дорослих
                    <input type="text" id="reserve-adults" disabled>
                </div>
                <div class="input">
                    Кількість дітей
                    <input type="text" id="reserve-children" disabled>
                </div>
                <div class="input">
                    Пошта
                    <input type="email" id="reserve-email">
                </div>
                <div class="input">
                    Призвіще
                    <input type="text" id="reserve-surname">
                </div>
                <div class="input">
                    Ім'я
                    <input type="text" id="reserve-name">
                </div>
                <div class="input">
                    Телефон
                    <input type="tel" id="reserve-phone">
                </div>
                <div class="input">
                    <label class="checkbox">
                        <input type="checkbox" id="reserve-food" name="food" />
                        <span class="custom-checkbox"></span>
                        <label for="reserve-food">Харчування в номер</label>
                    </label>
                </div>
            </div>

            <div class="button">
                <input type="submit" value="Бронювати">
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        var slider = $("#slider-range");
        var amount = $("#amount");
        var from = $('#from');
        var to = $('#to');
        var adults = $('#adults');
        var children = $('#children');
        var details = $('#details');

        getRoomTypes();

        slider.slider({
            range: true,
            min: 0,
            max: 10000,
            values: [0, 10000],
            slide: function(event, ui) {
                amount.val(ui.values[0] + " - " + ui.values[1]);
            },
            change: function(event, ui) {
                getRoomsWithParams();
            }
        });

        amount.val(slider.slider("values", 0) + " - " + slider.slider("values", 1));

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

        adults.change(function() {
            getRoomsWithParams();
        });

        children.change(function() {
            getRoomsWithParams();
        });

        getRoomsWithParams();

        var urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('from') && urlParams.has('to') && urlParams.has('adults') && urlParams.has('children')) {
            from.val(urlParams.get('from'));
            to.val(urlParams.get('to'));
            adults.val(urlParams.get('adults'));
            children.val(urlParams.get('children'));

            window.history.pushState({}, document.title, window.location.pathname);

            getRoomsWithParams();
        };

        var $modal = $('.reserve-bg');
        $modal.click(function(e) {
            if ($(e.target).is($modal)) {
                $modal.animate({ opacity: 0 }, 250, function() {
                    $modal.css('display', 'none');
                });
            }
        });

        $('.reserve form').submit(function(e) {
            e.preventDefault();

            var from = $('#reserve-from').val();
            var to = $('#reserve-to').val();
            var id = $('#reserve-id').val();
            var adults = $('#reserve-adults').val();
            var children = $('#reserve-children').val();
            var email = $('#reserve-email').val();
            var surname = $('#reserve-surname').val();
            var name = $('#reserve-name').val();
            var phone = $('#reserve-phone').val();
            var food = $('#reserve-food').is(':checked');

            if (email == '' || surname == '' || name == '' || phone == '') {
                alert('Заповніть всі поля');
                return;
            }

            if (!phone.match(/^\+380\d{9}$/)) {
                alert('Невірний формат телефону (+380XXXXXXXXX)');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'api/reserveRoom',
                data: {
                    from: from,
                    to: to,
                    id: id,
                    adults: adults,
                    children: children,
                    email: email,
                    surname: surname,
                    name: name,
                    phone: phone,
                    food: food
                },
                success: function(data) {
                    if (data.status == 'error') {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        $('#from').val('');
                        $('#to').val('');
                        $('#adults').val('');
                        $('#children').val('');
                        $('#reserve-email').val('');
                        $('#reserve-surname').val('');
                        $('#reserve-name').val('');
                        $('#reserve-phone').val('');
                        $('#reserve-food').prop('checked', false);
                        $('#from').datepicker("option", "minDate", 1);
                        $('#from').datepicker("option", "maxDate", "+1y");
                        $('#to').datepicker("option", "minDate", 2);
                        $('#to').datepicker("option", "maxDate", "+1y");
                        $('.reserve-bg').animate({ opacity: 0 }, 250, function() {
                            $('.reserve-bg').css('display', 'none');
                        });
                    }
                }
            });
        });
    });

    function getRoomTypes() {
        $.ajax({
            type: 'POST',
            data: {
                reverse: true
            },
            url: 'api/getRoomTypes',
            success: function(data) {
                data.forEach(function(item) {
                    var type = '<label class="checkbox">' +
                        '<input type="checkbox" id="' + item.ID + '" name="' + item.ID + '" checked />' +
                        '<span class="custom-checkbox"></span>' +
                        '<label for="' + item.ID + '">' + item.Name + '</label>' +
                        '</label>';

                    $('#roomTypes').after(type);

                    $('#' + item.ID).change(function() {
                        getRooms();
                    });
                });
            }
        });
    }

    function getRoomsWithParams(page) {
        var adults = $('#adults').val();
        var children = $('#children').val();
        var price = $('#slider-range').slider("values");

        getRooms({ price: price, adults: adults, children: children, page: page || 1 });
    }

    function getRooms(params) {
        var price = [0, 10000];
        var adults = 1;
        var children = 0;
        var page = 1;

        if (params) {
            if (params.price) {
                price = params.price;
            }

            if (params.adults) {
                adults = params.adults;
            }

            if (params.children) {
                children = params.children;
            }

            if (params.page) {
                page = params.page;
            }
        }

        var data = {
            filter: true,
            type: 'all',
            price: price,
            adults: adults,
            children: children,
            page: page
        };

        $('.filters input[type="checkbox"]').each(function() {
            if (data.type == 'all') {
                data.type = {};
            }

            if ($(this).is(':checked')) {
                data.type[$(this).attr('id')] = $(this).attr('id');
            }

            if (Object.keys(data.type).length === $('.filters input[type="checkbox"]').length) {
                data.type = 'all';
            }
        });

        $.ajax({
            type: 'POST',
            url: 'api/getRooms',
            data: data,
            success: function(data) {
                $('.rooms-list').empty();

                $('#pagination-container').pagination({
                    currentPage: page,
                    maxPages: data.total,
                    limitPages: 5,
                    onPageClick: function(page) {
                        getRoomsWithParams(page)
                    }
                });

                if (data.rows.length === 0) {
                    $('.rooms-list').append('<div class="empty">Немає доступних номерів</div>');
                    return;
                };

                data.rows.forEach(function(item) {
                    var images = [];

                    item.Images.forEach(function(image) {
                        images.push('uploads/' + image.ID + '.png');
                    });

                    var room = '<div class="room">' +
                        '<div class="img-bg">' +
                        '<img src="' + images[0] + '" alt="room' + item.ID + '" id="gallery' + item.ID + '">' +
                        '</div>' +
                        '<div class="room-info">' +
                        '<div class="left">' +
                        '<div class="name"><div class="calendar" id="calender' + item.ID + '"><input type="text" id="datepicker' + item.ID + '" style="opacity: 0; width: 0; cursor: pointer;"></div>' + item.Name + '</div>' +
                        '<div class="grid">' +
                        '<div class="type">' + item.Type + '</div>' +
                        '<div class="rooms">Кімнати: ' + item.Rooms + '</div>' +
                        '<div class="beds"><img src="images/double.png" alt="double">' + item.Double + '</div>' +
                        '<div class="beds"><img src="images/single.png" alt="single">' + item.Single + '</div>' +                            '</div>' + '<div class="benefits">';

                    item.Benefits.forEach(function(benefit) {
                        room += '<a>' + benefit + '</a>';
                    });

                    room += '</div>' +
                        '</div>' +
                        '<div class="side">' +
                        '<a onclick="reserveRoom({ id: ' + item.ID + ', type: \'' + item.Type + '\' });">Бронювати</a>' +
                        '<div class="price" id="money' + item.ID + '">' +
                        item.Price +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    $('.rooms-list').append(room);

                    $('#gallery' + item.ID).photoCarousel({
                        images: images
                    });

                    $('#money' + item.ID).moneyFormatter();

                    $('#datepicker' + item.ID).datepicker({
                        dateFormat: 'dd/mm/yy',
                        minDate: 0
                    });

                    $.ajax({
                        type: 'POST',
                        url: 'api/getRoomReservedDates',
                        data: {
                            id: item.ID
                        },
                        success: function(data) {
                            $('#datepicker' + item.ID).datepicker('option', 'beforeShowDay', function(date) {
                                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                                return [data.indexOf(string) == -1]
                            });
                        }
                    })

                    $('#calender' + item.ID).click(function() {
                        $('#datepicker' + item.ID).datepicker('show');
                    });
                });
            }
        })
    }

    function reserveRoom(room) {
        var from = $('#from');
        var to = $('#to');

        $.ajax({
            type: 'POST',
            url: 'api/getRoomStatus',
            data: {
                id: room.id,
                from: from.val(),
                to: to.val()
            },
            success: function(data) {
                if (data.status == 'error') {
                    alert(data.message);
                } else {
                    var adults = $('#adults');
                    var children = $('#children');

                    var fromval = from.val();
                    var toval = to.val();
                    var adultsval = adults.val();
                    var childrenval = children.val();

                    if (fromval == '' || toval == '') {
                        alert('Виберіть дату заїзду та виїзду');
                        return;
                    }

                    if (adultsval == '' || childrenval == '') {
                        alert('Вкажіть кількість дорослих та дітей');
                        return;
                    }

                    $('#reserve-from').val(fromval);
                    $('#reserve-to').val(toval);
                    $('#reserve-id').val(room.id);
                    $('#reserve-type').val(room.type);
                    $('#reserve-adults').val(adultsval);
                    $('#reserve-children').val(childrenval);

                    $('.reserve-bg').css('display', 'flex');
                    $('.reserve-bg').animate({ opacity: 1 }, 250);
                }
            }
        })
    }
</script>

<?php
$page->Footer();
?>