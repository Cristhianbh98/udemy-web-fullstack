CREATE DATABASE IF NOT EXISTS api_rest_laravel_blog;

USE api_rest_laravel_blog;

CREATE TABLE users (
  id              int(255) auto_increment NOT NULL,
  name            varchar(255) NOT NULL,
  surname         varchar(255),
  role            varchar(20),
  email           varchar(255) NOT NULL,
  password        varchar(255) NOT NULL,
  description     text,
  image           varchar(255),
  created_at      datetime DEFAULT NULL,
  updated_at      datetime DEFAULT NULL,
  remember_token  varchar(255),
  CONSTRAINT      pk_user PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE categories (
  id              int(255) auto_increment NOT NULL,
  name            varchar(255) NOT NULL,
  created_at      datetime DEFAULT NULL,
  updated_at      datetime DEFAULT NULL,
  CONSTRAINT      pk_category PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE posts (
  id              int(255) auto_increment NOT NULL,
  user_id         int(255) NOT NULL,
  category_id     int(255) NOT NULL,
  title           varchar(255) NOT NULL,
  content         text,
  image           varchar(255),
  created_at      datetime DEFAULT NULL,
  updated_at      datetime DEFAULT NULL,
  CONSTRAINT      pk_post PRIMARY KEY(id),
  CONSTRAINT      fk_post_user FOREIGN KEY(user_id) REFERENCES users(id),
  CONSTRAINT      fk_post_category FOREIGN KEY(category_id) REFERENCES categories(id)
)ENGINE=InnoDB;
