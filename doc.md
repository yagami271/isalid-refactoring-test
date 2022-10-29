### Logbook
* Update fzaninotto/faker lib to fix `join(): Passing glue string after array is deprecated. Swap the parameters`
* List all placeholders
  * [quote:summary_html]
  * [quote:summary]
  * [quote:destination_name]
  * [quote:destination_link]
  * [user:first_name]
* On variable $data we can got **quote** or **user**
* :warning: Bug detected on url site link generated on template, a url must not contain spaces
* Before start refactoring 
  * we have to add a lot of tests before refactoring to avoid regression
  * I use blackbox testing and whitbox testing to create my tests

* Analyse TemplateManagerTest
  * The first thing we see is the naming of variables like $tpl instead of template.. etc 
  * There is a real performance problem, because we make several calls to the repository to get data. In real life we request a DB server
  * We do no respect DRY principle, as we see for example, We get the quote from the repository knowing that we already have it in parameter
  * An **if else** without **{}** is really dangerous, because another developer by accident add just one line and it could break everything
* After refactoring TemplateManager
  * analyse for `['[quote:destination_link]' => '']` in `getQuotePlaceholdersData` method the delete it
    * We can check the template used when calling the `getTemplateComputed` with the user only
* I know that we can not change the signature of `getTemplateComputed` but passing an array of Quote Or/And User it's really bad practice, i think it's more better if we pass Object directly.