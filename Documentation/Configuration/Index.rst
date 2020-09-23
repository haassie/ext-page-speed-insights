.. include:: ../Includes.txt


.. _configuration:

=============
Configuration
=============

Target group: **Developers, Integrators**

There is not that much of configuration needed to make this extension work. The only mandatory configuration is to
schedule the execution of the analyzer. Besides that, there are some small configuration options.


Scheduler task
==============
As said, it is important to make sure your pages are analyzed frequently. The easiest way to do is to add a scheduler
task in the backend of TYPO3.

- Create a task task of the class :guilabel:`Execute console commands`
- Choose recurring as type and set a proper frequency
- As the schedulable command you choose :guilabel:`pagespeedinsights:run:`
- After saving this task you will get the option to add your Google API key. You can find more information how to get an API key on the `Developer website of Google <https://developers.google.com/speed/docs/insights/v5/get-started#APIKey>`__.

.. tip::

    We advise you to do the checks at least once a day. Sometimes the API have problems connection to websites and when you only check once a day, you wont have data of that day.

Permissions
===========
As the fields in the page properties are excludable fields, you need to give access to those fields for your users. The fields you need are: :guilabel:`tx_pagespeedinsights_check` and :guilabel:`tx_pagespeedinsights_results`.

Configuration options
=====================

There are some small configuration options you can set.

Disable warnings
----------------
The extension will give an editor warnings in the page module when a page is to slow. If you want to disable those
additional warnings, you can set :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['disableWarnings']` to true.

Disable info in page module
---------------------------
If you don't want to get feedback in the page module, but only in the page properties, you can set the option :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['disableInfo']` to true.

Strategy to show
----------------
By default, your results are shown for mobile devices. If you want to view the results of desktop, just set :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['strategyToShow']` to :php:`desktop`.

Only check specific categories
------------------------------
Sometimes you are just not interested in all categories. By overriding :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['categories']` you can define which categories should be checked. If you want to disable the check on PWA features, you just use the following lines of code.

.. code-block:: php

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['categories'] = [
        'performance',
        'seo',
        'accessibility',
        'best-practices'
    ];
