#
# Table structure for table 'pages'
#
CREATE TABLE pages
(
    tx_pagespeedinsights_check tinyint(4) DEFAULT '0' NOT NULL,
    tx_pagespeedinsights_results tinyint(4) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_pagespeedinsights_results
(
    url                 tinytext,
    page_id             int(11)     DEFAULT '0' NOT NULL,
    strategy            varchar(10) DEFAULT 'mobile',
    date                int(11)     DEFAULT '0' NOT NULL,
    reference           varchar(100),
    performance_score   tinyint(4)  DEFAULT '0' NOT NULL,
    seo_score           tinyint(4)  DEFAULT '0' NOT NULL,
    accessibility_score tinyint(4)  DEFAULT '0' NOT NULL,
    bestpractices_score tinyint(4)  DEFAULT '0' NOT NULL,
    pwa_score           tinyint(4)  DEFAULT '0' NOT NULL
);
