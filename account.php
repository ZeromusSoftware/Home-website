<?php
    session_start();
    $page = 'mon compte';
    $site = 'Iron Hawk';
    echo '
        <!doctype html>
        <html>
            <body>
                <head>
                    <meta charset="utf-8" />
                    <title>'.$page.' - '.$site.'</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        iframe {
                            width: 100%;
                            height: 100%;
                            border: none;
                        }
                    </style>
                </head>
                <section>
                    <iframe src="http://www.paradoxe01.herobo.com/web.php?code=0" scrolling="no" id="iniframe" width=100% height=100%></iframe>
                </section>
            </body>
        </html>
    ';
?>