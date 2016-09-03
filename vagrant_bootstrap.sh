#!/usr/bin/env bash
mysql_password=om1234   # Dummy password, update it
apt-get update
apt-get install -y apache2
rm -rf /var/www
ln -fs /vagrant /var/www

#set-up apache
apt-get install -y vim htop subversion git-all
apt-get install -y libapache2-mod-php5 php5-cgi php5-cli
a2enmod ssl
a2enmod rewrite
a2ensite default-ssl
sed -i.bak '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride all/' /etc/apache2/sites-available/default
service apache2 restart

#set up mysql
apt-get install -y debconf-utils
#export DEBIAN_FRONTEND=noninteractive
echo "mysql-server-5.5 mysql-server/root_password_again password $mysql_password" | debconf-set-selections
echo "mysql-server-5.5 mysql-server/root_password password $mysql_password" | debconf-set-selections
apt-get install -y mysql-server
#mysqladmin -u root password $mysql_password

#set up phpmyadmin
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $mysql_password" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password " | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections
apt-get install -y phpmyadmin

#php modules
apt-get install -y php5-curl php5-gd php5-mcrypt php5-memcached php5-mysql php5-sqlite php5-xdebug  php-apc php-soap

#suphp
#apt-get install -y libapache2-mod-suphp
#a2dismod php5
#a2enmod suphp
