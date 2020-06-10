Скачано с сайта https://boominfo.ru - Присоединяйся!

Установка -  Bot shop.

Требование к VPS серверу: php7.2, MySql, Apache2.
Наличие ssl сертификата у домена(https).

1 Распаковать в корне.
2. Создать бд.
3. Настроить db.php и /telegram/db.php
4. Указать права записи 777 на каталоги:
	/upload/
	/upload/delivery
5. CRON:
		FOR SSH:
			*/1 * * * * curl -s https://inbrain.online/task_run.php?action=delivery
			*/1 * * * * curl -s https://inbrain.online/task_run.php?action=check_wallets
			*/1 * * * * curl -s https://inbrain.online/check_reserve.php
	
		COMMANDS FOR CRON:
			curl -s https://domain.ru/task_run.php?action=delivery
			curl -s https://domain.ru/task_run.php?action=check_wallets
			curl -s https://inbrain.online/check_reserve.php

6. Авторизациия: 
https://domain.ru/login.php
admin:z6GZ1Jbq66

