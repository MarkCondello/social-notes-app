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