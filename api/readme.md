## PHP QA Tools implemented with Grunt

The code that goes with [this article on mariehogebrandt.se](http://mariehogebrandt.se/articles/using-grunt-php-quality-assurance-tools/)




To get up and running install

- "node" for javascript
- "npm" for javascript package management
- "grunt-cli" grunt's CLI
- "composer" for php package management

Then run

$ composer install

$ npm install

$ grunt (builds the app and runs the tests)

$ php artisan serve (laravel's built in server for http://localhost:8000) serves files from /public

Put your views in app/views directory and assets in the public/ directory.

TODO: Create src/ for html, scss, js, img, etc. for grunt to assemble into the public/ (instead of dist/) directory.

public/css
public/images
public/fonts
public/js

To call from a Laravel view...

{{ HTML::script('js/scrollTo.js'); }}

{{ HTML::style('css/css.css'); }}