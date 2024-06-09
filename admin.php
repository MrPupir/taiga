<?php

include 'include/init.php';

if (!isset($_SESSION["currentAccount"])) {
    header("Location: /");
}

$page = new Page();
$page->Header(["plugin-multipleImagePreview", "plugin-styleSelect", "plugin-photoCarousel", "plugin-moneyFormatter", "plugin-pagination", "locale/grid-uk", "jquery.jqGrid.min"]);
?>
    <div class="logged">
        Увійдено як <?php echo $_SESSION["currentAccount"]["UserName"]; ?>
    </div>
<?php
    if (hasFlag("a") === true) {
?>
    <div class="reserved">
        <div class="container">
            <div class="category">
                Заброньовані номери
            </div>
            <div class="line"></div>
            <div class="reservations">
                <table id="grid"></table>
                <div id="gridPager"></div>
            </div>
        </div>
    </div>
<?php
    }

    if (hasFlag("b") === true) {
?>
    <div class="add">
        <div class="container">
            <div class="category">
                Додавання номерів
            </div>
            <div class="line"></div>
            <div class="form">
                <form id="addRoom">
                    <div class="images">
                        <div class="subcategory">Галерея</div>
                        <div id="imagePreview"></div>
                    </div>
                    <div class="grid">
                        <select id="type">
                            <option value="">Тип номеру</option>
                        </select>
                        <input id="name" type="text" name="name" placeholder="Назва номеру">
                        <input id="price" type="number" name="price" placeholder="Вартість номеру" min="1000" max="10000">
                        <select id="rooms">
                            <option value="">Кімнати</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                        <select id="double">
                            <option value="">Двоспальні ліжка</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                        <select id="single">
                            <option value="">Односпальні ліжка</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <input type="submit" value="Додати">
                </form>
            </div>
        </div>
    </div>
    <div class="rooms">
        <div class="container">
            <div class="category">
                Список номерів
            </div>
            <div class="line"></div>
            <div class="rooms-list">
            </div>
            <div class="pagination" id="pagination-container"></div>
        </div>
    </div>
<?php
    }
?>

    <script>
        $(document).ready(function() {
            $('#imagePreview').multipleImagePreview({
                maxImages: 10
            });

            $('#type').styleSelect();
            $('#rooms').styleSelect();
            $('#double').styleSelect();
            $('#single').styleSelect();
            getRoomTypes();
            getRooms();
            addRoomForm();

            $('#grid').jqGrid({
                url: 'api/getReservations',
                editurl: 'api/editReservation',
                datatype: 'json',
                mtype: 'POST',
                colModel: [
                    { label: 'ID', name: 'ID', key: true, width: 60 },
                    { label: 'Ім\'я', name: 'Name', width: 150 },
                    { label: 'Прізвище', name: 'SurName', width: 150 },
                    { label: 'Телефон', name: 'Phone', width: 150 },
                    { label: 'Дата заїзду', name: 'CheckIn', width: 150 },
                    { label: 'Дата виїзду', name: 'CheckOut', width: 150 },
                    { label: 'Дорослих', name: 'Adults', width: 60 },
                    { label: 'Дітей', name: 'Children', width: 60 },
                    { label: 'Номер', name: 'RoomName', width: 150 },
                    { label: 'Харчування', name: 'Food', width: 100, formatter: 'checkbox', formatoptions: { disabled: true, defaultValue: '1:0' } },
                    { label: 'Ціна', name: 'Price', width: 150, formatter: 'currency', formatoptions: { prefix: '₴' } }
                ],
                pager: "#gridPager",
                rowNum: 10,
                rowList: [10, 20, 30],
                sortname: 'ID',
                viewrecords: true,
                height: 'auto',
                shrinkToFit: true,
                sortorder: "desc",
                caption: "Заброньовані номери"
            }).jqGrid('navGrid', '#gridPager', {
                edit: false,
                add: false,
                del: true,
                search: true,
                refresh: true
            });
        });

        function getRoomTypes() {
            $.ajax({
                type: 'GET',
                url: 'api/getRoomTypes',
                success: function(data) {
                    data.forEach(function(item) {
                        $('#type').append('<option value="' + item.ID + '">' + item.Name + '</option>');
                    });
                }
            });
        }

        function getRooms(params) {
            var page = 1;

            if (params && params.page) {
                page = params.page;
            }

            var data = {
                page: page
            };

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
                            getRooms({ page: page });
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
                            '<div class="name">' + item.Name + '</div>' +
                            '<div class="grid">' +
                            '<div class="type">' + item.Type + '</div>' +
                            '<div class="rooms">Кімнати: ' + item.Rooms + '</div>' +
                            '<div class="beds"><img src="images/double.png" alt="double">' + item.Double + '</div>' +
                            '<div class="beds"><img src="images/single.png" alt="single">' + item.Single + '</div>' +
                            '</div>' + '<div class="benefits">';

                        item.Benefits.forEach(function(benefit) {
                            room += '<a>' + benefit + '</a>';
                        });

                        room += '</div>' +
                            '</div>' +
                            '<div class="side">' +
                            '<a onclick="deleteRoom(' + item.ID + ');">Видалити</a>' +
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
                    });
                }
            })
        }

        function addRoomForm() {
            $('#addRoom').submit(function(e) {
                e.preventDefault();

                var formData = new FormData();
                var images = $('#imagePreview').find('img');

                for (var i = 0; i < images.length; i++) {
                    formData.append('images[]', images[i].src);
                }

                formData.append('type', $('#type').val());
                formData.append('name', $('#name').val());
                formData.append('price', $('#price').val());
                formData.append('rooms', $('#rooms').val());
                formData.append('double', $('#double').val());
                formData.append('single', $('#single').val());

                if (formData.get('images[]') === null) {
                    alert('Виберіть хоча б одне зображення');
                    return;
                }

                if (formData.get('type') === '') {
                    alert('Виберіть тип номеру');
                    return;
                }

                if (formData.get('name') === '') {
                    alert('Введіть назву номеру');
                    return;
                }

                if (formData.get('price') === '') {
                    alert('Введіть вартість номеру');
                    return;
                }

                if (formData.get('rooms') === '') {
                    alert('Виберіть кількість кімнат');
                    return;
                }

                if (formData.get('double') === '') {
                    alert('Виберіть кількість двоспальних ліжок');
                    return;
                }

                if (formData.get('single') === '') {
                    alert('Виберіть кількість односпальних ліжок');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'api/addRoom',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            alert(data.message);
                            getRooms();
                            $('#type').val('');
                            $('#name').val('');
                            $('#price').val('');
                            $('#rooms').val('');
                            $('#double').val('');
                            $('#single').val('');
                            $('#imagePreview').empty();
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
        }

        function deleteRoom(roomID) {
            $.ajax({
                type: 'POST',
                url: 'api/deleteRoom',
                data: {
                    roomID: roomID
                },
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        getRooms();
                        $('#grid').trigger('reloadGrid');
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    </script>
<?php

$page->Footer();

?>