# Gousto API

## Objective

Gousto’s technical infrastructure includes an API Gateway.  The gateway offers a number of recipe operations.  Recipes contain a lot of
information such as cuisine, customer ratings & comments, stock levels and diet types.

Your task is to design, develop and deliver to us your version of a set of recipe operations.

Your solution should meet our functional and nonfunctional requirements below.


## Structure of the project

The project is based on [Laravel framework](https://laravel.com/), using a simple CSV file with recipes as a data source. 
Of course, this must not be used in any production nor staging deploy.

The project is structured as follows:

* Rating resource: provides rating capabilities
* Recipe resource: handles everything miscellaneous
* Custom Store & Update request validators: validates the HTTP requests
* Models and other classes created in order to explain how to handle the same test with a more complex scenarios
* A set of interfaces, in order to complain with most of [SOLID principles](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design)

The request are handled by `App\Http\Controllers\RecipeController` that provides a set of actions to be called on every request.

As in every Laravel project, each action is mapped to a route in `routes\api.php`. The routes and mappings are self explanatory, so only need to take a look at it.

### Routes:

* HTTP GET `api/gousto/recipe/{id}` - Gets a recipe by its ID
* HTTP GET `api/gousto/recipes`  - Gets all recipes

* HTTP POST `api/gousto/recipe/rate/{id}` - Rates a recipe. Valid payload in the form of:
```
{
	"rate": <rate>
}
```
Where `<rate>` has to be a number between 1 and 5 (Checked via a Request/RateRecipePost.php)

* HTTP POST `api/gousto/recipe/store` - Stores a recipe
* HTTP PUT `api/gousto/recipe/update/{id}` - Updates a recipe

### Data source file

By far, the hardest part was handling all CRUD operations without the help of a DBMS, but a CSV file.

```
The CSV was used ONLY because it was a specific & mandatory requirement for the test. It should NOT be used nor in a production environment nor in a simple QA one. It does NOT provides any single multi thread/process capabilites, nor locking data, nor any comfortable way of performing CRUD (specially UPDATES). So it was used only as a matter of showing development capabilites
```

While creating the project data model was done by creating a Recipe Model, this is not suitable for 
a production environment, but the CSV data comes together with a big recipe payload.
This makes the losing of information about the way it was built prior to that, 

This leads to losing the information of about how many tables were in the first place, or how are they structured.

```
As a matter of showing how this could have been achieved, I created several  models that would have 
hold the information in a normal database.
```

### Main classes & interfaces

> * RecipeController: Main and only controller that handles all the necessary actions for managing recipes

> * RecipeService: Handles the business ligic to perform any internal actions and calculations for achieving results.
> * RatingService: Handles the rate of a recipe. It creates a new row any time a rate is requested. In order to calculating an average if desired.
> * StreamService: Serves as a middleware between the prior services and the phisycal file managing.
> * FileService: Base class for handling file acces to disk
> * FileReader & FileWriter: Classes to manage the read/write to disk.

> * DataTransactionInterface: Contract for getting & saving recipe data
> * RatingInterface: Contract for rating a recipe
> * StramHandlerInterface: Contact for middleware actions to disk
> * FileXXXXInterfaces: Contracts for managing disk access

## Installation steps

1. Clone this repo to the document root of the web server you're using and run `composer install` in the command line.
2. Give permissions to the CSV files in the `\data` directory
3. Copy `.env.example` to `.env`, in order to take all the configuration variables from there.

Start your web server (for developing purposes I use the built in that PHP has) typing `php artisan serve` in the project 
folder command line, and create a `\data` directory in the root folder (If not present), as this direactory will hold the CSV file.

## Usage

Being a HTTP REST API the usage is straightforward, just send the needed HTTP request to the server and it will return a JSON or a HTTP response telling what went wrong.

The API was designed to be the most accurate as possible when returning HTTP responses, so luckily you don't have to expect a HTTP 400 for everything.

The API was buils keeping in mind [HTTP specifications](https://www.w3.org/Protocols/Specs.html) as much as possible.

For example, a POST request creating a new recipe __should__ return in the HTTP header a HTTP GET to the newly created resource. Things like that were followed when possible, having the time constraints for the exercise.

```
In case the user needs/wants a different directory or file, just change the corresponding environment 
variables that are in env & env.example files.
```

## Tests

In order to accomplish proper refactoring of the code, several integration test were provided.

For a matter of timing, not class-to-class unit tests were developed, but it's importan to point out that a high rate of code coverage is paramount when developing a medium to big size software.

The provide test are in `test\Feature` folder, using the API that Laravel provides (Is a combination of PHPUNIT functions and some core Json handling methods).

The tests make some HTTP requests to all and every single endpoint of the project, and checks whether the HTTP status returned is the corresponding to the type of payload/request, and also checks that the response structure is correct.

For more details check the tests, are pretty self explanatory.

The execution of the tests are in the form `vendor/phpunit/phpunit/phpunit` for executing all tests, of using the `vendor/phpunit/phpunit/phpunit --filter <pattern>` for a single test.

Please check the [PHPUnit docs](https://phpunit.readthedocs.io/en/8.1/) for more details.


## Using with different clients

Being a HTTP REST API, using it with different clients is straightforward, as long as the client has AJAX calls capabilities.

For example, the API can be requested using the common JQuery `$.ajax({})` methods, `http` Angular calls, or whatever library that provides this type of calls.

The API will return a JSON response that can be parsed via any Javascript client, or of course, natively.

Examples:

* Postman: 

When using the project requesting data with Postman, remember to add `X-Requested-With` => `XMLHttpRequest` 
headers when POST or PUT, as if not, Laravel won't realize that AJAX request were made and will not trigger internal HTTP UNPROCESSABLE ENTITY response, as the Validator will not be called automatically.

## Trobleshooting

In case any errors appear, first execute the following steps:

1. Check that the `storage` directory has permissions and the owner was set to the web server, for that:

Execute `sudo chown -R $USER:<webserver-user> storage`, where `<webserver-user>` is the one according to 
your OS and web server. 

If you're using __apache__, execute this in the command line: `ps aux | egrep '(apache|httpd)'` and the first
column will be the web server user (In my case `daemon`).

If you're using __nginx__, execute `ps aux|grep nginx|grep -v grep`

Then, execute:
```
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```
And try the HTTP request again.

2. If you are having troubles sending HTTP requests to some route, check the routes names by executing:
`php artisan route:list`. This will show a list of all the available routes (Remember to precede the route
names with `public`, in case you haven't set the web server to make requests against that folder, as indicated
in the Laravel site)

3. If you're having trouble when executing step number 2, for example with a permission message of any type, 
execute the step 1 and try again.
