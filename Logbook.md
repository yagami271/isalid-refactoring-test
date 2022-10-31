## Logbook
* Update fzaninotto/faker lib to fix `join(): Passing glue string after array is deprecated. Swap the parameters`
* List all placeholders
  * [quote:summary_html]
  * [quote:summary]
  * [quote:destination_name]
  * [quote:destination_link]
  * [user:first_name]
* On variable $data we can got **quote** or **user**
* :warning: Bug detected on url site link generated on template. The url shouldn't contain spaces
* Before start refactoring 
  * Add several tests before refactoring to avoid regression
  * I use blackbox testing and whitbox testing to create my tests
* Analyse **TemplateManager**
  * It is not recommended to use naming variables like $tpl, $_user, $usefulObject... etc. Instead, consider meaningful names such as: $template, $user... etc
  * There is a real performance issue. Because we make several calls to get data from the repository. However, conconcretely, we request a DB server
  * We do no respect DRY principle. For example: we get the quote from the repository knowing that we already have it in parameter
  * An **if else** without **{}** is really dangerous. The developer may accidentally add just one additional line and it could break everything
* After refactoring TemplateManager
  * analyse for rule `['[quote:destination_link]' => '']` in `getQuotePlaceholdersData` method to delete it
    * We can check the template used when calling the `getTemplateComputed` with the user only
* We can not change the signature of `getTemplateComputed`. However, passing an array of Quote Or/And User it's a really bad practice. it's safer if we pass the Object directly.


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
* We can move `getQuotePlaceholdersData` and `getUserPlaceholdersData` in 2 different classes, like, `QuotePlaceHoldersDataService` and `UserPlaceHoldersDataService` and put each functional rules related (Single Responsibility). 
  But for now let's stay pragmatic and keep it simple, stupid. **If another developer wants to add a new key or modify one, he just has to update an array and that's it**. 
 

## Other solution to be discussed
Check branche : https://github.com/yagami271/isalid-refactoring-test/tree/feature/strategy-pattern <br> 
* The idea is to separate the responsibilities of each entity placeholders data. If for example we want to add a new placeholder for the product, all we have to do is to create a new class `ProductPlaceholdersDataStrategy` and implements `PlaceholdersDataStrategyInterface` without forgetting to inject the new strategy in the `TemplateManager` class
* With symfony's dependency injection ! we can easily inject automatically all services that implements `PlaceholdersDataStrategyInterface` into `TemplateManager`, so for new placeholders feature we just need to create a new class that implement `PlaceholdersDataStrategyInterface`