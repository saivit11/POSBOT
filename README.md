# POSBOT

## SETUP INSTRUCTIONS
The following are the steps one should follow to run this project on their local computers.

* Download & Install Xampp from the internet in your C drive. Any version above 7.3.28 should work fine.
* After Installation of Xampp, navigate to  ``` xampp\htdocs\ ``` on your pc.
* Clone this repository in the current folder.
* Rename the cloned repository foldername as ``` POSBOT ```.
* Open **xampp control panel** and start the **actions** corresponding to **Apache** & **MySQL**
* Open your internet browser and type ```localhost/phpmyadmin/``` in the search bar.
  * Now click **new** on the left panel.
  * In the text box of **Database name** write **posbot**, and then click **create**.
  * On the left panel now you should see another item named **posbot**. 
  * Now click on **posbot** on the left panel, and then click **import** from the top panel.
  * Now click the **choose file** option, then navigate to ```xampp\htdocs\POSBOT\sql\``` and then select the ```posbot.sql``` file, then hit **Go** button on the bottom right.
  * Now close this tab.
* Open another internet browser tab and type ```local/phpmyqdmin/POSBOT/index.php/``` in the search bar.
* And Voila, there you go!!! You are ready to run this on project on your local machine!!!
