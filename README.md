# PHP Example for Apache Ignite

The example shows how to connect to an Apache Ignite cluster from PHP side and to:
<lu>
<li>
Preload data using PDO INSERT statements.
</li>
<li>
Query data using PDO SELECT queries.
</li>
</lu>

## Overview

The example is based on <a href="https://apacheignite-mix.readme.io/docs/php-pdo">PHP guide</a> and covers
advanced topics/functionality such as:
<lu>
<li>
Affinity collocation configuration for data stored in different but related caches.
</li>
<li>
Execution of distributed SQL queries with joins in collocated mode.
</li>
</lu>

## Structure

The project includes two folders "config" and "sources".

The first one holds the configuration that has to be used for Apache Ignite cluster nodes.

The sources contain PHP scripts for data preloading and querying.

The blog post below provides the deep dive for the example: https://dzone.com/articles/apache-ignite-enables-full-fledged-sql-support-for
