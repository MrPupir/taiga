<?php

class Page
{
    public function LoadJS($name)
    {
        ?>
        <script src="js/<?= $name ?>.js"></script>
        <?php
    }

    public function AddButton($text, $link, $selected = false)
    {
        ?>
        <a href="<?= $link ?>" class="<?php echo ($selected ? "selected" : ""); ?>"><?= $text ?></a>
        <?php
    }

    public function Header($plugins = [])
    {
        $currentPage = basename($_SERVER['PHP_SELF']);
        $currentPageName = "";

        if ($currentPage == "index.php") {
            $currentPage = 0;
            $currentPageName = "Головна";
        } elseif ($currentPage == "rooms.php") {
            $currentPage = 1;
            $currentPageName = "Номери";
        } elseif ($currentPage == "admin.php") {
            $currentPage = 2;
            $currentPageName = "Адмін";
        } elseif ($currentPage == "login.php") {
            $currentPage = 3;
            $currentPageName = "Вхід";
        } elseif ($currentPage == "logout.php") {
            $currentPage = 4;
            $currentPageName = "Вийти";
        }
        ?>
        <!DOCTYPE html>
        <html lang="uk">
        <head>
            <title>TAIGA - <? echo($currentPageName) ?></title>
            <link rel="stylesheet" href="css/jquery-ui-1.9.2.custom.min.css">
            <link rel="stylesheet" href="css/ui.jqgrid.css">
            <link rel="stylesheet" type="text/css" href="css/style.css">
            <link rel="icon" type="image/png" href="images/logo.png">

            <?php
            $this->LoadJS("jquery-1.7.2.min");
            $this->LoadJS("jquery-ui-1.9.2.custom.min");
            $this->LoadJS("locale/datepicker-uk");
            $this->LoadJS("plugin-smoothScroll");

            foreach ($plugins as $plugin) {
                $this->LoadJS($plugin);
            }
            ?>

            <script>
                $(document).ready(function() {
                    $('#loading-screen').animate({opacity: 0}, 1000, function() {
                        $('#loading-screen').addClass('hidden');
                    });

                    $('#header-logo').smoothScroll({ duration: 2000 });
                    $('#footer-logo').smoothScroll({ duration: 1000 });
                });
            </script>
        </head>
        <body>
            <div id="loading-screen">
            </div>
            <header>
                <div class="title">T A I G A</div>
                <a href="#" scroll="footer" id="header-logo" class="logo"></a>
                <nav>
                    <?php
                        $this->AddButton("Головна", "/", $currentPage == 0);
                        $this->AddButton("Номери", "/rooms", $currentPage == 1);

                        if (isset($_SESSION["currentAccount"])) {
                            $this->AddButton("Адмін", "/admin", $currentPage == 2);
                            $this->AddButton("Вийти", "/logout", $currentPage == 4);
                        } else {
                            $this->AddButton("Вхід", "/login", $currentPage == 3);
                        }
                    ?>
                </nav>
            </header>
            <div id="carousel-modal" class="modal">
                <div class="modal-content">
                    <div class="carousel">
                        <div class="prev"></div>
                        <div class="carousel-background">
                            <img class="carousel-img" src="" alt="Modal Image">
                        </div>
                        <div class="next"></div>
                    </div>
                </div>
            </div>
        <?php
    }

    public function Footer()
    {
        ?>
                <footer>
                    <div class="title">T A I G A</div>
                    <a href="#" scroll="header" id="footer-logo" class="logo"></a>
                </footer>
            </body>
        </html>
        <?php
    }
}
