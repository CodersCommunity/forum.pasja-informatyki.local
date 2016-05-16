====================================
Question2Answer Most Active Users Widget (per time interval) v1.2
====================================
-----------
Description
-----------
This plugin for **Question2Answer** displays the most active users of the current week or the current month in a widget. 

--------
Features
--------
- considers question/answer/comment posted as +1 activity point
- needs plugin "Event Logger" enabled, check Admin > Plugins >Event Logger >options (tick "Log events to qa_eventlog database table")

------------
Installation
------------
#. Install Question2Answer_
#. Get the source code for this plugin from _github, download directly from the `project page`_ and click **Download**
#. Extract the files to a subfolder such as ``most-active-users-widget`` inside the ``qa-plugins`` folder of your Q2A installation.
#. Change language strings in qa-most-active-users-lang.php
#. Change settings (week or month display, number of users etc.) in qa-most-active-users.php
#. Navigate to your site, go to **Admin -> Plugins** on your q2a install.
#. Set up the event logger plugin to ``Log events to qa_eventlog database table``.
#. Then go to Admin >Layout >Available widgets, and add the widget "Most active users per week/month" where you want it to appear

.. _Question2Answer: http://www.question2answer.org/install.php
.. _github: https://www.github.com/echteinfachtv/q2a-most-active-users/
.. _project page: https://github.com/echteinfachtv/q2a-most-active-users

----------
Disclaimer
----------
This is **beta** code. It is probably okay for production environments, but may not work exactly as expected. You bear the risk. Refunds will not be given!

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

-------
Copyright
-------
All code herein is OpenSource_. Feel free to build upon it and share with the world.

.. _OpenSource: http://www.gnu.org/licenses/gpl.html

---------
About q2a
---------
Question2Answer is a free and open source platform for Q&A sites. For more information, visit: www.question2answer.org

---------
Final Note
---------
If you use the plugin:
+ Consider joining the Question2Answer-Forum_, answer some questions or write your own plugin!
+ You can use the code of this plugin to learn more about q2a-plugins. It is commented code.
+ Thanks!

.. _Question2Answer-Forum: http://www.question2answer.org/qa/

