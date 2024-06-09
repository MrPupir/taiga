<?php

include "include/init.php";
$page = new Page();

if (!isset($_SESSION["currentAccount"])) {
    $page->Header();
    ?>
    <div class="login">
        <div class="login-form">
            <div class="title">Вхід</div>
            <form>
                <input type="text" name="login" id="login" placeholder="Логін">
                <input type="password" name="password" id="password" placeholder="Пароль">
                <input type="submit" value="Увійти">
            </form>
        </div>
    </div>

    <script>
        $('.login-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'api/login',
                data: {
                    login: $('#login').val(),
                    password: $('#password').val()
                },
                success: function(data) {
                    if (data.status == 'error') {
                        alert(data.message);
                        $('#password').val('');
                    } else {
                        window.location.href = 'admin';
                    }
                }
            });
        });
    </script>

    <? $page->Footer(); ?>
    <?php
} else {
    header("Location: admin");
}