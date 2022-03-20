<?php
return [
    'create table api (
        id int auto_increment primary key,
        tag varchar(20),
        access_token text,
        refresh_token text,
        x_refresh_token_expires varchar(30),
        expires_in varchar(30),
        accessTokenObject text
    )'
];