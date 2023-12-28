## Policies
Laravel allows us to setup custom policies which are often used to validate a users role or permissions to perform and action or view a specific area.

Run the following command to generate a new policy:
`php artisan make:policy PostPolicy --model=Post`
Add ing `--model=Post` ties the policy to the model specified.

This command will generate a policy class which allows us to specify which crud operations are allowed or not. We need to specify the logic for each of these.

Once thats completed, we need to registed this policy in the `App\Providers\AuthServiceProvider.php` file. This is done with static references to the associated classes like so:

```
protected $policies = [
  Post::class => PostPolicy::class,
];
```

In this code we are saying the `Post` class's policy is the `PostPolicy` class.

We can use the directives defined in the `PostPolicy` in the .blade.php files by using the `@can` directives.

We can also use the post *policy* as middleware in routes as well like so:
`->middleware('can:delete,post')`

Policies are tied to a model for CRUD operations for a specific resource. In this example we are tying the Post model to the policy.

## Gates
We use a *gate* to allow access to private areas. In this project we set up the `accessAdminPages` in the boot method of AuthServiceProvider.php file and spell out which type of user gets past the *gate*.
Then in the routes file, we use the `->middleware(can:accessAdminPages)` to allow access to sepcific routes.

## Laravel file handling
Our application allows users to upload Avatar images to the `/storage/app` directory. This is the best practice rather than allowing users to upload files to the `/public` directory.
In order to symlink the uploaded files to public, we run the following command:
`php artisan storage:link`
We still reference the `/storage` path to load files in the application.

Resizing images is done with the `Intervention\Image` package [here](https://github.com/Intervention/image).

### Avatar accessor
In the User model, we have an accessor which changes the logic for accessing the `$user->avatar`. It checks if the image exists in the table, and if so, loads the correct path for where it is located. Otherwise a fallback image is provided.

### Scout Laravel search:
```composer require laravel/scout```
```php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"```

Add the associated settings to the model where it needs to be searchable. See the Post model as an exanple..
In the .env add the settings for the Scout driver:
```SCOUT_DRIVER=database```
Use Scout search on a model like so: `Post::search()`

### Events & Listeners
Events can be added to the system for them to be listened to. An example of an event could be when a user creates a blog post or loggs in or performs any type of action.
From with in the `EventServiceProvider.php` we can scaffold events and listeners with creating the associated class files like so:
```
use \App\Events\ExampleEvent;
use \App\Listener\ExampleListener;
...

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ExampleEvent::class => [
            ExampleListener::class
        ],
```
Then we can run `php artisan event:generate` and Laravel will make the class files we have referenced.
With this setup, we can use the Listener file generated to perform an action once the event occurs by using `event(new ExampleEvent());` in our Controllers or Models etc.

### Pusher (Broadcasting)
We are using the `Pusher` service to broadcast data on the platform. Pusher allows for real time socket connections. My (account)[https://dashboard.pusher.com/] is connected to my Github.

We need to add in the App Keys provided by Pusher and install this package:
`composer require pusher/pusher-php-server`.

To create a new Event we can run the artisan command like this:
`php artisan make:event ChatMessage`
In the constructor of that file, we defined what data is received / broadcasted.

