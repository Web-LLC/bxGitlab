bxGitlab
========

Web-hooks for BitBucket to integrate with Bitrix24 Self-hosted

How to use
==========

1. You need to have both working BitBucket and Bitrix24.
2. Put "hooks" folder anywhere within document root of your Bitrix24.
3. Create custom user field named UF_BITBUCKET_ID in Bitrix24. Fill in an ID for each user.
4. Choose Hooks in a project settings in BitBucket, add POST hooks and fill in an URL for commithandler.php
5. Enjoy :)

Distribution
============

Please feel free to modify and use (GPLv2).
Pull requests are strongly appreciated.
