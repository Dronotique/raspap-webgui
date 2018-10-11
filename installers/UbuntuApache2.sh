cd /var/www/html
ls
mkdir raspap
sudo git clone https://github.com/billz/raspap-webgui /var/www/html/raspap
cat /etc/network/interfaces
sudo nano /etc/init.d/hostapd
sudo nano /etc/hostapd/hostapd.conf
lsb -release
lsb_release -a
sudo nano /etc/sudoers
cat /etc/passwd
sudo chown -R www-data:www-data /var/www/html/raspap/
sudo mkdir /etc/raspap
sudo mv /var/www/html/raspap/raspap.php /etc/raspap/
sudo chown -R www-data:www-data /etc/raspap
sudo mkdir /etc/raspap/hostapd
sudo mv /var/www/html/installers/*log.sh /etc/raspap/hostapd
sudo mv /var/www/html/raspap/installers/*log.sh /etc/raspap/hostapd
cd /etc/raspap
ls
cat raspap.php 
cd hostapd/
ls
cat enablelog.sh 
cat disablelog.sh 
sudo reboot