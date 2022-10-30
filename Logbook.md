## Logbook
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
  * We have to add a lot of tests before refactoring to avoid regression
  * I use blackbox testing and whitbox testing to create my tests
* Analyse **TemplateManager**
  * The first thing we see is the naming of variables like $tpl instead of template, $_user, $usefulObject... etc 
  * There is a real performance problem, because we make several calls to the repository to get data. In real life we request a DB server
  * We do no respect DRY principle, as we see for example, We get the quote from the repository knowing that we already have it in parameter
  * An **if else** without **{}** is really dangerous, because another developer by accident add just one line and it could break everything
* After refactoring TemplateManager
  * analyse for rule `['[quote:destination_link]' => '']` in `getQuotePlaceholdersData` method to delete it
    * We can check the template used when calling the `getTemplateComputed` with the user only
* I know that we can not change the signature of `getTemplateComputed` but passing an array of Quote Or/And User it's really bad practice, i think it's more better if we pass Object directly.
* ### :next_track_button: **To be done** :next_track_button:
  *  We can add two attributes $site(Site) and $destination(Destination) on Quote entity to avoid 2 sql call in `TemplateManager::getQuotePlaceholdersData()` <br> 
  ```php
  $site = SiteRepository::getInstance()->getById($quote->siteId);
  $destination = DestinationRepository::getInstance()->getById($quote->destinationId);
  ```
  <br>
  So there is 2 solutions: <br>
  1. We add $site and $destination on Quote entity with **EAGER** loading and we avoid 2 query to database but it's bad practice <br>
  2. We can request again Quote entity on `TemplateManager::getQuotePlaceholdersData()` with query builder(Join) to get $site and $destiantion and we will win one sql request <br>

* See with team to delete rule ['[quote:destination_link]' => '']`
* Try to not use $data array in `TemplateManager::getTemplateComputed()` and use object directly.
* We can move `getQuotePlaceholdersData` and `getUserPlaceholdersData` in 2 diffirent classes, like, `QuotePlaceHoldersDataService` and `UserPlaceHoldersDataService` and put each functional rules related (Single Responsibility). 
  But for now let's stay pragmatic and keep it simple, stupid. **If another developer wants to add a new key or modify one, he just has to update an array and that's it**. 

  