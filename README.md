# Installation
`composer require tombroucke/wp-models`

# Interacting with models
Create a new class for your custom post type

```php
namespace Otomaties\Events\Models;

use Otomaties\WpModels\PostType;

class Event extends PostType {
	/**
     * This method will override the parent method which shows the post date.
     */
    public function date() : DateTime
    {
        $date = $this->meta()->get('date');
        return $date ? DateTime::createFromFormat('Ymd', $date) : null;
    }
	
    public function time() : string
    {
        return substr($this->meta()->get('time'), 0, 5);
    }

	// ... more custom methods

    public static function postType() : string
    {
        return 'event';
    }
}
```

## Client Code
```php
$event = new Otomaties\Events\Models\Event(420);
esc_html_e($event->getId()); // Post ID
esc_html_e($event->title()); // Post title
esc_html_e($event->slug()); // Post slug
esc_html_e($event->meta()->get('meta_key')); // Meta (single)
esc_html_e($event->meta()->get('meta_keys', false)); // Meta (multiple)
esc_html_e($event->date()->format('d-m-Y')); // Custom method
esc_html_e($event->time()); // Custom method

$event->meta()->set('meta_key', 'meta_value');
$event->meta()->add('meta_key', 'meta_value_2');

// See src/PostType.php for all default methods
```

# Insert, update, delete & query models

## Inserting

```php
	use Otomaties\WpModels\PostTypeRepository;

	$repository = new PostTypeRepository(Event::class);
	$args = [
		'post_title' => 'Event title',
		'post_status' => 'draft',
		'post_content' => 'Event content',
		'meta_input' => [
			'key' => 'value'
		]
	]
	$event = $repository->insert($args); // returns instance of Event::class
```
or
```php
	$args = [
		'post_title' => 'Event title',
		'post_status' => 'draft',
		'post_content' => 'Event content',
		'meta_input' => [
			'key' => 'value'
		]
	]
	$event = Event::insert($args);
```
## Updating
```php
	use Otomaties\WpModels\PostTypeRepository;

	$event = new Event(420);
	$repository = new PostTypeRepository(Event::class);
	$args = [
		'post_title' => 'Event title',
		'post_status' => 'draft',
		'post_content' => 'Event content',
		'meta_input' => [
			'key' => 'value'
		]
	]
	$event = $repository->update($event, $args); // returns instance of Event::class
```
or
```php
	$event = new Event(420);
	$args = [
		'post_title' => 'Event title',
		'post_status' => 'draft',
		'post_content' => 'Event content',
		'meta_input' => [
			'key' => 'value'
		]
	]
	$event = Event::update($event, $args);
```
## Deleting
```php
	use Otomaties\WpModels\PostTypeRepository;

	$event = new Event(420);
	$repository = new PostTypeRepository(Event::class);
	$event = $repository->delete($event);
```
or
```php
	$event = new Event(420);
	$event = Event::delete($event);
```

## Querying
### All posts
```php
	use Otomaties\WpModels\PostTypeRepository;

	$repository = new PostTypeRepository(Event::class);
	$allEvents = $repository->find(); // Returns post type collection
	$tenEventsOffsetTen = $repository->find(null, 10, 10); // Returns post type collection
```
or
```php
	$allEvents = Event::find(); // Returns post type collection
```
### By id
```php
	use Otomaties\WpModels\PostTypeRepository;

	$repository = new PostTypeRepository(Event::class);
	$event = $repository->find(420)->first(); // Returns PostType object (Event object in this case)
```
or
```php
	$event = Event::find(420)->first(); // Returns PostType object (Event object in this case)
```
### Custom query
```php
	use Otomaties\WpModels\PostTypeRepository;

	$repository = new PostTypeRepository(Event::class);
	$args = [
		'meta_query' => [
			'relation' => 'OR',
			[
				'key' => 'date',
				'value' => date('Ymd'),
				'compare' => '>='
			],
			[
				'key' => 'date',
				'compare'=>'NOT EXISTS',
			]
		]
	];
	$event = $repository->find($args); // Returns Collection
```
or
```php
	$args = [
		'meta_query' => [
			'relation' => 'OR',
			[
				'key' => 'date',
				'value' => date('Ymd'),
				'compare' => '>='
			],
			[
				'key' => 'date',
				'compare'=>'NOT EXISTS',
			]
		]
	];
	$event = Event::find($args); // Returns Collection
```

