## Setup

### Composer

Pull this package in through Composer. (development version `dev-master`)

```
{
    "require": {
        "jumper423/laravel-filter": "~1.0"
    }
}
```

    $ composer update


##Пример

```php

use jumper423\LaravelTrait\Filter;

class Post{
    use Filter;
    
    protected $filterColumns = [
        'user_name' => 'user.first.name'
    ];
}

$posts = Post::join('users', 'users.id', '=', 'posts.user_id')
    ->filter([
        [
            'name' => 'user_name'
            'value' => 'Вася'
        ]
    ])->get();
```
