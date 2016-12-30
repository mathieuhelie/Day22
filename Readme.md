# Card 22 - Functional Javascript Tests

https://www.chapterthree.com/blog/javascript-testing-comes-to-drupal-8

Drupal 8 replaces the legacy SimpleTest framework with the more complete PHPUnit Library, which includes both unit testing and full functional testing systems. This made it possible to add JavaScript and AJAX API tests in Drupal 8.1.

## Installing PhantomJS
First, we need a headless test browser called PhantomJS to be installed and running in our VM in order to use the JS tests. If you are using Drupal-VM, change these settings in config.yml:
```YAML
installed_extras:
  - nodejs

nodejs_npm_global_packages: 
  - name: phantomjs-prebuilt
```
Now run vagrant provision or vagrant reload --provision to install phantomjs.

SSH into the VM, go to the project root (where the vendor directory is) and run `phantomjs --ssl-protocol=any --ignore-ssl-errors=true vendor/jcalderonzumba/gastonjs/src/Client/main.js 8510 1024 768`. The browser will now wait to be called from PHPUnit. Open a new terminal session to continue working.

## Setting up a test run
Copy the file core/phpunit.xml.dist to core/phpunit.xml. Change the env parameters to these:
```XML
    <!-- Example SIMPLETEST_BASE_URL value: http://localhost -->
    <env name="SIMPLETEST_BASE_URL" value="http://drupalvm.dev"/>
```

Make sure sites/simpletest is writeable by the webserver user (www-data in Drupal-VM) and you are set.

Tip: If you need to squeeze more performance from the test framework, you can use a special Linux mount called /dev/shm (shared memory) to prevent files being written to disk and synced between your Host and VM. In the VM, remove sites/simpletest and use `ln -s /dev/shm/simpletest simpletest` to use a memory-only filesystem. _Bonus points: For maximum performance, you may use a sqlite database symlinked to shared memory instead of the default mysql connection. To do this, you will need to create an empty site without any db connection settings in settings.php, and use the SIMPLETEST_DB env variable in phpunit.xml._

We are now ready to run our test suite. Go to your Drupal root and run:

`sudo -u www-data -E ../vendor/bin/phpunit -c core core/modules/book/tests/src/FunctionalJavascript/BookJavascriptTest.php`

This will run, as the webserver user, the phpunit script in the vendor directory, telling it to use the configuration in core/ and to run the test BookJavascriptTest.php.

When the PHPUnit welcome message appears, switch to your PhantomJS terminal window and watch the fun. Switch back when it's over to see the results of the test run.

## Writing Javascript tests.

Read carefully through core/modules/book/tests/src/FunctionalJavascript/BookJavascriptTest.php. It validates the drag and drop reordering of book pages. You may also read through ClickSortingAJAXTest and ExposedFilterAJAXTest for examples of tests on views' AJAX features.

Now we are going to write our first Javascript test case.

We will extend the module we wrote on Day 3 to use Drupal's Form AJAX states API.

Add a '#states' property to the text input box that sets it visible only when a particular value is active on another form element. See: https://api.drupal.org/api/drupal/core!includes!common.inc/function/drupal_process_states/8.2.x

Using BookJavascriptTest as your model, write a test case in the Day 3 module that validates:
1. The form loads with the text input hidden.
2. After changing the value of the other input, the text input is visible and can be changed.
3. The form can be saved.
4. After saving, the text input is still visible.
5. It can be hidden again by changing back the other form element.
6. Saving the form again reloads the form with the text input hidden.

Hint: Drupal's test case now enforce strict configuration schema, make sure your module has a schema file in config/schema for its configuration data, or the test page will return a site error. https://www.drupal.org/docs/8/api/configuration-api/configuration-schemametadata
