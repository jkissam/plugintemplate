# plugintemplate

Template for Wordpress plugins

To use:

1. make a copy of all files, change file name of plugintemplate.php to newpluginname.php

2. run a search and replace from plugintemplate to newpluginname in newpluginname.php, admin.css and admin.js

You can do this from the command line using the following:

```bash
grep -rl "plugintemplate" . | xargs sed -i 's/webskillet14/newpluginname/g'
```
