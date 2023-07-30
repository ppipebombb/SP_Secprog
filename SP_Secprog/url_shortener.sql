CREATE DATABASE url_shortener;

USE url_shortener;

CREATE TABLE links (
    id VARCHAR(20) PRIMARY KEY,
    userid VARCHAR(20),
    long_url VARCHAR NOT NULL,
    slug VARCHAR(50) NOT NULL,
    visits VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    id VARCHAR(20) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

 COMMIT;