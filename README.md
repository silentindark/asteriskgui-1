# Asterisk.GUI

 This is the Javascript / HTML / CSS / Ajax related to asterisk app. 
 Only for viewing settings and conditions, not to configure.
 Screenshot from real system with Elastix in folder "screenshot".
 Using AMI for request info about channels and mysql for CDR stat. 

 Default login/password - admin/admin

# Features
 - CDR stat 
 - Show sip peers 
 - Show sip registry 
 - Show sip channel stats
 - Queues panel: realtime agent status and calls
 - Show diag info from "core show ... "
 - All tables support sorting, filtering and export to excel

# Version

Current version 0.6
 
# Up and Running

1. Склонировать git в /usr/local/voip/src/
2. /usr/local/voip/src/asteriskgui/asteriskgui скопировать в /var/www/ переименовав в клиенсткую
3. Отредактировать клиентский db/config.php под его ВАТС, User/Password указать те, под которыми он будет логиниться
4. Задать всем файлам и папкам www-data:www-data


