About this directory:
=====================

By default, this application is configured to load all configs in
`./config/autoload/{,*.}{global,local,APP_ENV}.php`. Doing this provides a
location for a developer to drop in configuration override files provided by
modules, as well as cleanly provide individual, application-wide config files
for things like database connections, etc. Note `APP_ENV` is the current environment, it can be 'production','development.
