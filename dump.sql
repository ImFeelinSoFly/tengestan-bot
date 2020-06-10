SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `Accounts` (
  `id` int(11) NOT NULL,
  `login` text,
  `password` text,
  `pwd_show` text NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `email` text,
  `date` int(11) NOT NULL,
  `lock` int(11) NOT NULL,
  `notify` int(11) NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Accounts` (`id`, `login`, `password`, `pwd_show`, `rang`, `email`, `date`, `lock`, `notify`, `balance`) VALUES
(1, 'admin', '5135e749caa1d5f3588cee0d9a999619', '', 1, '', 0, 0, 1, 0);

CREATE TABLE `a_menu` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `alias` text NOT NULL,
  `modal` text NOT NULL,
  `sid` int(11) NOT NULL,
  `pid` text,
  `operator` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `a_menu` (`id`, `name`, `alias`, `modal`, `sid`, `pid`, `operator`, `active`) VALUES
(2, 'Настройки', 'options', '', 2, '2', 0, 0),
(3, 'Заказы', 'orders', '', 3, '2', 0, 0),
(4, 'Юзеры', 'users', '', 7, '2', 0, 0),
(6, 'Каталог', 'catalog', '', 4, '2', 1, 0),
(7, 'Рассылка', 'delivery', 'add_delivery', 5, '2', 0, 0),
(9, 'Меню бота', 'bot_menu', '', 1, NULL, 0, 0),
(10, 'Операторы', 'managers', '', 8, NULL, 0, 0),
(11, 'Статистика', 'status', '', 0, NULL, 0, 0),
(12, 'Пополнения', 'payments', '', 3, NULL, 0, 0);

CREATE TABLE `a_zakaz` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `suma` text NOT NULL,
  `discount` text NOT NULL,
  `buy` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `pay_type` int(11) NOT NULL COMMENT '1 - QIWO, 2 - YAD, 4 BTC',
  `id_wallet_qiwi` int(11) NOT NULL,
  `info_client` text NOT NULL,
  `how_method` int(11) NOT NULL,
  `phone` text NOT NULL,
  `addr_coin` text NOT NULL,
  `confirmations` int(11) NOT NULL,
  `tx_hash` text NOT NULL,
  `signature` text NOT NULL,
  `bot_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `a_zakaz_items` (
  `id` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `sum` text NOT NULL,
  `all_sum` text NOT NULL,
  `id_zakaz` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `basket` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `photo_message_id` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bot_list` (
  `id` int(11) NOT NULL,
  `webhook` text NOT NULL,
  `token` text NOT NULL,
  `botname` text NOT NULL,
  `username` text NOT NULL,
  `id_bot` text NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `catalog` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `active` int(11) DEFAULT NULL,
  `name` text NOT NULL,
  `description` text COMMENT 'Описание',
  `diameter` text COMMENT 'Диаметр',
  `price` float DEFAULT NULL COMMENT 'Цена',
  `currency` text COMMENT 'Валюта',
  `link` text,
  `height` text NOT NULL,
  `content` mediumtext NOT NULL,
  `channel_id` text NOT NULL,
  `multi` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `sales` int(11) NOT NULL,
  `loolol` text NOT NULL,
  `testt` text NOT NULL,
  `gramm` text NOT NULL,
  `description1` text NOT NULL,
  `about` text NOT NULL,
  `upack` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_product` int(11) NOT NULL,
  `type_access` int(11) NOT NULL,
  `type_content` text NOT NULL,
  `days` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `catalog` (`id`, `id_item`, `active`, `name`, `description`, `diameter`, `price`, `currency`, `link`, `height`, `content`, `channel_id`, `multi`, `count`, `sales`, `loolol`, `testt`, `gramm`, `description1`, `about`, `upack`, `user_id`, `amount_product`, `type_access`, `type_content`, `days`) VALUES
(1, 0, 1, 'Vk.com (VK, ВК) [авторег, РФ, тел:пасс] Женский', 'Info Info Info Info Info Info Info Info\r\nInfo Info', NULL, 2, NULL, NULL, '', '5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '', NULL, 177, 0, '', '', '400гр', '', '', 10, 1, 9, 1, '', ''),
(2, 0, 1, 'Avito.ru (АВИТО) М ( SMS ) ', 'Info Info Info Info Inf', NULL, 5, NULL, NULL, '', '73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '9', NULL, 200, 0, '', '', '100гр', '', 'LOL', 34, 1, 12, 2, '', '7'),
(5, 0, 1, 'Gmail.com ( Гугл ) ( SMS ) ', 'dev dev dev dev dev dev dev dev dev dev dev dev', NULL, 101, NULL, NULL, '', '73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '', NULL, 200, 0, '', '', '55', '', '', 0, 1, 0, 1, '', ''),
(6, 0, 1, 'Mamba.ru Ж ( SMS ) + загружены фотки', 'Тестовое описание товара', NULL, 25, NULL, NULL, '', '73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '2', NULL, 200, 0, '', '', '1 грм', '', '', 0, 1, 0, 2, '', '30'),
(14, 0, 1, 'yandex.ru М ( Без SMS )', NULL, NULL, 6, NULL, NULL, '', 'xj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '9', NULL, 197, 0, '', '', '', '', '', 0, 1, 0, 2, '', '90'),
(17, 0, 1, 'mail.ru М (мой мир) ( SMS ) ', NULL, NULL, 150, NULL, NULL, '', '15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '9', NULL, 196, 0, '', '', '', '', '', 0, 1, 0, 1, 'Лицензия', ''),
(19, 0, 1, '4game.com ( SMS ) ', NULL, NULL, 1000, NULL, NULL, '', '73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA\r\nyU960kL6JBte\r\nB56COIs7a4ro\r\nl5r1fZ4rY6XB\r\nA6NUxm2P7hv2\r\nuE9D90J9oOxv\r\nkIPj2SV1z9y0\r\nYAKk7X20jt3h\r\nTk689Gx2sVaD\r\n7axO61f1HIXs\r\nHVk31T7dP8on\r\n7Px0U2UCbtr0\r\ndFVA09k2tYe7\r\n3dDG91l4zASe\r\nT5I3bzC5o4Bb\r\n384lJ9OBozIo\r\nAOkbz42G0A6i\r\nAd13ieK42VTl\r\n4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT', '2', NULL, 200, 0, '', '', '', '', '', 0, 1, 0, 1, '', ''),
(20, 0, 1, 'Vk.com (VK, ВК) [авторег, РФ, тел:пасс] Женский + фото', NULL, NULL, 2, NULL, NULL, '', '4X2l2epLLoC6\r\ng6rLjP1UUo68\r\nNINpIx5118rb\r\n449CLTpygv7F\r\nLdS835jOv6Af\r\nE729dAGA8vhs\r\nGcI3v2mxUI03\r\n1g61mkCSLf5H\r\nbYPNM4g290ur\r\ndiLeKg4DF043\r\nr6gpEjM42RH1\r\nyftA7OU40lZ7\r\n8z6v4TSne3NH\r\nDoXH3iD6e8b2\r\nvi84CXJ2om4B\r\nU11JhbThOx15\r\nPOS1bd5mB05m\r\nShhfr18T8BT7\r\nvv4g5FuCD87N\r\nUlK2o09lDLc1\r\n122lJO7jINuz\r\n263hcBeVP1Xl\r\nna925U0HHxcJ\r\n6nj3vO1M6kJY\r\nPsOzD3f11iL4\r\nUj3H22eVr4pT\r\n18OS4eLzHn6u\r\nj4d06U7SxXKg\r\n1zJ9PnuBs38U\r\njTYKc3cS90n3\r\n1hBR9e56pJBv\r\nrSd22z2lTVY6\r\nAjhMi7e1A61Z\r\nf98ay32jNAVG\r\nV9rR0t5HaM4t\r\n0HDik1gT3g8P\r\nE2am8NEJ63ek\r\nAp2z5ICr92Hs\r\nhBZ9Re587Zxg\r\nRY0P3zS65obr\r\nKg5SP4hf7N6y\r\n3tg6u1b5DRMB\r\n9RZ7CLyg9bl7\r\nE9D3xh24aMuD\r\nc50ZhGic08GT\r\n73VkZi8a1UoY\r\noA6iHC7Zx34z\r\nCJDj9eZzi811\r\nxj1JgEZE1t20\r\n15zC5v7zTRiN\r\ny33iKUtI37Gi\r\n5325rxAVdPeG\r\n0S5kAmfgI25U\r\npyp767NJv6PJ\r\nnX71aL0vGi3X\r\nHgTFcNzr2796\r\nhgT01mGZD54l\r\nDUH8t8Nj0l7l\r\nEn4Po53sRMd6\r\nUmh60L1e5DlL\r\ncz96dNMJzE47\r\nUhgL5L7E7e1o\r\n0MdS7Bnu84rF\r\nM3zEknL842sS\r\nt5u28HA9vMAs\r\n7Nmlzf5J45KN\r\n2LNu5eGEd9n8\r\ntFuiXv7Y02O0\r\n5xsCM0l55iCK\r\n61anp0V7cGXR\r\nN1d9CoP5p4rZ\r\no1EEo61s1AHe\r\no8tZlf0LP17M\r\n75jdJcONP33x\r\nd84FZUDnxk27\r\n0Px3GiR6cGr2\r\n92Mio5US3Mut\r\nuX32zVdDO6t0\r\n9x4n6LFUcyN1\r\nv0g2tH4kUT5B\r\ndYF2Zr56C7gx\r\n8MYxh64ERg7e\r\n2B6Te8k4MltA', '', NULL, 83, 0, '', '', '', '', '', 0, 1, 0, 0, '', '');

CREATE TABLE `cat_photo` (
  `id` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `file` text NOT NULL,
  `file_id` text NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_photo` (`id`, `id_cat`, `file`, `file_id`, `type`) VALUES
(1, 1, '5182769.jpg', 'AgACAgQAAxkDAAI4y15ov-_S1tnvZgYJVDLsAgmwaQubAALaqTEb6eD8Ult1NqNyITWbbheqGwAEAQADAgADdwADLBQBAAEYBA', 0),
(2, 2, '6585414.jpg', 'AgACAgQAAxkDAAI23l5oeXx1rpehuTOwIy-kMLS6peTgAAJIqTEbrSn8UdIYq025Wa2Dm4spGwAEAQADAgADeQADn70EAAEYBA', 0),
(3, 6, '4238826.jpg', 'AgACAgQAAxkDAAI24F5oeX53Rbyl_911CVzKIvL1fZCmAAJaqTEbEir9UcxG4g92dhcj5GqoGwAEAQADAgADdwAD3I4AAhgE', 0),
(4, 5, '2895429.gif', 'AgACAgQAAxkDAAI2315oeX3sV9TXUDpHq_fsU0AfIc17AALUqTEbUmNFUyOMMX0PZEoJbcIsGwAEAQADAgADeAADNCIHAAEYBA', 0),
(5, 14, '524502.jpeg', 'AgACAgQAAxkDAAI40V5ov_Xdqh-dhhB8KvymTD_-EiZvAAKwqTEbCChFUxU4Lb_f2yx59BexGgAEAQADAgADeQADLLEEAAEYBA', 0),
(6, 17, '1812425.png', 'AgACAgQAAxkDAAI4zV5ov_EsXERyHJjLtuXIFrCwFFL5AAKwqTEb6R6kUx1RD-e6rjL3AAECsRoABAEAAwIAA3gAA-X_BAABGAQ', 0),
(8, 19, '5265878.jpg', 'AgACAgQAAxkDAAI40l5ov_Xrw2qf08PdNqnIG0p5_Ez-AAIyqjEbMDfFU8CH8_-Sa5GGD9MgGwAEAQADAgADbQADdeYBAAEYBA', 0),
(9, 20, '155518.jpg', 'AgACAgQAAxkDAAI4zl5ov_LzgvzCXiPpBc3UC6sU6KdAAAKjqjEbTFpFUzgdR6_1UBNBhSiOIl0AAwEAAwIAA3kAA1rgAAIYBA', 0);

CREATE TABLE `cat_struktura` (
  `id` int(11) NOT NULL,
  `id1` int(11) DEFAULT NULL COMMENT 'ID item',
  `id2` int(11) DEFAULT NULL COMMENT 'ID section'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_struktura` (`id`, `id1`, `id2`) VALUES
(394, 1, 690),
(395, 1, 701),
(396, 1, 691),
(397, 2, 701),
(398, 5, 701),
(399, 6, 701),
(400, 14, 690),
(401, 14, 699),
(402, 17, 690),
(403, 17, 701),
(404, 19, 699),
(405, 19, 700),
(406, 20, 690),
(407, 20, 701),
(408, 20, 691);

CREATE TABLE `currency_list` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `chk` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `currency_list` (`id`, `name`, `chk`) VALUES
(1, 'RUB', '1'),
(4, '฿', '1'),
(6, 'LTC', '1'),
(7, 'EUR', '1'),
(55, 'грн', '1');

CREATE TABLE `delivery_list` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `bonus` text NOT NULL,
  `inline_link` text NOT NULL,
  `date_run` text NOT NULL,
  `date` text NOT NULL,
  `inline_name` text NOT NULL,
  `status` int(11) NOT NULL,
  `progress` int(11) NOT NULL,
  `max_users` int(11) NOT NULL,
  `type_d` int(11) NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `how_delivery` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `how_delivery` (`id`, `name`, `active`) VALUES
(1, 'EMS', 0),
(2, 'Почта Росси', 0),
(3, 'Курьер', 0);

CREATE TABLE `list_referer` (
  `id` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `referer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `losg_action` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `content` text NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `multiple_qiwi` (
  `id` int(11) NOT NULL,
  `wallet` text NOT NULL,
  `api_key` text NOT NULL,
  `hookId` text NOT NULL,
  `limit` text NOT NULL,
  `balance` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orders_referers` (
  `id` int(11) NOT NULL,
  `zakaz_id` int(11) NOT NULL,
  `id_chat_client` int(11) NOT NULL,
  `referer` int(11) NOT NULL,
  `sum` float NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `payment_users` (
  `id` int(11) NOT NULL,
  `id_chat` text NOT NULL,
  `amount` text NOT NULL,
  `btc_address` text NOT NULL,
  `btc_amount` text NOT NULL,
  `confirmations` int(11) NOT NULL,
  `tx_hash` text NOT NULL,
  `date` int(11) NOT NULL,
  `pay_type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `signature` text NOT NULL,
  `bot_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `product_value_units` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `amount` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `product_value_units` (`id`, `name`, `value`, `amount`) VALUES
(12, '12', '1', '33');

CREATE TABLE `prop_list_catalog` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `alias` text NOT NULL,
  `type` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `private` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prop_list_catalog` (`id`, `name`, `alias`, `type`, `active`, `sid`, `private`) VALUES
(4, 'Название', 'name', 1, 0, 3, 0),
(5, 'Описание', 'description', 2, 1, 7, 0),
(6, 'Цена', 'price', 1, 0, 6, 0),
(7, 'Активен', 'active', 1, 0, 5, 1),
(10, 'Цифровой товар', 'content', 2, 0, 0, 0),
(11, 'Кол-во', 'count', 1, 0, 4, 0),
(17, 'Производитель', 'about', 1, 1, 1, 0),
(18, 'Упаковка шт', 'upack', 1, 1, 2, 0),
(19, 'Связь товара', 'amount_product', 3, 1, 0, 0),
(22, 'Дней', 'days', 1, 0, 0, 0);

CREATE TABLE `sales_total` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `amount` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `ref_bonus` text NOT NULL,
  `ref_at_friend` text NOT NULL,
  `ref_friend_5` int(11) NOT NULL,
  `ref_friend_15` int(11) NOT NULL,
  `ref_friend_30` int(11) NOT NULL,
  `ref_friend_50` int(11) NOT NULL,
  `ref_friend_100` int(11) NOT NULL,
  `type_referer` int(11) NOT NULL,
  `gain_proc` int(11) NOT NULL,
  `start_bonus` int(11) NOT NULL,
  `daily_bonus` int(11) NOT NULL,
  `qiwi_token` text NOT NULL,
  `qiwi_wallet` text NOT NULL,
  `bot_token` text NOT NULL,
  `bot_name` text NOT NULL,
  `bot_username` text NOT NULL,
  `max_connections` int(11) NOT NULL,
  `chat_id_admin` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `basket_id` int(11) NOT NULL,
  `step_text` text NOT NULL,
  `step_get_name` int(11) NOT NULL,
  `how` int(11) NOT NULL,
  `get_phone` int(11) NOT NULL,
  `salute` text NOT NULL,
  `confirmations` int(11) NOT NULL,
  `wallet` text NOT NULL,
  `level_btc` text NOT NULL,
  `token_list` text NOT NULL,
  `ref_button` int(11) NOT NULL,
  `qiwi_method` int(11) NOT NULL,
  `type_payment` int(11) NOT NULL,
  `min_sum_balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting` (`id`, `ref_bonus`, `ref_at_friend`, `ref_friend_5`, `ref_friend_15`, `ref_friend_30`, `ref_friend_50`, `ref_friend_100`, `type_referer`, `gain_proc`, `start_bonus`, `daily_bonus`, `qiwi_token`, `qiwi_wallet`, `bot_token`, `bot_name`, `bot_username`, `max_connections`, `chat_id_admin`, `catalog_id`, `basket_id`, `step_text`, `step_get_name`, `how`, `get_phone`, `salute`, `confirmations`, `wallet`, `level_btc`, `token_list`, `ref_button`, `qiwi_method`, `type_payment`, `min_sum_balance`) VALUES
(1, '15', '1', 15, 3, 5, 10, 15, 1, 20, 0, 10, '0000000000000', '00000000000', '', '', '', 100, 0, 687, 43, 'Адрес доставки', 2, 2, 2, '[DEMO] &lt;b&gt;&#x414;&#x41E;&#x411;&#x420;&#x41E; &#x41F;&#x41E;&#x416;&#x410;&#x41B;&#x41E;&#x412;&#x410;&#x422;&#x42C; &#x412; &#x41C;&#x410;&#x413;&#x410;&#x417;&#x418;&#x41D;!&lt;/b&gt;\n\n&lt;b&gt;&#x412;&#x41D;&#x418;&#x41C;&#x410;&#x41D;&#x418;&#x415;!!!&lt;/b&gt;\n&#x41F;&#x43E;&#x434;&#x43F;&#x438;&#x448;&#x438;&#x442;&#x435;&#x441;&#x44C; &#x43D;&#x430; &#x442;&#x435;&#x43B;&#x435;&#x433;&#x440;&#x430;&#x43C;-&#x43A;&#x430;&#x43D;&#x430;&#x43B; &#x43D;&#x430;&#x448;&#x438;&#x445; &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; @lololest &#x438; &#x443;&#x447;&#x430;&#x441;&#x442;&#x432;&#x443;&#x439;&#x442;&#x435; &#x441;&#x43F;&#x435;&#x446;&#x438;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x445; &#x430;&#x43A;&#x446;&#x438;&#x44F;&#x445;!\n&#x41E;&#x431;&#x44F;&#x437;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x43E; &#x434;&#x43E;&#x431;&#x430;&#x432;&#x44C;&#x442;&#x435; &#x441;&#x435;&#x431;&#x435; &#x43A;&#x43E;&#x43D;&#x442;&#x430;&#x43A;&#x442; &#x43A;&#x440;&#x443;&#x433;&#x43B;&#x43E;&#x441;&#x443;&#x442;&#x43E;&#x447;&#x43D;&#x43E;&#x439; &#x441;&#x43B;&#x443;&#x436;&#x431;&#x44B; &#x43F;&#x43E;&#x434;&#x434;&#x435;&#x440;&#x436;&#x43A;&#x438; &#x431;&#x43E;&#x442;&#x430; @testestestestx!', 0, '', '', '', 694, 1, 1, 1);

CREATE TABLE `setting_payment` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `name_service` text NOT NULL,
  `secret_key` text NOT NULL,
  `pub_key` text NOT NULL,
  `hookId` text NOT NULL,
  `api_key` text NOT NULL,
  `wallet` text NOT NULL,
  `confirmations` int(11) NOT NULL,
  `level_btc` text NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting_payment` (`id`, `name`, `name_service`, `secret_key`, `hookId`, `api_key`, `wallet`, `confirmations`, `level_btc`, `active`) VALUES
(1, 'Qiwi', 'QIWI', '', '', '', '', 0, '', 0),
(2, 'Yandex', 'Яндек деньги', '', '', '', '', 0, '', 0),
(3, 'Card', 'Visa/MastecCard', '', '', '', '', 0, '', 0),
(4, 'Bitcoin', 'Bitcoin', '', '', '', '', 2, 'medium', 0),
(5, 'Exmo-code', 'Exmo-code', '', '', '', '', 0, '', 1),
(6, 'Баланс юзера', '', '', '', '', '', 0, '', 1),
(7, 'Payment_qiwi ', '', '', '', '', '', 0, '', 1),
(8, 'unitpay', 'UnitPay', '', '', '', '', 0, '', 1),
(9, 'Free-kassa', 'Free-Kassa', '', '', '', '', 0, '', 1);

CREATE TABLE `static_day` (
  `id` int(11) NOT NULL,
  `date` text NOT NULL,
  `id_chat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `struktura` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `content` text NOT NULL,
  `url` text NOT NULL,
  `sid` int(11) NOT NULL,
  `pid` text,
  `active` int(11) NOT NULL,
  `inside` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `struktura` (`id`, `name`, `content`, `url`, `sid`, `pid`, `active`, `inside`, `type`, `pos`, `category_id`, `parent_id`) VALUES
(1, 'Профиль', '&#x2139;&#xFE0F; <b>&#x41F;&#x440;&#x43E;&#x444;&#x438;&#x43B;&#x44C;</b>\n<code>- - - - - - - - - - - - - - - - - - - - - - - - </code>\n\n&#x1F464; <b>&#x418;&#x43C;&#x44F;:</b> {name}\n&#x1F511; <b>ID:</b> {id_chat}\n&#x25AB;&#xFE0F; <b>Username:</b> {username}\n{discount}{ref_money}\n{USER_BALANCE}\n\n<code>- - - - - - - - - - - - - - - - - - - - - - - - </code>', '', 1, '2', 0, 1, 0, 1, 0, 0),
(2, '666', '', '', 0, '0', 0, 0, 0, 0, 0, 0),
(5, 'Мои покупки', '<code>- - - - - - - - - - - - - - - </code>\n&#x267B;&#xFE0F; <b>&#x421;&#x43F;&#x438;&#x441;&#x43E;&#x43A;  &#x43C;&#x43E;&#x438;&#x445; &#x43F;&#x43E;&#x43A;&#x443;&#x43F;&#x43E;&#x43A;</b>\n<code>- - - - - - - - - - - - - - - </code>\n', '', 4, '2', 1, 0, 0, 1, 0, 0),
(10, 'Закон', '&#x421;&#x435;&#x43C;&#x435;&#x43D;&#x430; &#x43A;&#x43E;&#x43D;&#x43E;&#x43F;&#x43B;&#x438; - &#x44D;&#x442;&#x43E; &#x43D;&#x435; &#x442;&#x43E;&#x43B;&#x44C;&#x43A;&#x43E; &#x43F;&#x43E;&#x441;&#x435;&#x432;&#x43E;&#x447;&#x43D;&#x44B;&#x439; &#x43C;&#x430;&#x442;&#x435;&#x440;&#x438;&#x430;&#x43B; &#x434;&#x43B;&#x44F; &#x433;&#x440;&#x43E;&#x432;&#x435;&#x440;&#x43E;&#x432;, &#x43D;&#x43E; &#x438; &#x43E;&#x440;&#x438;&#x433;&#x438;&#x43D;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x439; &#x441;&#x443;&#x432;&#x435;&#x43D;&#x438;&#x440;, &#x44D;&#x43A;&#x441;&#x442;&#x440;&#x430;&#x432;&#x430;&#x433;&#x430;&#x43D;&#x442;&#x43D;&#x430;&#x44F; &#x43D;&#x430;&#x436;&#x438;&#x432;&#x43A;&#x430; &#x434;&#x43B;&#x44F; &#x440;&#x44B;&#x431;&#x430;&#x43B;&#x43A;&#x438;, &#x432;&#x435;&#x43B;&#x438;&#x43A;&#x43E;&#x43B;&#x435;&#x43F;&#x43D;&#x43E;&#x435; &#x43A;&#x43E;&#x441;&#x43C;&#x435;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x430;&#x44F; &#x441;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43B;&#x44F;&#x44E;&#x449;&#x430;&#x44F; &#x43A;&#x440;&#x435;&#x43C;&#x43E;&#x432; &#x438; &#x43C;&#x430;&#x441;&#x43E;&#x43A;, &#x43B;&#x435;&#x43A;&#x430;&#x440;&#x441;&#x442;&#x432;&#x43E; &#x438;&#x437; &#x442;&#x44B;&#x441;&#x44F;&#x447; &#x440;&#x435;&#x446;&#x435;&#x43F;&#x442;&#x43E;&#x432; &#x43D;&#x430;&#x440;&#x43E;&#x434;&#x43D;&#x43E;&#x439; &#x43C;&#x435;&#x434;&#x438;&#x446;&#x438;&#x43D;&#x44B;. &#x41D;&#x43E;, &#x43F;&#x440;&#x430;&#x43A;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438; &#x432;&#x441;&#x435; &#x43F;&#x43E;&#x441;&#x435;&#x442;&#x438;&#x442;&#x435;&#x43B;&#x438; &#x438;&#x43D;&#x442;&#x435;&#x440;&#x43D;&#x435;&#x442;-&#x43C;&#x430;&#x433;&#x430;&#x437;&#x438;&#x43D;&#x430; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x43A;&#x430;&#x43D;&#x43D;&#x430;&#x431;&#x438;&#x441;&#x430; &#x437;&#x430;&#x434;&#x430;&#x44E;&#x442;&#x441;&#x44F; &#x43E;&#x434;&#x43D;&#x438;&#x43C; &#x438; &#x442;&#x435;&#x43C; &#x436;&#x435; &#x432;&#x43E;&#x43F;&#x440;&#x43E;&#x441;&#x43E;&#x43C;: &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x43D;&#x430; &#x43B;&#x438; &#x43F;&#x43E;&#x43A;&#x443;&#x43F;&#x43A;&#x430; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x43A;&#x43E;&#x43D;&#x43E;&#x43F;&#x43B;&#x438; &#x438;, &#x435;&#x441;&#x43B;&#x438; &#x43D;&#x435;&#x442;, &#x442;&#x43E; &#x43A;&#x430;&#x43A;&#x43E;&#x435; &#x437;&#x430; &#x44D;&#x442;&#x43E; &#x43F;&#x440;&#x435;&#x434;&#x443;&#x441;&#x43C;&#x43E;&#x442;&#x440;&#x435;&#x43D;&#x43E; &#x43D;&#x430;&#x43A;&#x430;&#x437;&#x430;&#x43D;&#x438;&#x435;?\n\n&#x41A;&#x430;&#x43A; &#x44D;&#x442;&#x43E; &#x43D;&#x435; &#x43F;&#x43E;&#x43A;&#x430;&#x436;&#x435;&#x442;&#x441;&#x44F; &#x441;&#x442;&#x440;&#x430;&#x43D;&#x43D;&#x44B;&#x43C;, &#x43D;&#x43E; &#x432; &#x420;&#x424; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x43E;&#x434;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x44B;&#x435; &#x43E;&#x440;&#x433;&#x430;&#x43D;&#x44B; &#x43D;&#x438;&#x43A;&#x430;&#x43A; &#x43D;&#x435; &#x43E;&#x442;&#x43D;&#x43E;&#x441;&#x44F;&#x442;&#x441;&#x44F; &#x43A; &#x43F;&#x43E;&#x43A;&#x443;&#x43F;&#x43A;&#x435; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x43A;&#x43E;&#x43D;&#x43E;&#x43F;&#x43B;&#x438;. &#x41E;&#x434;&#x43D;&#x430;&#x43A;&#x43E;, &#x441;&#x442;&#x43E;&#x438;&#x442; &#x437;&#x430;&#x43F;&#x43E;&#x43C;&#x43D;&#x438;&#x442;&#x44C;, &#x447;&#x442;&#x43E; &#x43F;&#x43E;&#x43A;&#x443;&#x43F;&#x430;&#x44F; &#x434;&#x430;&#x43D;&#x43D;&#x44B;&#x439; &#x43F;&#x440;&#x43E;&#x434;&#x443;&#x43A;&#x442; &#x432; &#x43B;&#x44E;&#x431;&#x43E;&#x43C; &#x438;&#x43D;&#x442;&#x435;&#x440;&#x43D;&#x435;&#x442;-&#x43C;&#x430;&#x433;&#x430;&#x437;&#x438;&#x43D;&#x435;, &#x432;&#x44B; &#x43D;&#x435; &#x43D;&#x430;&#x440;&#x443;&#x448;&#x430;&#x435;&#x442;&#x435; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x43E;&#x432; &#x420;&#x424;, &#x430; &#x44D;&#x442;&#x43E; &#x433;&#x43B;&#x430;&#x432;&#x43D;&#x43E;&#x435;. &#x41A; &#x442;&#x43E;&#x43C;&#x443; &#x436;&#x435;, &#x441;&#x435;&#x43C;&#x435;&#x43D;&#x430; &#x43A;&#x43E;&#x43D;&#x43E;&#x43F;&#x43B;&#x438;, &#x440;&#x43E;&#x432;&#x43D;&#x43E; &#x442;&#x430;&#x43A; &#x436;&#x435;, &#x43A;&#x430;&#x43A; &#x438; &#x433;&#x438;&#x431;&#x440;&#x438;&#x434;&#x43E;&#x432; &#x441;&#x435;&#x43B;&#x435;&#x43A;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x44B;&#x445; &#x441;&#x435;&#x43C;&#x44F;&#x43D;, &#x43D;&#x435; &#x441;&#x43E;&#x434;&#x435;&#x440;&#x436;&#x430;&#x442; &#x43D;&#x430;&#x440;&#x43A;&#x43E;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438;&#x445; &#x432;&#x435;&#x449;&#x435;&#x441;&#x442;&#x432;, &#x43E;&#x43A;&#x430;&#x437;&#x44B;&#x432;&#x430;&#x44E;&#x449;&#x438;&#x445; &#x44D;&#x439;&#x444;&#x43E;&#x440;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x43E;&#x435; &#x432;&#x43E;&#x437;&#x434;&#x435;&#x439;&#x441;&#x442;&#x432;&#x438;&#x435;. &#x410; &#x435;&#x441;&#x43B;&#x438; &#x43D;&#x435;&#x442; &#x432; &#x441;&#x435;&#x43C;&#x435;&#x43D;&#x430;&#x445; &#x43D;&#x438;&#x447;&#x435;&#x433;&#x43E; &#x437;&#x430;&#x43F;&#x440;&#x435;&#x449;&#x435;&#x43D;&#x43D;&#x43E;&#x433;&#x43E;, &#x441;&#x43B;&#x435;&#x434;&#x43E;&#x432;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x43E; &#x438; &#x43D;&#x435;&#x442; &#x441;&#x43C;&#x44B;&#x441;&#x43B;&#x430; &#x438;&#x445; &#x437;&#x430;&#x43F;&#x440;&#x435;&#x449;&#x430;&#x442;&#x44C;. &#x41F;&#x440;&#x43E;&#x441;&#x442;&#x430;&#x44F; &#x438; &#x43F;&#x43E;&#x43D;&#x44F;&#x442;&#x43D;&#x430;&#x44F; &#x43B;&#x43E;&#x433;&#x438;&#x43A;&#x430;, &#x43A;&#x43E;&#x442;&#x43E;&#x440;&#x43E;&#x439;, &#x441;&#x43B;&#x430;&#x432;&#x430; &#x411;&#x43E;&#x433;&#x443;, &#x440;&#x443;&#x43A;&#x43E;&#x432;&#x43E;&#x434;&#x441;&#x442;&#x432;&#x43E;&#x432;&#x430;&#x43B;&#x438;&#x441;&#x44C; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x43E;&#x434;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x44B;&#x435; &#x43E;&#x440;&#x433;&#x430;&#x43D;&#x44B; &#x420;&#x424;.\n\n&#x423;&#x447;&#x438;&#x442;&#x44B;&#x432;&#x430;&#x44F; &#x43F;&#x43E;&#x441;&#x442;&#x430;&#x43D;&#x43E;&#x432;&#x43B;&#x435;&#x43D;&#x438;&#x435; - 681 (&#x43E;&#x442; 30 &#x438;&#x44E;&#x43D;&#x44F; 1998 &#x433;.) &#x438; &#x43F;&#x440;&#x438;&#x43B;&#x430;&#x433;&#x430;&#x435;&#x43C;&#x44B;&#x439; &#x43A; &#x43F;&#x43E;&#x441;&#x442;&#x430;&#x43D;&#x43E;&#x432;&#x43B;&#x435;&#x43D;&#x438;&#x44E; &#x441;&#x43F;&#x438;&#x441;&#x43E;&#x43A; &#x43D;&#x430;&#x440;&#x43A;&#x43E;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438;&#x445; &#x441;&#x440;&#x435;&#x434;&#x441;&#x442;&#x432;, &#x43E;&#x431;&#x43E;&#x440;&#x43E;&#x442; &#x43A;&#x43E;&#x442;&#x43E;&#x440;&#x44B;&#x445; &#x437;&#x430;&#x43F;&#x440;&#x435;&#x449;&#x435;&#x43D;, &#x441;&#x435;&#x43C;&#x435;&#x43D;&#x430; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B; &#x43D;&#x435; &#x44F;&#x432;&#x43B;&#x44F;&#x44E;&#x442;&#x441;&#x44F; &#x43D;&#x430;&#x440;&#x43A;&#x43E;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438;&#x43C; &#x441;&#x440;&#x435;&#x434;&#x441;&#x442;&#x432;&#x43E;&#x43C;. &#x42D;&#x442;&#x43E; &#x43E;&#x437;&#x43D;&#x430;&#x447;&#x430;&#x435;&#x442;, &#x447;&#x442;&#x43E; &#x43F;&#x440;&#x43E;&#x434;&#x430;&#x436;&#x430;, &#x440;&#x43E;&#x432;&#x43D;&#x43E; &#x43A;&#x430;&#x43A; &#x438; &#x43F;&#x440;&#x438;&#x43E;&#x431;&#x440;&#x435;&#x442;&#x435;&#x43D;&#x438;&#x435;, &#x43B;&#x438;&#x431;&#x43E; &#x445;&#x440;&#x430;&#x43D;&#x435;&#x43D;&#x438;&#x435; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B; &#x43D;&#x430; &#x442;&#x435;&#x440;&#x440;&#x438;&#x442;&#x43E;&#x440;&#x438;&#x438; &#x420;&#x424; - &#x43D;&#x435; &#x43D;&#x430;&#x440;&#x443;&#x448;&#x430;&#x435;&#x442; &#x43D;&#x438;&#x43A;&#x430;&#x43A;&#x430;&#x43A;&#x438;&#x435; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x44B;, &#x430; &#x441;&#x43B;&#x435;&#x434;&#x43E;&#x432;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x43E;, &#x43D;&#x435; &#x432;&#x43B;&#x435;&#x447;&#x435;&#x442; &#x437;&#x430; &#x441;&#x43E;&#x431;&#x43E;&#x439; &#x43D;&#x438;&#x43A;&#x430;&#x43A;&#x43E;&#x439; &#x43E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x438;.\n\n&#x41D;&#x43E;, &#x442;&#x435;&#x43C; &#x43D;&#x435; &#x43C;&#x435;&#x43D;&#x435;&#x435;, &#x43A;&#x430;&#x436;&#x434;&#x44B;&#x439; &#x434;&#x43E;&#x43B;&#x436;&#x435;&#x43D; &#x434;&#x43B;&#x44F; &#x441;&#x435;&#x431;&#x44F; &#x443;&#x44F;&#x441;&#x43D;&#x438;&#x442;&#x44C;, &#x447;&#x442;&#x43E; &#x43A;&#x443;&#x43B;&#x44C;&#x442;&#x438;&#x432;&#x430;&#x446;&#x438;&#x44F; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B; &#x43D;&#x430; &#x442;&#x435;&#x440;&#x440;&#x438;&#x442;&#x43E;&#x440;&#x438;&#x438; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438;, &#x432; &#x43D;&#x435;&#x437;&#x430;&#x432;&#x438;&#x441;&#x438;&#x43C;&#x43E;&#x441;&#x442;&#x438; &#x43E;&#x442; &#x442;&#x43E;&#x433;&#x43E;, &#x434;&#x43B;&#x44F; &#x43A;&#x430;&#x43A;&#x438;&#x445; &#x446;&#x435;&#x43B;&#x435;&#x439; &#x432;&#x44B;&#x440;&#x430;&#x449;&#x438;&#x432;&#x430;&#x435;&#x442;&#x441;&#x44F; &#x440;&#x430;&#x441;&#x442;&#x435;&#x43D;&#x438;&#x435;, &#x437;&#x430;&#x43F;&#x440;&#x435;&#x449;&#x435;&#x43D;&#x43E; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x43E;&#x43C; &#x438;, &#x43A;&#x430;&#x43A; &#x441;&#x43B;&#x435;&#x434;&#x441;&#x442;&#x432;&#x438;&#x435;, &#x432;&#x43B;&#x435;&#x447;&#x435;&#x442; &#x437;&#x430; &#x441;&#x43E;&#x431;&#x43E;&#x439;, &#x43A;&#x430;&#x43A; &#x430;&#x434;&#x43C;&#x438;&#x43D;&#x438;&#x441;&#x442;&#x440;&#x430;&#x442;&#x438;&#x432;&#x43D;&#x443;&#x44E;, &#x442;&#x430;&#x43A; &#x438; &#x443;&#x433;&#x43E;&#x43B;&#x43E;&#x432;&#x43D;&#x443;&#x44E; &#x43E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x44C;.\n\n&#x41F;&#x440;&#x438;&#x43E;&#x431;&#x440;&#x435;&#x442;&#x430;&#x44F; &#x441;&#x435;&#x43C;&#x435;&#x43D;&#x430; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B;, &#x432;&#x44B; &#x434;&#x43E;&#x43B;&#x436;&#x43D;&#x44B; &#x437;&#x430;&#x43F;&#x43E;&#x43C;&#x43D;&#x438;&#x442;&#x44C;, &#x447;&#x442;&#x43E; &#x43B;&#x435;&#x433;&#x430;&#x43B;&#x44C;&#x43D;&#x43E;&#x435; &#x438;&#x441;&#x43F;&#x43E;&#x43B;&#x44C;&#x437;&#x43E;&#x432;&#x430;&#x43D;&#x438;&#x435; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x43A;&#x430;&#x43D;&#x43D;&#x430;&#x431;&#x438;&#x441;&#x430; (&#x43E;&#x440;&#x438;&#x433;&#x438;&#x43D;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x439; &#x43F;&#x43E;&#x434;&#x430;&#x440;&#x43E;&#x43A; &#x434;&#x440;&#x443;&#x433;&#x443;, &#x43F;&#x440;&#x438;&#x433;&#x43E;&#x442;&#x43E;&#x432;&#x43B;&#x435;&#x43D;&#x438;&#x435; &#x43B;&#x435;&#x447;&#x435;&#x431;&#x43D;&#x44B;&#x445; &#x43C;&#x430;&#x437;&#x435;&#x439;, &#x43A;&#x43E;&#x441;&#x43C;&#x435;&#x442;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438;&#x445; &#x43C;&#x430;&#x441;&#x43E;&#x43A; &#x438; &#x442;.&#x434;.) &#x43D;&#x435; &#x44F;&#x432;&#x43B;&#x44F;&#x435;&#x442;&#x441;&#x44F; &#x43D;&#x430;&#x440;&#x443;&#x448;&#x435;&#x43D;&#x438;&#x435;&#x43C; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x430;. &#x410; &#x432;&#x43E;&#x442; &#x438;&#x441;&#x43F;&#x43E;&#x43B;&#x44C;&#x437;&#x43E;&#x432;&#x430;&#x43D;&#x438;&#x435; &#x441;&#x435;&#x43C;&#x44F;&#x43D; &#x432; &#x43A;&#x430;&#x447;&#x435;&#x441;&#x442;&#x432;&#x435; &#x441;&#x43F;&#x435;&#x446;&#x438;&#x444;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x43E;&#x433;&#x43E; &#x43F;&#x43E;&#x441;&#x435;&#x432;&#x43D;&#x43E;&#x433;&#x43E; &#x43C;&#x430;&#x442;&#x435;&#x440;&#x438;&#x430;&#x43B;&#x430; - &#x44F;&#x432;&#x43B;&#x44F;&#x435;&#x442;&#x441;&#x44F; &#x43D;&#x430;&#x440;&#x443;&#x448;&#x435;&#x43D;&#x438;&#x435;&#x43C; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x430; &#x420;&#x424;. &#x412; &#x434;&#x430;&#x43D;&#x43D;&#x43E;&#x43C; &#x441;&#x43B;&#x443;&#x447;&#x430;&#x435; &#x432;&#x430;&#x448;&#x438; &#x434;&#x435;&#x439;&#x441;&#x442;&#x432;&#x438;&#x44F; &#x43F;&#x43E;&#x43F;&#x430;&#x434;&#x430;&#x44E;&#x442; &#x43F;&#x43E;&#x434; &#x441;&#x442;. 231 &#x423;&#x41A; &#x420;&#x424;\n\n&#x41E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x44C; &#x437;&#x430; &#x43D;&#x430;&#x440;&#x443;&#x448;&#x435;&#x43D;&#x438;&#x435; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x430;:\n\n19 &#x43C;&#x430;&#x44F; 2010 &#x433;&#x43E;&#x434;&#x430; &#x432;&#x441;&#x442;&#x443;&#x43F;&#x438;&#x43B; &#x432; &#x441;&#x438;&#x43B;&#x443; &#x444;&#x435;&#x434;&#x435;&#x440;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x439; &#x437;&#x430;&#x43A;&#x43E;&#x43D; - 87-&#x424;&#x417;. &#x421;&#x430;&#x43C;&#x43E;&#x435; &#x432;&#x430;&#x436;&#x43D;&#x43E;&#x435; &#x432; &#x44D;&#x442;&#x43E;&#x43C; &#x434;&#x43E;&#x43A;&#x443;&#x43C;&#x435;&#x43D;&#x442;&#x435; - &#x43E;&#x442;&#x43C;&#x435;&#x43D;&#x430; &#x443;&#x433;&#x43E;&#x43B;&#x43E;&#x432;&#x43D;&#x43E;&#x439; &#x43E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x438; &#x437;&#x430; &#x43A;&#x443;&#x43B;&#x44C;&#x442;&#x438;&#x432;&#x430;&#x446;&#x438;&#x44E; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B; &#x432; &#x43D;&#x435; &#x43A;&#x440;&#x443;&#x43F;&#x43D;&#x44B;&#x445; &#x440;&#x430;&#x437;&#x43C;&#x435;&#x440;&#x430;&#x445;, &#x430; &#x442;&#x430;&#x43A; &#x436;&#x435; &#x437;&#x430;&#x43C;&#x435;&#x43D;&#x430; &#x432; &#x44D;&#x442;&#x43E;&#x43C; &#x441;&#x43B;&#x443;&#x447;&#x430;&#x435; &#x443;&#x433;&#x43E;&#x43B;&#x43E;&#x432;&#x43D;&#x43E;&#x439; &#x43E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x438; &#x43D;&#x430; &#x430;&#x434;&#x43C;&#x438;&#x43D;&#x438;&#x441;&#x442;&#x440;&#x430;&#x442;&#x438;&#x432;&#x43D;&#x443;&#x44E; (&#x448;&#x442;&#x440;&#x430;&#x444;). &#x41F;&#x43E; &#x43D;&#x43E;&#x432;&#x43E;&#x43C;&#x443; &#x437;&#x430;&#x43A;&#x43E;&#x43D;&#x443; &#x437;&#x430; &#x432;&#x44B;&#x440;&#x430;&#x449;&#x438;&#x432;&#x430;&#x43D;&#x438;&#x435; &#x43C;&#x435;&#x43D;&#x435;&#x435; 20 &#x43A;&#x443;&#x441;&#x442;&#x43E;&#x432; &#x43C;&#x430;&#x440;&#x438;&#x445;&#x443;&#x430;&#x43D;&#x44B; &#x43D;&#x430;&#x43A;&#x43B;&#x430;&#x434;&#x44B;&#x432;&#x430;&#x435;&#x442;&#x441;&#x44F; &#x448;&#x442;&#x440;&#x430;&#x444; - &#x43E;&#x442; 1500 &#x434;&#x43E; 4000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;, &#x438;&#x43B;&#x438; &#x436;&#x435; &#x430;&#x434;&#x43C;&#x438;&#x43D;&#x438;&#x441;&#x442;&#x440;&#x430;&#x442;&#x438;&#x432;&#x43D;&#x44B;&#x439; &#x430;&#x440;&#x435;&#x441;&#x442; &#x43D;&#x430; &#x441;&#x440;&#x43E;&#x43A; &#x434;&#x43E; 15 &#x441;&#x443;&#x442;&#x43E;&#x43A;; &#x43D;&#x430; &#x44E;&#x440;&#x438;&#x434;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438;&#x445; &#x43B;&#x438;&#x446; - &#x43E;&#x442; 100 000 &#x434;&#x43E; 300 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;.\n\n&#x412; &#x442;&#x43E;&#x43C; &#x436;&#x435; &#x434;&#x43E;&#x43A;&#x443;&#x43C;&#x435;&#x43D;&#x442;&#x435; &#x43C;&#x43E;&#x436;&#x43D;&#x43E; &#x443;&#x432;&#x438;&#x434;&#x435;&#x442;&#x44C; &#x438; &#x441;&#x43D;&#x438;&#x436;&#x435;&#x43D;&#x438;&#x435; &#x43D;&#x430;&#x43A;&#x430;&#x437;&#x430;&#x43D;&#x438;&#x44F; &#x437;&#x430; &#x43A;&#x443;&#x43B;&#x44C;&#x442;&#x438;&#x432;&#x430;&#x446;&#x438;&#x44E; &#x432; &#x43A;&#x440;&#x443;&#x43F;&#x43D;&#x43E;&#x43C; &#x440;&#x430;&#x437;&#x43C;&#x435;&#x440;&#x435;. &#x420;&#x430;&#x43D;&#x435;&#x435; &#x437;&#x430; &#x44D;&#x442;&#x43E; &#x434;&#x435;&#x44F;&#x43D;&#x438;&#x435; &#x43D;&#x430;&#x43A;&#x430;&#x437;&#x44B;&#x432;&#x430;&#x43B;&#x438; &#x43B;&#x438;&#x448;&#x435;&#x43D;&#x438;&#x435;&#x43C; &#x441;&#x432;&#x43E;&#x431;&#x43E;&#x434;&#x44B; &#x43D;&#x430; &#x441;&#x440;&#x43E;&#x43A; &#x43E;&#x442; 3 &#x434;&#x43E; 8 &#x43B;&#x435;&#x442;, &#x441;&#x435;&#x439;&#x447;&#x430;&#x441; &#x441;&#x442;&#x430;&#x43B;&#x43E; - &#x434;&#x43E; 2-&#x445; &#x43B;&#x435;&#x442;.\n\n&#x41A;&#x440;&#x43E;&#x43C;&#x435; &#x442;&#x43E;&#x433;&#x43E;, &#x443;&#x433;&#x43E;&#x43B;&#x43E;&#x432;&#x43D;&#x430;&#x44F; &#x43E;&#x442;&#x432;&#x435;&#x442;&#x441;&#x442;&#x432;&#x435;&#x43D;&#x43D;&#x43E;&#x441;&#x442;&#x44C; &#x441;&#x43E;&#x445;&#x440;&#x430;&#x43D;&#x44F;&#x435;&#x442;&#x441;&#x44F; &#x438;&#x441;&#x43A;&#x43B;&#x44E;&#x447;&#x438;&#x442;&#x435;&#x43B;&#x44C;&#x43D;&#x43E; &#x432; &#x442;&#x43E;&#x43C; &#x441;&#x43B;&#x443;&#x447;&#x430;&#x435;, &#x435;&#x441;&#x43B;&#x438; &#x431;&#x443;&#x434;&#x435;&#x442; &#x434;&#x43E;&#x43A;&#x430;&#x437;&#x430;&#x43D;&#x43E;, &#x447;&#x442;&#x43E; &#x432;&#x44B;&#x440;&#x430;&#x449;&#x438;&#x432;&#x430;&#x43D;&#x438;&#x435; &#x43E;&#x441;&#x443;&#x449;&#x435;&#x441;&#x442;&#x432;&#x43B;&#x44F;&#x43B;&#x43E;&#x441;&#x44C; &#x43D;&#x435; &#x434;&#x43B;&#x44F; &#x43B;&#x438;&#x447;&#x43D;&#x44B;&#x445; &#x43D;&#x443;&#x436;&#x434;, &#x430; &#x441; &#x446;&#x435;&#x43B;&#x44C;&#x44E; &#x441;&#x431;&#x44B;&#x442;&#x430;.', 'https://t.me/Khan_service_channel', 2, '2', 1, 0, 0, 4, 0, 0),
(12, 'Поддержка', '<b>Demo bot</b>\n\nzecode', '', 0, '2', 1, 0, 0, 2, 0, 0),
(43, 'Корзина', '<b>&#x41A;&#x43E;&#x440;&#x437;&#x438;&#x43D;&#x430;</b>', '', 1, '2', 0, 0, 0, 2, 0, 0),
(63, '+ халапеньо', '', '', 0, '59', 0, 0, 0, 0, 0, 0),
(439, 'Разное', '&#x412;&#x44B;&#x431;&#x435;&#x440;&#x435;&#x442;&#x435; &#x441;&#x438;&#x434;&#x431;&#x430;&#x43D;&#x43A;, &#x439;&#x43E;&#x443;!', '', 0, '3', 0, 0, 0, 1, 1, 0),
(680, 'Способы доставки', '&#x414;&#x41E;&#x421;&#x422;&#x410;&#x412;&#x41A;&#x410; &#x422;&#x41E;&#x412;&#x410;&#x420;&#x410;\n&#x41E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43A;&#x430; &#x43E;&#x441;&#x443;&#x449;&#x435;&#x441;&#x442;&#x432;&#x43B;&#x44F;&#x435;&#x442;&#x441;&#x44F; &#x432; &#x442;&#x435;&#x447;&#x435;&#x43D;&#x438;&#x435; 1-3 &#x434;&#x43D;&#x435;&#x439; &#x441; &#x43C;&#x43E;&#x43C;&#x435;&#x43D;&#x442;&#x430; &#x43F;&#x43E;&#x434;&#x442;&#x432;&#x435;&#x440;&#x436;&#x434;&#x435;&#x43D;&#x438;&#x44F; &#x438;&#x43B;&#x438; &#x43E;&#x43F;&#x43B;&#x430;&#x442;&#x44B; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x430; &#x432; &#x440;&#x430;&#x431;&#x43E;&#x447;&#x438;&#x435; &#x434;&#x43D;&#x438; (&#x41F;&#x41D;-&#x41F;&#x422;). &#x421;&#x442;&#x43E;&#x438;&#x43C;&#x43E;&#x441;&#x442;&#x44C; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x438; &#x440;&#x430;&#x441;&#x441;&#x447;&#x438;&#x442;&#x44B;&#x432;&#x430;&#x435;&#x442;&#x441;&#x44F; &#x432; &#x440;&#x435;&#x436;&#x438;&#x43C;&#x435; &#x440;&#x435;&#x430;&#x43B;&#x44C;&#x43D;&#x43E;&#x433;&#x43E; &#x432;&#x440;&#x435;&#x43C;&#x435;&#x43D;&#x438; &#x438;&#x43B;&#x438; &#x44F;&#x432;&#x43B;&#x44F;&#x435;&#x442;&#x441;&#x44F; &#x444;&#x438;&#x43A;&#x441;&#x438;&#x440;&#x43E;&#x432;&#x430;&#x43D;&#x43D;&#x43E;&#x439; &#x434;&#x43B;&#x44F; &#x43E;&#x43F;&#x440;&#x435;&#x434;&#x435;&#x43B;&#x435;&#x43D;&#x43D;&#x44B;&#x445; &#x441;&#x43B;&#x443;&#x436;&#x431; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x438;.\n\n&#x41C;&#x44B; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43B;&#x44F;&#x435;&#x43C; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x44B; &#x432; &#x43B;&#x44E;&#x431;&#x43E;&#x439; &#x43D;&#x430;&#x441;&#x435;&#x43B;&#x435;&#x43D;&#x43D;&#x44B;&#x439; &#x43F;&#x443;&#x43D;&#x43A;&#x442; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438;.\n&#x410; &#x442;&#x430;&#x43A;-&#x436;&#x435; &#x432; &#x41A;&#x430;&#x437;&#x430;&#x445;&#x441;&#x442;&#x430;&#x43D; &#x438; &#x411;&#x435;&#x43B;&#x430;&#x440;&#x443;&#x441;&#x44C; &#x43F;&#x440;&#x438; &#x43F;&#x43E;&#x43C;&#x43E;&#x449;&#x438; &#x441;&#x43B;&#x443;&#x436;&#x431;&#x44B; EMS.\n\n \n&#x421;&#x41F;&#x41E;&#x421;&#x41E;&#x411;&#x42B; &#x414;&#x41E;&#x421;&#x422;&#x410;&#x412;&#x41A;&#x418;\n&#x41F;&#x43E;&#x447;&#x442;&#x430; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438; - &#x43E;&#x442; 200 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 3 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\n&#x41F;&#x43E;&#x447;&#x442;&#x430; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438; (&#x41D;&#x430;&#x43B;&#x43E;&#x436;&#x435;&#x43D;&#x43D;&#x44B;&#x439; &#x43F;&#x43B;&#x430;&#x442;&#x435;&#x436;) - &#x43E;&#x442; 300 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; + 5% &#x43E;&#x442; &#x441;&#x443;&#x43C;&#x43C;&#x44B; &#x442;&#x43E;&#x432;&#x430;&#x440;&#x43E;&#x432; (&#x421;&#x442;&#x440;&#x430;&#x445;&#x43E;&#x432;&#x43E;&#x439; &#x441;&#x431;&#x43E;&#x440;)\n\n&#x422;&#x440;&#x430;&#x43D;&#x441;&#x43F;&#x43E;&#x440;&#x442;&#x43D;&#x430;&#x44F; &#x43A;&#x43E;&#x43C;&#x43F;&#x430;&#x43D;&#x438;&#x44F; &#x41F;&#x42D;&#x41A; - &#x43E;&#x442; 450 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 5 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\nEMS &#x41F;&#x43E;&#x447;&#x442;&#x430; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438; - &#x43E;&#x442; 500 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 10 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\nEMS &#x43F;&#x43E; &#x41F;&#x435;&#x440;&#x43C;&#x441;&#x43A;&#x43E;&#x43C;&#x443; &#x43A;&#x440;&#x430;&#x44E; - 400 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 10 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\nEMS &#x41A;&#x430;&#x437;&#x430;&#x445;&#x441;&#x442;&#x430;&#x43D; &#x438; &#x411;&#x435;&#x43B;&#x430;&#x440;&#x443;&#x441;&#x44C; - &#x43E;&#x442; 1000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;\n\n&#x41A;&#x443;&#x440;&#x44C;&#x435;&#x440;&#x441;&#x43A;&#x430;&#x44F; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x430; &#x43F;&#x43E; &#x41F;&#x435;&#x440;&#x43C;&#x438; - 500 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x446;&#x435;&#x43D;&#x442;&#x440;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x435; &#x440;&#x430;&#x439;&#x43E;&#x43D;&#x44B; &#x433;&#x43E;&#x440;&#x43E;&#x434;&#x430;)\n\n&#x422;&#x440;&#x430;&#x43D;&#x441;&#x43F;&#x43E;&#x440;&#x442;&#x43D;&#x430;&#x44F; &#x43A;&#x43E;&#x43C;&#x43F;&#x430;&#x43D;&#x438;&#x44F; &#x421;&#x414;&#x42D;&#x41A; (&#x434;&#x43E; &#x442;&#x435;&#x440;&#x43C;&#x438;&#x43D;&#x430;&#x43B;&#x430;) - &#x43E;&#x442; 200 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 8 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\n&#x422;&#x440;&#x430;&#x43D;&#x441;&#x43F;&#x43E;&#x440;&#x442;&#x43D;&#x430;&#x44F; &#x43A;&#x43E;&#x43C;&#x43F;&#x430;&#x43D;&#x438;&#x44F; &#x421;&#x414;&#x42D;&#x41A; (&#x434;&#x43E; &#x434;&#x432;&#x435;&#x440;&#x438;)- &#x43E;&#x442; 350 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439; (&#x431;&#x435;&#x441;&#x43F;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x43F;&#x440;&#x438; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x435; &#x43D;&#x430; &#x441;&#x443;&#x43C;&#x43C;&#x443; &#x43E;&#x442; 10 000 &#x440;&#x443;&#x431;&#x43B;&#x435;&#x439;)\n\n \n\n&#x412;&#x441;&#x435; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x44B; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43B;&#x44F;&#x44E;&#x442;&#x441;&#x44F; &#x432; &#x443;&#x43F;&#x430;&#x43A;&#x43E;&#x432;&#x43A;&#x435; &#x438;&#x441;&#x43A;&#x43B;&#x44E;&#x447;&#x430;&#x44E;&#x449;&#x435;&#x439; &#x43F;&#x43E;&#x432;&#x440;&#x435;&#x436;&#x434;&#x435;&#x43D;&#x438;&#x435; &#x433;&#x440;&#x443;&#x437;&#x430; &#x438;&#x43B;&#x438; &#x43D;&#x435;&#x437;&#x430;&#x43C;&#x435;&#x442;&#x43D;&#x43E;&#x435; &#x432;&#x441;&#x43A;&#x440;&#x44B;&#x442;&#x438;&#x435;.\n\n&#x421;&#x442;&#x43E;&#x438;&#x43C;&#x43E;&#x441;&#x442;&#x44C; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x438; &#x43C;&#x43E;&#x436;&#x435;&#x442; &#x431;&#x44B;&#x442;&#x44C; &#x438;&#x437;&#x43C;&#x435;&#x43D;&#x435;&#x43D;&#x430; &#x432; &#x441;&#x43B;&#x43E;&#x436;&#x43D;&#x44B;&#x445; &#x441;&#x43B;&#x443;&#x447;&#x430;&#x44F;&#x445;.\n \n&#x414;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x430; &#x441;&#x430;&#x43C;&#x44B;&#x43C;&#x438; &#x43F;&#x43E;&#x43F;&#x443;&#x43B;&#x44F;&#x440;&#x43D;&#x44B;&#x43C;&#x438; &#x438; &#x43F;&#x440;&#x43E;&#x432;&#x435;&#x440;&#x435;&#x43D;&#x43D;&#x44B;&#x43C;&#x438; &#x43A;&#x43E;&#x43C;&#x43F;&#x430;&#x43D;&#x438;&#x44F;&#x43C;&#x438;:\n\"&#x41F;&#x43E;&#x447;&#x442;&#x430; &#x420;&#x43E;&#x441;&#x441;&#x438;&#x438;\", \"EMS\", \"SDEK\", \"&#x41F;&#x42D;&#x41A;\"\n&#x423;&#x43A;&#x430;&#x436;&#x438;&#x442;&#x435; &#x443;&#x434;&#x43E;&#x431;&#x43D;&#x44B;&#x439; &#x434;&#x43B;&#x44F; &#x432;&#x430;&#x441; &#x441;&#x43F;&#x43E;&#x441;&#x43E;&#x431; &#x434;&#x43E;&#x441;&#x442;&#x430;&#x432;&#x43A;&#x438; &#x43F;&#x440;&#x438; &#x43E;&#x444;&#x43E;&#x440;&#x43C;&#x43B;&#x435;&#x43D;&#x438;&#x438; &#x437;&#x430;&#x44F;&#x432;&#x43A;&#x438;!', '', 0, '2', 1, 0, 0, 3, 0, 0),
(687, 'Ассортимент', '<b>&#x412;&#x44B;&#x431;&#x435;&#x440;&#x438;&#x442;&#x435; &#x43D;&#x443;&#x436;&#x43D;&#x43E;&#x435;:</b>', '', 0, '2', 0, 0, 0, 1, 0, 0),
(688, 'Разовые ставки', '', '', 2, '687', 1, 0, 0, 1, 0, 0),
(689, 'Подписка на vip канал', '', '', 1, '687', 1, 0, 0, 2, 0, 0),
(690, 'Каталог аккаунтов', '', '', 3, '687', 0, 0, 0, 3, 0, 0),
(691, 'Vk.com', '', '', 4, '687', 0, 0, 0, 2, 0, 0),
(692, 'Товары', '', '', 0, '2', 1, 0, 0, 2, 0, 0),
(694, 'Пригласить друга', '#&#x41F;&#x430;&#x440;&#x442;&#x43D;&#x435;&#x440;&#x441;&#x43A;&#x430;&#x44F;&#x41F;&#x440;&#x43E;&#x433;&#x440;&#x430;&#x43C;&#x43C;&#x430;\n\n&#x2757;&#xFE0F; <b>&#x412;&#x410;&#x420;&#x418;&#x410;&#x41D;&#x422; &#x41E;&#x422;&#x41E;&#x411;&#x420;&#x410;&#x416;&#x415;&#x41D;&#x418;&#x42F; 1:</b> &#x2757;&#xFE0F;\n\n&#x417;&#x430; &#x43A;&#x430;&#x436;&#x434;&#x43E;&#x433;&#x43E; &#x43F;&#x440;&#x438;&#x433;&#x43B;&#x430;&#x448;&#x435;&#x43D;&#x43D;&#x43E;&#x433;&#x43E; &#x43F;&#x43E;&#x43B;&#x44C;&#x437;&#x43E;&#x432;&#x430;&#x442;&#x435;&#x43B;&#x44F; &#x432;&#x44B; <b> &#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x435;&#x442;&#x435; {ref_bonus}% &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n&#x417;&#x430; &#x43A;&#x430;&#x436;&#x434;&#x43E;&#x433;&#x43E; &#x43F;&#x440;&#x438;&#x433;&#x43B;&#x430;&#x448;&#x435;&#x43D;&#x43D;&#x43E;&#x433;&#x43E; &#x432;&#x430;&#x448;&#x438;&#x43C; &#x434;&#x440;&#x443;&#x433;&#x43E;&#x43C; &#x432;&#x44B; <b>&#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x435;&#x442;&#x435; {ref_at_friend}% &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n\n&#x417;&#x430; <b>5</b> &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; - <b>{ref_friend_5}%   &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n&#x417;&#x430; <b>15</b> &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; - <b>{ref_friend_15}%  &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n&#x417;&#x430; <b>30</b> &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; - <b>{ref_friend_30}%  &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n&#x417;&#x430; <b>50</b> &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; - <b>{ref_friend_50}%  &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n&#x417;&#x430; <b>100</b> &#x434;&#x440;&#x443;&#x437;&#x435;&#x439; - <b>{ref_friend_100}% &#x441;&#x43A;&#x438;&#x434;&#x43A;&#x438;!</b>\n\n<b>&#x412;&#x430;&#x448;&#x430; &#x440;&#x435;&#x444;&#x435;&#x440;&#x430;&#x43B;&#x44C;&#x43D;&#x430;&#x44F; &#x441;&#x441;&#x44B;&#x43B;&#x43A;&#x430;:</b>\nhttps://t.me/{bot_username}?start={id_chat}\n\n<b>&#x412;&#x44B; &#x43F;&#x440;&#x438;&#x433;&#x43B;&#x430;&#x441;&#x438;&#x43B;&#x438;:</b> {main_ref} &#x447;&#x435;&#x43B;&#x43E;&#x432;&#x435;&#x43A;.\n\n&#x2757;&#xFE0F; <b>&#x412;&#x410;&#x420;&#x418;&#x410;&#x41D;&#x422; &#x41E;&#x422;&#x41E;&#x411;&#x420;&#x410;&#x416;&#x415;&#x41D;&#x418;&#x42F; 2:</b> &#x2757;&#xFE0F;\n\n&#x2755; <b>&#x412;&#x44B; &#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x435;&#x442;&#x435;</b> &#x1F4B5;  {gain_proc} <b>&#x441; &#x43E;&#x431;&#x449;&#x435;&#x439; &#x441;&#x443;&#x43C;&#x43C;&#x44B; &#x437;&#x430;&#x43A;&#x430;&#x437;&#x430;.</b>\n<b>&#x41E;&#x442; &#x440;&#x435;&#x444;&#x435;&#x440;&#x430;&#x43B;&#x43E;&#x432;, &#x43A;&#x43E;&#x442;&#x43E;&#x440;&#x44B;&#x445; &#x412;&#x44B; &#x43F;&#x440;&#x438;&#x433;&#x43B;&#x430;&#x441;&#x438;&#x43B;&#x438; &#x43F;&#x43E; &#x441;&#x432;&#x43E;&#x435;&#x439; &#x440;&#x435;&#x444;.&#x441;&#x441;&#x44B;&#x43B;&#x43A;&#x438;.</b>\n\n&#x1F449; &#x417;&#x430;&#x440;&#x430;&#x431;&#x43E;&#x442;&#x430;&#x43D;&#x43D;&#x44B;&#x435; &#x441;&#x440;&#x435;&#x434;&#x441;&#x442;&#x432;&#x430; &#x432;&#x44B; &#x43C;&#x43E;&#x436;&#x435;&#x442;&#x435; &#x432;&#x44B;&#x432;&#x435;&#x441;&#x442;&#x438; &#x43D;&#x430; <b>Qiwi</b> .\n\n<b>&#x41A;&#x43E;&#x43C;&#x430;&#x43D;&#x434;&#x430; &#x434;&#x43B;&#x44F; &#x432;&#x44B;&#x432;&#x43E;&#x434;&#x430; - </b> &#x1F446; /qiwi_send .\n\n<b>&#x412;&#x430;&#x448;&#x430; &#x440;&#x435;&#x444;&#x435;&#x440;&#x430;&#x43B;&#x44C;&#x43D;&#x430;&#x44F; &#x441;&#x441;&#x44B;&#x43B;&#x43A;&#x430; &#x434;&#x43B;&#x44F; &#x437;&#x430;&#x440;&#x430;&#x431;&#x43E;&#x442;&#x43A;&#x430;</b>\nhttps://t.me/{bot_username}?start={id_chat}\n\n<b>&#x41F;&#x43E;&#x43A;&#x443;&#x43F;&#x430;&#x442;&#x435;&#x43B;&#x435;&#x439; &#x43E;&#x442; &#x412;&#x430;&#x441;:</b> {client_ref} &#x447;&#x435;&#x43B;&#x43E;&#x432;&#x435;&#x43A;.', '', 0, '2', 0, 0, 0, 2, 0, 0),
(695, 'Вакансии', '&#x41E;&#x442;&#x43A;&#x440;&#x44B;&#x442; &#x43D;&#x430;&#x431;&#x43E;&#x440;', '', 0, '692', 0, 0, 0, 0, 0, 0),
(697, 'Вакансии', '&#x41E;&#x442;&#x43A;&#x440;&#x44B;&#x442; &#x43D;&#x430;&#x431;&#x43E;&#x440;', '', 0, '696', 0, 0, 0, 5, 0, 0),
(699, 'Пример 1', '', '', 0, '690', 0, 0, 0, 1, 0, 0),
(700, 'Пример 2', '', '', 1, '690', 0, 0, 0, 2, 0, 0),
(701, 'Подраздел 1', '', '', 0, '700', 0, 0, 0, 1, 0, 0);

CREATE TABLE `unicode` (
  `id` int(11) NOT NULL,
  `u` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text NOT NULL,
  `prev` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `unicode` (`id`, `u`, `name`, `prev`) VALUES
(2, '8J+YgQ==', '', '1f601.png'),
(3, '8J+Ygg==', '', '1f602.png'),
(4, '8J+Ygw==', '', '1f603.png'),
(5, '8J+YhA==', '', '1f604.png'),
(6, '8J+YhQ==', '', '1f605.png'),
(7, '8J+Yhg==', '', '1f606.png'),
(8, '8J+YiQ==', '', '1f609.png'),
(9, '8J+Yig==', '', '1f60a.png'),
(10, '8J+Yiw==', '', '1f60b.png'),
(11, '8J+YjA==', '', '1f60c.png'),
(12, '8J+YjQ==', '', '1f60d.png'),
(13, '8J+Yjw==', '', '1f60f.png'),
(14, '8J+Ykg==', '', '1f612.png'),
(15, '8J+Ykw==', '', '1f613.png'),
(16, '8J+YlA==', '', '1f614.png'),
(17, '8J+Ylg==', '', '1f616.png'),
(18, '8J+YmA==', '', '1f618.png'),
(19, '8J+Ymg==', '', '1f61a.png'),
(20, '8J+YnA==', '', '1f61c.png'),
(21, '8J+YnQ==', '', '1f61d.png'),
(22, '8J+Yng==', '', '1f61e.png'),
(23, '8J+YoA==', '', '1f620.png'),
(24, '8J+YoQ==', '', '1f621.png'),
(25, '8J+Yog==', '', '1f622.png'),
(26, '8J+Yow==', '', '1f623.png'),
(27, '8J+YpA==', '', '1f624.png'),
(28, '8J+YpQ==', '', '1f625.png'),
(29, '8J+YqA==', '', '1f628.png'),
(30, '8J+YqQ==', '', '1f629.png'),
(31, '8J+Yqg==', '', '1f62a.png'),
(32, '8J+Yqw==', '', '1f62b.png'),
(33, '8J+YrQ==', '', '1f62d.png'),
(34, '8J+YsA==', '', '1f630.png'),
(35, '8J+YsQ==', '', '1f631.png'),
(36, '8J+Ysg==', '', '1f632.png'),
(37, '8J+Ysw==', '', '1f633.png'),
(38, '8J+YtQ==', '', '1f635.png'),
(39, '8J+Ytw==', '', '1f637.png'),
(40, '8J+YuA==', '', '1f638.png'),
(41, '8J+YuQ==', '', '1f639.png'),
(42, '8J+Yug==', '', '1f63a.png'),
(43, '8J+Yuw==', '', '1f63b.png'),
(44, '8J+YvA==', '', '1f63c.png'),
(45, '8J+YvQ==', '', '1f63d.png'),
(46, '8J+Yvg==', '', '1f63e.png'),
(47, '8J+Yvw==', '', '1f63f.png'),
(48, '8J+ZgA==', '', '1f640.png'),
(49, '8J+ZhQ==', '', '1f645.png'),
(50, '8J+Zhg==', '', '1f646.png'),
(51, '8J+Zhw==', '', '1f647.png'),
(52, '8J+ZiA==', '', '1f648.png'),
(53, '8J+ZiQ==', '', '1f649.png'),
(54, '8J+Zig==', '', '1f64a.png'),
(55, '8J+Ziw==', '', '1f64b.png'),
(56, '8J+ZjA==', '', '1f64c.png'),
(57, '8J+ZjQ==', '', '1f64d.png'),
(58, '8J+Zjg==', '', '1f64e.png'),
(59, '8J+Zjw==', '', '1f64f.png'),
(60, '4pyC', '', '2702.png'),
(61, '4pyF', '', '2705.png'),
(62, '4pyI', '', '2708.png'),
(63, '4pyJ', '', '2709.png'),
(64, '4pyK', '', '270a.png'),
(65, '4pyL', '', '270b.png'),
(66, '4pyM', '', '270c.png'),
(67, '4pyP', '', '270f.png'),
(68, '4pyS', '', '2712.png'),
(69, '4pyU', '', '2714.png'),
(70, '4pyW', '', '2716.png'),
(71, '4pyo', '', '2728.png'),
(72, '4pyz', '', '2733.png'),
(73, '4py0', '', '2734.png'),
(74, '4p2E', '', '2744.png'),
(75, '4p2H', '', '2747.png'),
(76, '4p2M', '', '274c.png'),
(77, '4p2O', '', '274e.png'),
(78, '4p2T', '', '2753.png'),
(79, '4p2U', '', '2754.png'),
(80, '4p2V', '', '2755.png'),
(81, '4p2X', '', '2757.png'),
(82, '4p2k', '', '2764.png'),
(83, '4p6V', '', '2795.png'),
(84, '4p6W', '', '2796.png'),
(85, '4p6X', '', '2797.png'),
(86, '4p6h', '', '27a1.png'),
(87, '4p6w', '', '27b0.png'),
(88, '8J+agA==', '', '1f680.png'),
(89, '8J+agw==', '', '1f683.png'),
(90, '8J+ahA==', '', '1f684.png'),
(91, '8J+ahQ==', '', '1f685.png'),
(92, '8J+ahw==', '', '1f687.png'),
(93, '8J+aiQ==', '', '1f689.png'),
(94, '8J+ajA==', '', '1f68c.png'),
(95, '8J+ajw==', '', '1f68f.png'),
(96, '8J+akQ==', '', '1f691.png'),
(97, '8J+akg==', '', '1f692.png'),
(98, '8J+akw==', '', '1f693.png'),
(99, '8J+alQ==', '', '1f695.png'),
(100, '8J+alw==', '', '1f697.png'),
(101, '8J+amQ==', '', '1f699.png'),
(102, '8J+amg==', '', '1f69a.png'),
(103, '8J+aog==', '', '1f6a2.png'),
(104, '8J+apA==', '', '1f6a4.png'),
(105, '8J+apQ==', '', '1f6a5.png'),
(106, '8J+apw==', '', '1f6a7.png'),
(107, '8J+aqA==', '', '1f6a8.png'),
(108, '8J+aqQ==', '', '1f6a9.png'),
(109, '8J+aqg==', '', '1f6aa.png'),
(110, '8J+aqw==', '', '1f6ab.png'),
(111, '8J+arA==', '', '1f6ac.png'),
(112, '8J+arQ==', '', '1f6ad.png'),
(113, '8J+asg==', '', '1f6b2.png'),
(114, '8J+atg==', '', '1f6b6.png'),
(115, '8J+auQ==', '', '1f6b9.png'),
(116, '8J+aug==', '', '1f6ba.png'),
(117, '8J+auw==', '', '1f6bb.png'),
(118, '8J+avA==', '', '1f6bc.png'),
(119, '8J+avQ==', '', '1f6bd.png'),
(120, '8J+avg==', '', '1f6be.png'),
(121, '8J+bgA==', '', '1f6c0.png'),
(122, '4pOC', '', '24c2.png'),
(123, '8J+FsA==', '', '1f170.png'),
(124, '8J+FsQ==', '', '1f171.png'),
(125, '8J+Fvg==', '', '1f17e.png'),
(126, '8J+Fvw==', '', '1f17f.png'),
(127, '8J+Gjg==', '', '1f18e.png'),
(128, '8J+GkQ==', '', '1f191.png'),
(129, '8J+Gkg==', '', '1f192.png'),
(130, '8J+Gkw==', '', '1f193.png'),
(131, '8J+GlA==', '', '1f194.png'),
(132, '8J+GlQ==', '', '1f195.png'),
(133, '8J+Glg==', '', '1f196.png'),
(134, '8J+Glw==', '', '1f197.png'),
(135, '8J+GmA==', '', '1f198.png'),
(136, '8J+GmQ==', '', '1f199.png'),
(137, '8J+Gmg==', '', '1f19a.png'),
(138, '8J+HqfA=', '', '1f1e9-1f1ea.png'),
(139, '8J+HrPA=', '', '1f1ec-1f1e7.png'),
(140, '8J+HqPA=', '', '1f1e8-1f1f3.png'),
(141, '8J+Hr/A=', '', '1f1ef-1f1f5.png'),
(142, '8J+HsPA=', '', '1f1f0-1f1f7.png'),
(143, '8J+Hq/A=', '', '1f1eb-1f1f7.png'),
(144, '8J+HqvA=', '', '1f1ea-1f1f8.png'),
(145, '8J+HrvA=', '', '1f1ee-1f1f9.png'),
(146, '8J+HuvA=', '', '1f1fa-1f1f8.png'),
(147, '8J+Ht/A=', '', '1f1f7-1f1fa.png'),
(148, '8J+IgQ==', '', '1f201.png'),
(149, '8J+Igg==', '', '1f202.png'),
(150, '8J+Img==', '', '1f21a.png'),
(151, '8J+Irw==', '', '1f22f.png'),
(152, '8J+Isg==', '', '1f232.png'),
(153, '8J+Isw==', '', '1f233.png'),
(154, '8J+ItA==', '', '1f234.png'),
(155, '8J+ItQ==', '', '1f235.png'),
(156, '8J+Itg==', '', '1f236.png'),
(157, '8J+Itw==', '', '1f237.png'),
(158, '8J+IuA==', '', '1f238.png'),
(159, '8J+IuQ==', '', '1f239.png'),
(160, '8J+Iug==', '', '1f23a.png'),
(161, '8J+JkA==', '', '1f250.png'),
(162, '8J+JkQ==', '', '1f251.png'),
(163, 'wqk=', '', '00a9.png'),
(164, 'wq4=', '', '00ae.png'),
(165, '4oC8', '', '203c.png'),
(166, '4oGJ', '', '2049.png'),
(167, 'OOKDow==', '', '0038-20e3.png'),
(168, 'OeKDow==', '', '0039-20e3.png'),
(169, 'N+KDow==', '', '0037-20e3.png'),
(170, 'NuKDow==', '', '0036-20e3.png'),
(171, 'MeKDow==', '', '0031-20e3.png'),
(172, 'MOKDow==', '', '0030-20e3.png'),
(173, 'MuKDow==', '', '0032-20e3.png'),
(174, 'M+KDow==', '', '0033-20e3.png'),
(175, 'NeKDow==', '', '0035-20e3.png'),
(176, 'NOKDow==', '', '0034-20e3.png'),
(177, 'I+KDow==', '', '0023-20e3.png'),
(178, '4oSi', '', '2122.png'),
(179, '4oS5', '', '2139.png'),
(180, '4oaU', '', '2194.png'),
(181, '4oaV', '', '2195.png'),
(182, '4oaW', '', '2196.png'),
(183, '4oaX', '', '2197.png'),
(184, '4oaY', '', '2198.png'),
(185, '4oaZ', '', '2199.png'),
(186, '4oap', '', '21a9.png'),
(187, '4oaq', '', '21aa.png'),
(188, '4oya', '', '231a.png'),
(189, '4oyb', '', '231b.png'),
(190, '4o+p', '', '23e9.png'),
(191, '4o+q', '', '23ea.png'),
(192, '4o+r', '', '23eb.png'),
(193, '4o+s', '', '23ec.png'),
(194, '4o+w', '', '23f0.png'),
(195, '4o+z', '', '23f3.png'),
(196, '4paq', '', '25aa.png'),
(197, '4par', '', '25ab.png'),
(198, '4pa2', '', '25b6.png'),
(199, '4peA', '', '25c0.png'),
(200, '4pe7', '', '25fb.png'),
(201, '4pe8', '', '25fc.png'),
(202, '4pe9', '', '25fd.png'),
(203, '4pe+', '', '25fe.png'),
(204, '4piA', '', '2600.png'),
(205, '4piB', '', '2601.png'),
(206, '4piO', '', '260e.png'),
(207, '4piR', '', '2611.png'),
(208, '4piU', '', '2614.png'),
(209, '4piV', '', '2615.png'),
(210, '4pid', '', '261d.png'),
(211, '4pi6', '', '263a.png'),
(212, '4pmI', '', '2648.png'),
(213, '4pmJ', '', '2649.png'),
(214, '4pmK', '', '264a.png'),
(215, '4pmL', '', '264b.png'),
(216, '4pmM', '', '264c.png'),
(217, '4pmN', '', '264d.png'),
(218, '4pmO', '', '264e.png'),
(219, '4pmP', '', '264f.png'),
(220, '4pmQ', '', '2650.png'),
(221, '4pmR', '', '2651.png'),
(222, '4pmS', '', '2652.png'),
(223, '4pmT', '', '2653.png'),
(224, '4pmg', '', '2660.png'),
(225, '4pmj', '', '2663.png'),
(226, '4pml', '', '2665.png'),
(227, '4pmm', '', '2666.png'),
(228, '4pmo', '', '2668.png'),
(229, '4pm7', '', '267b.png'),
(230, '4pm/', '', '267f.png'),
(231, '4pqT', '', '2693.png'),
(232, '4pqg', '', '26a0.png'),
(233, '4pqh', '', '26a1.png'),
(234, '4pqq', '', '26aa.png'),
(235, '4pqr', '', '26ab.png'),
(236, '4pq9', '', '26bd.png'),
(237, '4pq+', '', '26be.png'),
(238, '4puE', '', '26c4.png'),
(239, '4puF', '', '26c5.png'),
(240, '4puO', '', '26ce.png'),
(241, '4puU', '', '26d4.png'),
(242, '4puq', '', '26ea.png'),
(243, '4puy', '', '26f2.png'),
(244, '4puz', '', '26f3.png'),
(245, '4pu1', '', '26f5.png'),
(246, '4pu6', '', '26fa.png'),
(247, '4pu9', '', '26fd.png'),
(248, '4qS0', '', '2934.png'),
(249, '4qS1', '', '2935.png'),
(250, '4qyF', '', '2b05.png'),
(251, '4qyG', '', '2b06.png'),
(252, '4qyH', '', '2b07.png'),
(253, '4qyb', '', '2b1b.png'),
(254, '4qyc', '', '2b1c.png'),
(255, '4q2Q', '', '2b50.png'),
(256, '4q2V', '', '2b55.png'),
(257, '44Cw', '', '3030.png'),
(258, '44C9', '', '303d.png'),
(259, '44qX', '', '3297.png'),
(260, '44qZ', '', '3299.png'),
(261, '8J+AhA==', '', '1f004.png'),
(262, '8J+Djw==', '', '1f0cf.png'),
(263, '8J+MgA==', '', '1f300.png'),
(264, '8J+MgQ==', '', '1f301.png'),
(265, '8J+Mgg==', '', '1f302.png'),
(266, '8J+Mgw==', '', '1f303.png'),
(267, '8J+MhA==', '', '1f304.png'),
(268, '8J+MhQ==', '', '1f305.png'),
(269, '8J+Mhg==', '', '1f306.png'),
(270, '8J+Mhw==', '', '1f307.png'),
(271, '8J+MiA==', '', '1f308.png'),
(272, '8J+MiQ==', '', '1f309.png'),
(273, '8J+Mig==', '', '1f30a.png'),
(274, '8J+Miw==', '', '1f30b.png'),
(275, '8J+MjA==', '', '1f30c.png'),
(276, '8J+Mjw==', '', '1f30f.png'),
(277, '8J+MkQ==', '', '1f311.png'),
(278, '8J+Mkw==', '', '1f313.png'),
(279, '8J+MlA==', '', '1f314.png'),
(280, '8J+MlQ==', '', '1f315.png'),
(281, '8J+MmQ==', '', '1f319.png'),
(282, '8J+Mmw==', '', '1f31b.png'),
(283, '8J+Mnw==', '', '1f31f.png'),
(284, '8J+MoA==', '', '1f320.png'),
(285, '8J+MsA==', '', '1f330.png'),
(286, '8J+MsQ==', '', '1f331.png'),
(287, '8J+MtA==', '', '1f334.png'),
(288, '8J+MtQ==', '', '1f335.png'),
(289, '8J+Mtw==', '', '1f337.png'),
(290, '8J+MuA==', '', '1f338.png'),
(291, '8J+MuQ==', '', '1f339.png'),
(292, '8J+Mug==', '', '1f33a.png'),
(293, '8J+Muw==', '', '1f33b.png'),
(294, '8J+MvA==', '', '1f33c.png'),
(295, '8J+MvQ==', '', '1f33d.png'),
(296, '8J+Mvg==', '', '1f33e.png'),
(297, '8J+Mvw==', '', '1f33f.png'),
(298, '8J+NgA==', '', '1f340.png'),
(299, '8J+NgQ==', '', '1f341.png'),
(300, '8J+Ngg==', '', '1f342.png'),
(301, '8J+Ngw==', '', '1f343.png'),
(302, '8J+NhA==', '', '1f344.png'),
(303, '8J+NhQ==', '', '1f345.png'),
(304, '8J+Nhg==', '', '1f346.png'),
(305, '8J+Nhw==', '', '1f347.png'),
(306, '8J+NiA==', '', '1f348.png'),
(307, '8J+NiQ==', '', '1f349.png'),
(308, '8J+Nig==', '', '1f34a.png'),
(309, '8J+NjA==', '', '1f34c.png'),
(310, '8J+NjQ==', '', '1f34d.png'),
(311, '8J+Njg==', '', '1f34e.png'),
(312, '8J+Njw==', '', '1f34f.png'),
(313, '8J+NkQ==', '', '1f351.png'),
(314, '8J+Nkg==', '', '1f352.png'),
(315, '8J+Nkw==', '', '1f353.png'),
(316, '8J+NlA==', '', '1f354.png'),
(317, '8J+NlQ==', '', '1f355.png'),
(318, '8J+Nlg==', '', '1f356.png'),
(319, '8J+Nlw==', '', '1f357.png'),
(320, '8J+NmA==', '', '1f358.png'),
(321, '8J+NmQ==', '', '1f359.png'),
(322, '8J+Nmg==', '', '1f35a.png'),
(323, '8J+Nmw==', '', '1f35b.png'),
(324, '8J+NnA==', '', '1f35c.png'),
(325, '8J+NnQ==', '', '1f35d.png'),
(326, '8J+Nng==', '', '1f35e.png'),
(327, '8J+Nnw==', '', '1f35f.png'),
(328, '8J+NoA==', '', '1f360.png'),
(329, '8J+NoQ==', '', '1f361.png'),
(330, '8J+Nog==', '', '1f362.png'),
(331, '8J+Now==', '', '1f363.png'),
(332, '8J+NpA==', '', '1f364.png'),
(333, '8J+NpQ==', '', '1f365.png'),
(334, '8J+Npg==', '', '1f366.png'),
(335, '8J+Npw==', '', '1f367.png'),
(336, '8J+NqA==', '', '1f368.png'),
(337, '8J+NqQ==', '', '1f369.png'),
(338, '8J+Nqg==', '', '1f36a.png'),
(339, '8J+Nqw==', '', '1f36b.png'),
(340, '8J+NrA==', '', '1f36c.png'),
(341, '8J+NrQ==', '', '1f36d.png'),
(342, '8J+Nrg==', '', '1f36e.png'),
(343, '8J+Nrw==', '', '1f36f.png'),
(344, '8J+NsA==', '', '1f370.png'),
(345, '8J+NsQ==', '', '1f371.png'),
(346, '8J+Nsg==', '', '1f372.png'),
(347, '8J+Nsw==', '', '1f373.png'),
(348, '8J+NtA==', '', '1f374.png'),
(349, '8J+NtQ==', '', '1f375.png'),
(350, '8J+Ntg==', '', '1f376.png'),
(351, '8J+Ntw==', '', '1f377.png'),
(352, '8J+NuA==', '', '1f378.png'),
(353, '8J+NuQ==', '', '1f379.png'),
(354, '8J+Nug==', '', '1f37a.png'),
(355, '8J+Nuw==', '', '1f37b.png'),
(356, '8J+OgA==', '', '1f380.png'),
(357, '8J+OgQ==', '', '1f381.png'),
(358, '8J+Ogg==', '', '1f382.png'),
(359, '8J+Ogw==', '', '1f383.png'),
(360, '8J+OhA==', '', '1f384.png'),
(361, '8J+OhQ==', '', '1f385.png'),
(362, '8J+Ohg==', '', '1f386.png'),
(363, '8J+Ohw==', '', '1f387.png'),
(364, '8J+OiA==', '', '1f388.png'),
(365, '8J+OiQ==', '', '1f389.png'),
(366, '8J+Oig==', '', '1f38a.png'),
(367, '8J+Oiw==', '', '1f38b.png'),
(368, '8J+OjA==', '', '1f38c.png'),
(369, '8J+OjQ==', '', '1f38d.png'),
(370, '8J+Ojg==', '', '1f38e.png'),
(371, '8J+Ojw==', '', '1f38f.png'),
(372, '8J+OkA==', '', '1f390.png'),
(373, '8J+OkQ==', '', '1f391.png'),
(374, '8J+Okg==', '', '1f392.png'),
(375, '8J+Okw==', '', '1f393.png'),
(376, '8J+OoA==', '', '1f3a0.png'),
(377, '8J+OoQ==', '', '1f3a1.png'),
(378, '8J+Oog==', '', '1f3a2.png'),
(379, '8J+Oow==', '', '1f3a3.png'),
(380, '8J+OpA==', '', '1f3a4.png'),
(381, '8J+OpQ==', '', '1f3a5.png'),
(382, '8J+Opg==', '', '1f3a6.png'),
(383, '8J+Opw==', '', '1f3a7.png'),
(384, '8J+OqA==', '', '1f3a8.png'),
(385, '8J+OqQ==', '', '1f3a9.png'),
(386, '8J+Oqg==', '', '1f3aa.png'),
(387, '8J+Oqw==', '', '1f3ab.png'),
(388, '8J+OrA==', '', '1f3ac.png'),
(389, '8J+OrQ==', '', '1f3ad.png'),
(390, '8J+Org==', '', '1f3ae.png'),
(391, '8J+Orw==', '', '1f3af.png'),
(392, '8J+OsA==', '', '1f3b0.png'),
(393, '8J+OsQ==', '', '1f3b1.png'),
(394, '8J+Osg==', '', '1f3b2.png'),
(395, '8J+Osw==', '', '1f3b3.png'),
(396, '8J+OtA==', '', '1f3b4.png'),
(397, '8J+OtQ==', '', '1f3b5.png'),
(398, '8J+Otg==', '', '1f3b6.png'),
(399, '8J+Otw==', '', '1f3b7.png'),
(400, '8J+OuA==', '', '1f3b8.png'),
(401, '8J+OuQ==', '', '1f3b9.png'),
(402, '8J+Oug==', '', '1f3ba.png'),
(403, '8J+Ouw==', '', '1f3bb.png'),
(404, '8J+OvA==', '', '1f3bc.png'),
(405, '8J+OvQ==', '', '1f3bd.png'),
(406, '8J+Ovg==', '', '1f3be.png'),
(407, '8J+Ovw==', '', '1f3bf.png'),
(408, '8J+PgA==', '', '1f3c0.png'),
(409, '8J+PgQ==', '', '1f3c1.png'),
(410, '8J+Pgg==', '', '1f3c2.png'),
(411, '8J+Pgw==', '', '1f3c3.png'),
(412, '8J+PhA==', '', '1f3c4.png'),
(413, '8J+Phg==', '', '1f3c6.png'),
(414, '8J+PiA==', '', '1f3c8.png'),
(415, '8J+Pig==', '', '1f3ca.png'),
(416, '8J+PoA==', '', '1f3e0.png'),
(417, '8J+PoQ==', '', '1f3e1.png'),
(418, '8J+Pog==', '', '1f3e2.png'),
(419, '8J+Pow==', '', '1f3e3.png'),
(420, '8J+PpQ==', '', '1f3e5.png'),
(421, '8J+Ppg==', '', '1f3e6.png'),
(422, '8J+Ppw==', '', '1f3e7.png'),
(423, '8J+PqA==', '', '1f3e8.png'),
(424, '8J+PqQ==', '', '1f3e9.png'),
(425, '8J+Pqg==', '', '1f3ea.png'),
(426, '8J+Pqw==', '', '1f3eb.png'),
(427, '8J+PrA==', '', '1f3ec.png'),
(428, '8J+PrQ==', '', '1f3ed.png'),
(429, '8J+Prg==', '', '1f3ee.png'),
(430, '8J+Prw==', '', '1f3ef.png'),
(431, '8J+PsA==', '', '1f3f0.png'),
(432, '8J+QjA==', '', '1f40c.png'),
(433, '8J+QjQ==', '', '1f40d.png'),
(434, '8J+Qjg==', '', '1f40e.png'),
(435, '8J+QkQ==', '', '1f411.png'),
(436, '8J+Qkg==', '', '1f412.png'),
(437, '8J+QlA==', '', '1f414.png'),
(438, '8J+Qlw==', '', '1f417.png'),
(439, '8J+QmA==', '', '1f418.png'),
(440, '8J+QmQ==', '', '1f419.png'),
(441, '8J+Qmg==', '', '1f41a.png'),
(442, '8J+Qmw==', '', '1f41b.png'),
(443, '8J+QnA==', '', '1f41c.png'),
(444, '8J+QnQ==', '', '1f41d.png'),
(445, '8J+Qng==', '', '1f41e.png'),
(446, '8J+Qnw==', '', '1f41f.png'),
(447, '8J+QoA==', '', '1f420.png'),
(448, '8J+QoQ==', '', '1f421.png'),
(449, '8J+Qog==', '', '1f422.png'),
(450, '8J+Qow==', '', '1f423.png'),
(451, '8J+QpA==', '', '1f424.png'),
(452, '8J+QpQ==', '', '1f425.png'),
(453, '8J+Qpg==', '', '1f426.png'),
(454, '8J+Qpw==', '', '1f427.png'),
(455, '8J+QqA==', '', '1f428.png'),
(456, '8J+QqQ==', '', '1f429.png'),
(457, '8J+Qqw==', '', '1f42b.png'),
(458, '8J+QrA==', '', '1f42c.png'),
(459, '8J+QrQ==', '', '1f42d.png'),
(460, '8J+Qrg==', '', '1f42e.png'),
(461, '8J+Qrw==', '', '1f42f.png'),
(462, '8J+QsA==', '', '1f430.png'),
(463, '8J+QsQ==', '', '1f431.png'),
(464, '8J+Qsg==', '', '1f432.png'),
(465, '8J+Qsw==', '', '1f433.png'),
(466, '8J+QtA==', '', '1f434.png'),
(467, '8J+QtQ==', '', '1f435.png'),
(468, '8J+Qtg==', '', '1f436.png'),
(469, '8J+Qtw==', '', '1f437.png'),
(470, '8J+QuA==', '', '1f438.png'),
(471, '8J+QuQ==', '', '1f439.png'),
(472, '8J+Qug==', '', '1f43a.png'),
(473, '8J+Quw==', '', '1f43b.png'),
(474, '8J+QvA==', '', '1f43c.png'),
(475, '8J+QvQ==', '', '1f43d.png'),
(476, '8J+Qvg==', '', '1f43e.png'),
(477, '8J+RgA==', '', '1f440.png'),
(478, '8J+Rgg==', '', '1f442.png'),
(479, '8J+Rgw==', '', '1f443.png'),
(480, '8J+RhA==', '', '1f444.png'),
(481, '8J+RhQ==', '', '1f445.png'),
(482, '8J+Rhg==', '', '1f446.png'),
(483, '8J+Rhw==', '', '1f447.png'),
(484, '8J+RiA==', '', '1f448.png'),
(485, '8J+RiQ==', '', '1f449.png'),
(486, '8J+Rig==', '', '1f44a.png'),
(487, '8J+Riw==', '', '1f44b.png'),
(488, '8J+RjA==', '', '1f44c.png'),
(489, '8J+RjQ==', '', '1f44d.png'),
(490, '8J+Rjg==', '', '1f44e.png'),
(491, '8J+Rjw==', '', '1f44f.png'),
(492, '8J+RkA==', '', '1f450.png'),
(493, '8J+RkQ==', '', '1f451.png'),
(494, '8J+Rkg==', '', '1f452.png'),
(495, '8J+Rkw==', '', '1f453.png'),
(496, '8J+RlA==', '', '1f454.png'),
(497, '8J+RlQ==', '', '1f455.png'),
(498, '8J+Rlg==', '', '1f456.png'),
(499, '8J+Rlw==', '', '1f457.png'),
(500, '8J+RmA==', '', '1f458.png'),
(501, '8J+RmQ==', '', '1f459.png'),
(502, '8J+Rmg==', '', '1f45a.png'),
(503, '8J+Rmw==', '', '1f45b.png'),
(504, '8J+RnA==', '', '1f45c.png'),
(505, '8J+RnQ==', '', '1f45d.png'),
(506, '8J+Rng==', '', '1f45e.png'),
(507, '8J+Rnw==', '', '1f45f.png'),
(508, '8J+RoA==', '', '1f460.png'),
(509, '8J+RoQ==', '', '1f461.png'),
(510, '8J+Rog==', '', '1f462.png'),
(511, '8J+Row==', '', '1f463.png'),
(512, '8J+RpA==', '', '1f464.png'),
(513, '8J+Rpg==', '', '1f466.png'),
(514, '8J+Rpw==', '', '1f467.png'),
(515, '8J+RqA==', '', '1f468.png'),
(516, '8J+RqQ==', '', '1f469.png'),
(517, '8J+Rqg==', '', '1f46a.png'),
(518, '8J+Rqw==', '', '1f46b.png'),
(519, '8J+Rrg==', '', '1f46e.png'),
(520, '8J+Rrw==', '', '1f46f.png'),
(521, '8J+RsA==', '', '1f470.png'),
(522, '8J+RsQ==', '', '1f471.png'),
(523, '8J+Rsg==', '', '1f472.png'),
(524, '8J+Rsw==', '', '1f473.png'),
(525, '8J+RtA==', '', '1f474.png'),
(526, '8J+RtQ==', '', '1f475.png'),
(527, '8J+Rtg==', '', '1f476.png'),
(528, '8J+Rtw==', '', '1f477.png'),
(529, '8J+RuA==', '', '1f478.png'),
(530, '8J+RuQ==', '', '1f479.png'),
(531, '8J+Rug==', '', '1f47a.png'),
(532, '8J+Ruw==', '', '1f47b.png'),
(533, '8J+RvA==', '', '1f47c.png'),
(534, '8J+RvQ==', '', '1f47d.png'),
(535, '8J+Rvg==', '', '1f47e.png'),
(536, '8J+Rvw==', '', '1f47f.png'),
(537, '8J+SgA==', '', '1f480.png'),
(538, '8J+SgQ==', '', '1f481.png'),
(539, '8J+Sgg==', '', '1f482.png'),
(540, '8J+Sgw==', '', '1f483.png'),
(541, '8J+ShA==', '', '1f484.png'),
(542, '8J+ShQ==', '', '1f485.png'),
(543, '8J+Shg==', '', '1f486.png'),
(544, '8J+Shw==', '', '1f487.png'),
(545, '8J+SiA==', '', '1f488.png'),
(546, '8J+SiQ==', '', '1f489.png'),
(547, '8J+Sig==', '', '1f48a.png'),
(548, '8J+Siw==', '', '1f48b.png'),
(549, '8J+SjA==', '', '1f48c.png'),
(550, '8J+SjQ==', '', '1f48d.png'),
(551, '8J+Sjg==', '', '1f48e.png'),
(552, '8J+Sjw==', '', '1f48f.png'),
(553, '8J+SkA==', '', '1f490.png'),
(554, '8J+SkQ==', '', '1f491.png'),
(555, '8J+Skg==', '', '1f492.png'),
(556, '8J+Skw==', '', '1f493.png'),
(557, '8J+SlA==', '', '1f494.png'),
(558, '8J+SlQ==', '', '1f495.png'),
(559, '8J+Slg==', '', '1f496.png'),
(560, '8J+Slw==', '', '1f497.png'),
(561, '8J+SmA==', '', '1f498.png'),
(562, '8J+SmQ==', '', '1f499.png'),
(563, '8J+Smg==', '', '1f49a.png'),
(564, '8J+Smw==', '', '1f49b.png'),
(565, '8J+SnA==', '', '1f49c.png'),
(566, '8J+SnQ==', '', '1f49d.png'),
(567, '8J+Sng==', '', '1f49e.png'),
(568, '8J+Snw==', '', '1f49f.png'),
(569, '8J+SoA==', '', '1f4a0.png'),
(570, '8J+SoQ==', '', '1f4a1.png'),
(571, '8J+Sog==', '', '1f4a2.png'),
(572, '8J+Sow==', '', '1f4a3.png'),
(573, '8J+SpA==', '', '1f4a4.png'),
(574, '8J+SpQ==', '', '1f4a5.png'),
(575, '8J+Spg==', '', '1f4a6.png'),
(576, '8J+Spw==', '', '1f4a7.png'),
(577, '8J+SqA==', '', '1f4a8.png'),
(578, '8J+SqQ==', '', '1f4a9.png'),
(579, '8J+Sqg==', '', '1f4aa.png'),
(580, '8J+Sqw==', '', '1f4ab.png'),
(581, '8J+SrA==', '', '1f4ac.png'),
(582, '8J+Srg==', '', '1f4ae.png'),
(583, '8J+Srw==', '', '1f4af.png'),
(584, '8J+SsA==', '', '1f4b0.png'),
(585, '8J+SsQ==', '', '1f4b1.png'),
(586, '8J+Ssg==', '', '1f4b2.png'),
(587, '8J+Ssw==', '', '1f4b3.png'),
(588, '8J+StA==', '', '1f4b4.png'),
(589, '8J+StQ==', '', '1f4b5.png'),
(590, '8J+SuA==', '', '1f4b8.png'),
(591, '8J+SuQ==', '', '1f4b9.png'),
(592, '8J+Sug==', '', '1f4ba.png'),
(593, '8J+Suw==', '', '1f4bb.png'),
(594, '8J+SvA==', '', '1f4bc.png'),
(595, '8J+SvQ==', '', '1f4bd.png'),
(596, '8J+Svg==', '', '1f4be.png'),
(597, '8J+Svw==', '', '1f4bf.png'),
(598, '8J+TgA==', '', '1f4c0.png'),
(599, '8J+TgQ==', '', '1f4c1.png'),
(600, '8J+Tgg==', '', '1f4c2.png'),
(601, '8J+Tgw==', '', '1f4c3.png'),
(602, '8J+ThA==', '', '1f4c4.png'),
(603, '8J+ThQ==', '', '1f4c5.png'),
(604, '8J+Thg==', '', '1f4c6.png'),
(605, '8J+Thw==', '', '1f4c7.png'),
(606, '8J+TiA==', '', '1f4c8.png'),
(607, '8J+TiQ==', '', '1f4c9.png'),
(608, '8J+Tig==', '', '1f4ca.png'),
(609, '8J+Tiw==', '', '1f4cb.png'),
(610, '8J+TjA==', '', '1f4cc.png'),
(611, '8J+TjQ==', '', '1f4cd.png'),
(612, '8J+Tjg==', '', '1f4ce.png'),
(613, '8J+Tjw==', '', '1f4cf.png'),
(614, '8J+TkA==', '', '1f4d0.png'),
(615, '8J+TkQ==', '', '1f4d1.png'),
(616, '8J+Tkg==', '', '1f4d2.png'),
(617, '8J+Tkw==', '', '1f4d3.png'),
(618, '8J+TlA==', '', '1f4d4.png'),
(619, '8J+TlQ==', '', '1f4d5.png'),
(620, '8J+Tlg==', '', '1f4d6.png'),
(621, '8J+Tlw==', '', '1f4d7.png'),
(622, '8J+TmA==', '', '1f4d8.png'),
(623, '8J+TmQ==', '', '1f4d9.png'),
(624, '8J+Tmg==', '', '1f4da.png'),
(625, '8J+Tmw==', '', '1f4db.png'),
(626, '8J+TnA==', '', '1f4dc.png'),
(627, '8J+TnQ==', '', '1f4dd.png'),
(628, '8J+Tng==', '', '1f4de.png'),
(629, '8J+Tnw==', '', '1f4df.png'),
(630, '8J+ToA==', '', '1f4e0.png'),
(631, '8J+ToQ==', '', '1f4e1.png'),
(632, '8J+Tog==', '', '1f4e2.png'),
(633, '8J+Tow==', '', '1f4e3.png'),
(634, '8J+TpA==', '', '1f4e4.png'),
(635, '8J+TpQ==', '', '1f4e5.png'),
(636, '8J+Tpg==', '', '1f4e6.png'),
(637, '8J+Tpw==', '', '1f4e7.png'),
(638, '8J+TqA==', '', '1f4e8.png'),
(639, '8J+TqQ==', '', '1f4e9.png'),
(640, '8J+Tqg==', '', '1f4ea.png'),
(641, '8J+Tqw==', '', '1f4eb.png'),
(642, '8J+Trg==', '', '1f4ee.png'),
(643, '8J+TsA==', '', '1f4f0.png'),
(644, '8J+TsQ==', '', '1f4f1.png'),
(645, '8J+Tsg==', '', '1f4f2.png'),
(646, '8J+Tsw==', '', '1f4f3.png'),
(647, '8J+TtA==', '', '1f4f4.png'),
(648, '8J+Ttg==', '', '1f4f6.png'),
(649, '8J+Ttw==', '', '1f4f7.png'),
(650, '8J+TuQ==', '', '1f4f9.png'),
(651, '8J+Tug==', '', '1f4fa.png'),
(652, '8J+Tuw==', '', '1f4fb.png'),
(653, '8J+TvA==', '', '1f4fc.png'),
(654, '8J+Ugw==', '', '1f503.png'),
(655, '8J+Uig==', '', '1f50a.png'),
(656, '8J+Uiw==', '', '1f50b.png'),
(657, '8J+UjA==', '', '1f50c.png'),
(658, '8J+UjQ==', '', '1f50d.png'),
(659, '8J+Ujg==', '', '1f50e.png'),
(660, '8J+Ujw==', '', '1f50f.png'),
(661, '8J+UkA==', '', '1f510.png'),
(662, '8J+UkQ==', '', '1f511.png'),
(663, '8J+Ukg==', '', '1f512.png'),
(664, '8J+Ukw==', '', '1f513.png'),
(665, '8J+UlA==', '', '1f514.png'),
(666, '8J+Ulg==', '', '1f516.png'),
(667, '8J+Ulw==', '', '1f517.png'),
(668, '8J+UmA==', '', '1f518.png'),
(669, '8J+UmQ==', '', '1f519.png'),
(670, '8J+Umg==', '', '1f51a.png'),
(671, '8J+Umw==', '', '1f51b.png'),
(672, '8J+UnA==', '', '1f51c.png'),
(673, '8J+UnQ==', '', '1f51d.png'),
(674, '8J+Ung==', '', '1f51e.png'),
(675, '8J+Unw==', '', '1f51f.png'),
(676, '8J+UoA==', '', '1f520.png'),
(677, '8J+UoQ==', '', '1f521.png'),
(678, '8J+Uog==', '', '1f522.png'),
(679, '8J+Uow==', '', '1f523.png'),
(680, '8J+UpA==', '', '1f524.png'),
(681, '8J+UpQ==', '', '1f525.png'),
(682, '8J+Upg==', '', '1f526.png'),
(683, '8J+Upw==', '', '1f527.png'),
(684, '8J+UqA==', '', '1f528.png'),
(685, '8J+UqQ==', '', '1f529.png'),
(686, '8J+Uqg==', '', '1f52a.png'),
(687, '8J+Uqw==', '', '1f52b.png'),
(688, '8J+Urg==', '', '1f52e.png'),
(689, '8J+Urw==', '', '1f52f.png'),
(690, '8J+UsA==', '', '1f530.png'),
(691, '8J+UsQ==', '', '1f531.png'),
(692, '8J+Usg==', '', '1f532.png'),
(693, '8J+Usw==', '', '1f533.png'),
(694, '8J+UtA==', '', '1f534.png'),
(695, '8J+UtQ==', '', '1f535.png'),
(696, '8J+Utg==', '', '1f536.png'),
(697, '8J+Utw==', '', '1f537.png'),
(698, '8J+UuA==', '', '1f538.png'),
(699, '8J+UuQ==', '', '1f539.png'),
(700, '8J+Uug==', '', '1f53a.png'),
(701, '8J+Uuw==', '', '1f53b.png'),
(702, '8J+UvA==', '', '1f53c.png'),
(703, '8J+UvQ==', '', '1f53d.png'),
(704, '8J+VkA==', '', '1f550.png'),
(705, '8J+VkQ==', '', '1f551.png'),
(706, '8J+Vkg==', '', '1f552.png'),
(707, '8J+Vkw==', '', '1f553.png'),
(708, '8J+VlA==', '', '1f554.png'),
(709, '8J+VlQ==', '', '1f555.png'),
(710, '8J+Vlg==', '', '1f556.png'),
(711, '8J+Vlw==', '', '1f557.png'),
(712, '8J+VmA==', '', '1f558.png'),
(713, '8J+VmQ==', '', '1f559.png'),
(714, '8J+Vmg==', '', '1f55a.png'),
(715, '8J+Vmw==', '', '1f55b.png'),
(716, '8J+Xuw==', '', '1f5fb.png'),
(717, '8J+XvA==', '', '1f5fc.png'),
(718, '8J+XvQ==', '', '1f5fd.png'),
(719, '8J+Xvg==', '', '1f5fe.png'),
(720, '8J+Xvw==', '', '1f5ff.png'),
(721, '8J+YgA==', '', '1f600.png'),
(722, '8J+Yhw==', '', '1f607.png'),
(723, '8J+YiA==', '', '1f608.png'),
(724, '8J+Yjg==', '', '1f60e.png'),
(725, '8J+YkA==', '', '1f610.png'),
(726, '4qyF77iP', '', ''),
(727, '8J+Xng==', '', ''),
(728, '8J+kkQ==', '', ''),
(729, '8J+Xgw==', '', ''),
(730, '8J+boA==', '', ''),
(731, '4pm777iP', '', ''),
(732, '8J+bjQ==', '', '');

CREATE TABLE `unicode_struktura` (
  `id` int(11) NOT NULL,
  `cmd_id` int(11) NOT NULL,
  `id_unicode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `unicode_struktura` (`id`, `cmd_id`, `id_unicode`) VALUES
(31, 1, 512),
(33, 9, 506),
(34, 5, 731),
(35, 3, 619),
(36, 4, 728),
(38, 10, 164),
(39, 6, 518),
(40, 8, 649),
(41, 7, 599),
(42, 11, 204),
(43, 12, 574),
(44, 13, 731),
(45, 14, 277),
(46, 15, 433),
(47, 16, 76),
(48, 17, 398),
(49, 18, 451),
(50, 19, 388),
(51, 20, 524),
(52, 21, 709),
(53, 22, 513),
(54, 23, 714),
(55, 24, 495),
(57, 12, 574),
(58, 12, 574),
(59, 3, 20),
(60, 40, 309),
(61, 41, 68),
(63, 44, 484),
(64, 43, 732),
(65, 45, 61),
(66, 46, 2),
(67, 50, 50),
(68, 52, 69),
(69, 54, 2),
(70, 55, 67),
(71, 56, 2),
(72, 57, 598),
(73, 58, 81),
(74, 59, 75),
(75, 60, 85),
(76, 65, 29),
(77, 68, 728),
(78, 70, 686),
(79, 75, 574),
(80, 77, 574),
(81, 159, 686),
(82, 439, 69),
(83, 680, 102),
(84, 477, 204),
(85, 681, 728),
(86, 682, 731),
(87, 683, 731),
(88, 684, 417),
(89, 685, 379),
(90, 686, 731),
(91, 687, 691),
(92, 688, 691),
(93, 692, 636),
(94, 693, 729),
(95, 694, 731),
(96, 695, 728),
(97, 696, 728),
(98, 697, 728),
(99, 689, 691),
(100, 690, 339),
(101, 691, 619),
(102, 698, 731),
(103, 699, 731),
(104, 700, 699),
(105, 701, 731);

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `user` text,
  `username` text NOT NULL,
  `id_chat` text,
  `discount` int(11) NOT NULL,
  `date` text,
  `count_message` int(11) DEFAULT NULL,
  `lock` int(11) NOT NULL,
  `email` text NOT NULL,
  `cmd_id` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `amount_add` text NOT NULL,
  `referer` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `z_step` text NOT NULL,
  `limit` int(11) NOT NULL,
  `pay_id` int(11) NOT NULL,
  `self_task` text NOT NULL,
  `count_msg` int(11) NOT NULL,
  `query` int(11) NOT NULL,
  `id_zakaz` int(11) NOT NULL,
  `tmp_data` text NOT NULL,
  `how_method` int(11) NOT NULL,
  `phone` text NOT NULL,
  `msg_id_chat` int(11) NOT NULL,
  `qiwi_sum` text NOT NULL,
  `qiwi_wallet` text NOT NULL,
  `btc_address` text NOT NULL,
  `secret_pay_code` text NOT NULL,
  `id_city` int(11) NOT NULL,
  `id_bot` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `a_menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `a_zakaz`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `a_zakaz_items`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bot_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cat_photo`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cat_struktura`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `currency_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `delivery_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `how_delivery`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `list_referer`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `losg_action`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `multiple_qiwi`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders_referers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `payment_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `product_value_units`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `prop_list_catalog`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sales_total`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `setting_payment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `static_day`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `struktura`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `unicode`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `unicode_struktura`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `Accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `a_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `a_zakaz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `a_zakaz_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `basket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bot_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

ALTER TABLE `catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `cat_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `cat_struktura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

ALTER TABLE `currency_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

ALTER TABLE `delivery_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE `how_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `list_referer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `losg_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `multiple_qiwi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `orders_referers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `product_value_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `prop_list_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `sales_total`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `setting_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `static_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `struktura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=702;

ALTER TABLE `unicode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=733;

ALTER TABLE `unicode_struktura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
