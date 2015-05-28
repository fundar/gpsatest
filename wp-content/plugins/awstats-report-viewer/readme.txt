=== AWStats Report Viewer ===
Contributors: xpointer
Donate link: http://wp-arv.xptrdev.com
Tags: access logs, Apache log, cpanel, logs, analytics, awstats, report, statistics, visitors, browsers, ips, os, hosts, robots, keywords
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

View CPanel's AWStats report via Wordpress Dashboard page.

== Description ==

Monthly web access logs report. View CPanel's AWstats report via Wordpress Dashbaord page. Create, Delete and Update report simply and easily.

= AWStats =
[AWStats](http://www.awstats.org/) is a free powerful and featureful tool that generates advanced web, streaming, ftp or mail server statistics, graphically. This log analyzer works as a CGI or from command line and shows you all possible information your log contains, in few graphical web pages. It uses a partial information file to be able to process large log files, often and quickly. It can analyze log files from all major server tools like Apache log files (NCSA combined/XLF/ELF log format or common/CLF log format), WebStar, IIS (W3C log format) and a lot of other web, proxy, wap, streaming servers, mail servers and some ftp servers.
Take a look at this comparison table for an idea on features and differences between most famous statistics tools (AWStats, Analog, Webalizer,...).
AWStats is a free software distributed under the GNU General Public License. You can have a look at this license chart to know what you can/can't do.
As AWStats works from the command line but also as a CGI, it can work with all web hosting providers which allow Perl, CGI and log access.

= Features =
* Number of visits, and number of unique visitors,
* Visits duration and last visits,
* Authenticated users, and last authenticated visits,
* Days of week and rush hours (pages, hits, KB for each hour and day of week),
* Domains/countries of hosts visitors (pages, hits, KB, 269 domains/countries detected, GeoIp detection),
* Hosts list, last visits and unresolved IP addresses list,
* Most viewed, entry and exit pages,
* Files type,
* Web compression statistics (for mod_gzip or mod_deflate),
* OS used (pages, hits, KB for each OS, 35 OS detected),
* Browsers used (pages, hits, KB for each browser, each version (Web, Wap, Media browsers: 97 browsers, more than 450 if using browsers_phone.pm library file),
* Visits of robots (319 robots detected),
* Worms attacks (5 worm's families),
* Search engines, keyphrases and keywords used to find your site (The 115 most famous search engines are detected like yahoo, google, altavista, etc...),
* HTTP errors (Page Not Found with last referrer, ...),
* Other personalized reports based on url, url parameters, referer field for miscellanous/marketing purpose,
* Number of times your site is "added to favourites bookmarks".
* Screen size (need to add some HTML tags in index page).
* Ratio of Browsers with support of: Java, Flash, RealG2 reader, Quicktime reader, WMA reader, PDF reader (need to add some HTML tags in index page).
* Cluster report for load balanced servers ratio.

== Installation ==

1. Upload `awstats-report-viewer.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'AWStats Report Installer' Dashboard page anf follow the instruction to complete the installation
4. After Installation completed, the awstats report is automatically created for the first time and you will prmopted to go to the report page.

== Requirements ==
* Linux Server with CPanel installed
* PHP >= 5.3

== Frequently Asked Questions ==

= Could I use ARV under Windows Server? =

No. It won't work. Its developed to use AWStats report that is mostely installed with the CPanel that comes with Linux servers.

= Should I change the installation Parameters? =

Only if aware of what you're doing. ARV Plugin is automatically discover installation parameters for you and it would work under most system ocnfiguration

= What is the purspose of the 'Regenerate' button? =

ARV Plugin is saving AWStats report under Wordpress wp-content folder. It creates a unique LONG number
for the report directory name (e.g: 954693ec2b66aa9f51876107bf1880ef54707a492c40c0.87758923), it then gives
every file a unique identifier. If you felt that, for some reason, the report is accessed from Public user you can change the unique identified by forcing
ARV to re-genarate all the exists Unique Ids.

== Screenshots ==

1. Installation Form
2. Report Screen

== Changelog ==
= 0.7 =
* Security improvement

= 0.6 =
* Framework updates

= 0.5 =
First release