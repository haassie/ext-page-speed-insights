Page Speed Insights for TYPO3
=============================

The performance of your website is important for several reasons. The most important one is that if a page is to slow
you will lose visitors. Studies showed that over 50% of the people will leave a page that takes longer than three seconds
to load. 

As this is important for users, it is important for search engines as well. Search engines want to give the user the
best results possible. If a search engines have to choose between two results that both are interesting for a user, the
fastest one will definitely have a higher change to rank over the other result. 

About the extension
-------------------
This extension will give you the possibility to check your TYPO3 pages with [PageSpeed Insights](https://developers.google.com/speed/docs/insights/v5/about).
The performance of your page is checked with Lighthouse and will give you an indication of the performance of your
page.

What is checked?
----------------
As PageSpeed Insights is using Lighthouse to analyse your page, there is quite some information available. Currently
this TYPO3 extension will store the following data: 

- Performance score 
- SEO score
- Accessibility score
- Best practises score
- PWA score

How to use it?
--------------
Using this extension is quite simple and consists of some simple steps:
- Install the extension by using composer or download it from the TYPO3 Extension Repository. 
- Set the option `Analyze with PageSpeed Insights` in the page properties of the pages you would like to analyze.
- Add a scheduler job of class `Execute console commands` with as `Schedulable Command` the option `pagespeedinsights:run`.
- Now make sure the scheduler job will run as often you would like to run the analysis. My personal preference is once a day.
- After a run you can see the results in the page properties with an overview of the results of the last year.

Configuration
-------------
No configuration is needed, but a 1 configuration option is available. If you want to monitor the API requests or if you
will need more than 25.000 check per day, you might want to set your Google API key. You can do this as an argument of the
command in the scheduler.

Dashboard
---------
Are you testing the new [dashboard for TYPO3](https://github.com/TYPO3-Initiatives/dashboard)? This extension will add
some widgets to show you the average results of your complete website. 

What's next?
------------
Some things on the roadmap:
- Show performance score in page module
- Add suggestions how to make your page faster
- Reporting of results

Sponsoring
----------
Do you like this extension and do you use it on production environments? Please help me to maintain this extension and
become a sponsor. Check [my website](https://www.richardhaeser.com/sponsoring) for more information. You can already
help me by [buying me a coffee](https://www.buymeacoffee.com/richardhaeser).

[![Buy me a coffee](https://cdn.buymeacoffee.com/buttons/default-orange.png)](https://www.buymeacoffee.com/richardhaeser)
