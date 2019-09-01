SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE invoice_app;

USE invoice_app;

CREATE TABLE address (
 id mediumint(8) UNSIGNED NOT NULL,
 city varchar(32) NOT NULL,
 street varchar(32) NOT NULL,
 number varchar(16) NOT NULL,
 postcode varchar(6) NOT NULL,
 is_deleted tinyint(1) NOT NULL DEFAULT '0',
 updated_at datetime DEFAULT NULL,
 created_at datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO address
(id, city, street, number, postcode, created_at) VALUES
(1, 'Gdańsk', 'Warzywnicza', '139', '80-838', '2019-08-31 18:13:48'),
(2, 'Warszawa', 'Żołny', '99', '02-815', '2019-08-31 18:15:04'),
(3, 'Kraków', 'Taklińskiego Władysława', '122', '30-499', '2019-08-31 18:17:06'),
(4, 'Warszawa', 'Narutowicza Gabriela', '5', '05-077', '2019-08-31 18:25:42'),
(5, 'Kraków', 'Chodowieckiego Daniela', '96', '30-065', '2019-08-31 18:25:42'),
(6, 'Wrocław', 'Mokry Dwór', '131', '52-051', '2019-08-31 18:41:20');

CREATE TABLE invoice (
 id mediumint(8) UNSIGNED NOT NULL,
 sender_id mediumint(8) UNSIGNED NOT NULL,
 supplier_id mediumint(8) UNSIGNED NOT NULL,
 number varchar(16) NOT NULL,
 issuer varchar(32) NOT NULL,
 created_date date NOT NULL,
 sale_date date NOT NULL,
 payment_date date NOT NULL,
 summary varchar(128) DEFAULT NULL,
 is_deleted tinyint(1) NOT NULL DEFAULT '0',
 updated_at datetime DEFAULT NULL,
 created_at datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO invoice
(id, sender_id, supplier_id, number, issuer, created_date, sale_date, payment_date, created_at) VALUES
(1, 3, 2, '12467/2019', 'Przemysł Chmielewski', '2019-09-01', '2019-09-01', '2019-09-15', '2019-08-31 18:25:42'),
(2, 1, 1, '14235/2019', 'Maurycy Kowalczyk', '2019-09-02', '2019-09-02', '2019-09-02', '2019-08-31 18:28:02'),
(3, 3, 3, '12578/2019', 'Borys Dąbrowski', '2019-09-03', '2019-09-03', '2019-09-03', '2019-08-31 18:41:20');

CREATE TABLE invoice_item (
 id mediumint(8) UNSIGNED NOT NULL,
 invoice_id mediumint(8) UNSIGNED NOT NULL,
 name varchar(256) NOT NULL,
 price mediumint(8) UNSIGNED NOT NULL,
 is_deleted tinyint(1) NOT NULL DEFAULT '0',
 updated_at datetime DEFAULT NULL,
 created_at datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO invoice_item
(id, invoice_id, name, price, created_at) VALUES
(1, 1, 'Kabel USB', 1999, '2019-08-31 18:25:42'),
(2, 1, 'Etui na telefon Nokia 3210', 2495, '2019-08-31 18:25:42'),
(3, 2, 'IBM 5150', 99995, '2019-08-31 18:28:02'),
(4, 3, 'MPC5000', 87599, '2019-08-31 18:41:20');

CREATE TABLE sender (
 id mediumint(8) UNSIGNED NOT NULL,
 address_id mediumint(8) UNSIGNED NOT NULL,
 name varchar(128) NOT NULL,
 nip varchar(13) NOT NULL,
 is_deleted tinyint(1) NOT NULL DEFAULT '0',
 updated_at datetime DEFAULT NULL,
 created_at datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO sender
(id, address_id, name, nip, created_at) VALUES
(1, 1, 'Acme', '1220640914', '2019-08-31 18:13:48'),
(2, 2, 'Globex', '5319922761', '2019-08-31 18:15:04'),
(3, 5, 'Universal Exports', '4975458864', '2019-08-31 18:25:42');

CREATE TABLE supplier (
 id mediumint(8) UNSIGNED NOT NULL,
 address_id mediumint(8) UNSIGNED NOT NULL,
 name varchar(128) NOT NULL,
 nip varchar(13) NOT NULL,
 is_deleted tinyint(1) NOT NULL DEFAULT '0',
 updated_at datetime DEFAULT NULL,
 created_at datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO supplier
(id, address_id, name, nip, created_at) VALUES
(1, 3, 'Cyberdyne Systems', '8221738694', '2019-08-31 18:17:06'),
(2, 4, 'Sinnesloschen', '3792426637', '2019-08-31 18:25:42'),
(3, 6, 'Phoenix Foundation', '9314040142', '2019-08-31 18:41:20');

ALTER TABLE address ADD PRIMARY KEY (id);
ALTER TABLE invoice ADD PRIMARY KEY (id), ADD KEY invoice_sender_id_fk (sender_id), ADD KEY invoice_supplier_id_fk (supplier_id);
ALTER TABLE invoice_item ADD PRIMARY KEY (id), ADD KEY invoice_items_invoice_id_fk (invoice_id);
ALTER TABLE sender ADD PRIMARY KEY (id), ADD KEY supplier_address_id_fk (address_id);
ALTER TABLE supplier ADD PRIMARY KEY (id), ADD KEY supplier_address_id_fk (address_id);

ALTER TABLE address MODIFY id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE invoice MODIFY id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE invoice_item MODIFY id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE sender MODIFY id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE supplier MODIFY id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE invoice
 ADD CONSTRAINT invoice_sender_id_fk FOREIGN KEY (sender_id) REFERENCES sender (id),
 ADD CONSTRAINT invoice_supplier_id_fk FOREIGN KEY (supplier_id) REFERENCES supplier (id);
ALTER TABLE invoice_item ADD CONSTRAINT invoice_items_invoice_id_fk FOREIGN KEY (invoice_id) REFERENCES invoice (id);
ALTER TABLE sender ADD CONSTRAINT sender_address_id_fk FOREIGN KEY (address_id) REFERENCES address (id);
ALTER TABLE supplier ADD CONSTRAINT supplier_address_id_fk FOREIGN KEY (address_id) REFERENCES address (id);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;