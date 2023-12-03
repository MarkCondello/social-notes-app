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

We can also use policies as middleware in routes as well like so:
`->middleware('can:delete,post')`