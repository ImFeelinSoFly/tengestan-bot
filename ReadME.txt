������� � ����� https://boominfo.ru - �������������!

��������� -  Bot shop.

���������� � VPS �������: php7.2, MySql, Apache2.
������� ssl ����������� � ������(https).

1 ����������� � �����.
2. ������� ��.
3. ��������� db.php � /telegram/db.php
4. ������� ����� ������ 777 �� ��������:
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

6. ������������: 
https://domain.ru/login.php
admin:z6GZ1Jbq66

