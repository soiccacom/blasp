![Local Image](./assets/icon.png)

# Blasp - Profanity Filter for Laravel

Blasp is a profanity filter package for Laravel that helps detect and mask profane words in a given sentence. It offers a robust set of features for handling variations of offensive language, including substitutions, obscured characters, and doubled letters.

## Installation

You can install the package via Composer:

```bash
composer require blaspsoft/blasp
```

## Usage

### Basic Usage

To use the profanity filter, simply call the `Blasp::check()` method with the sentence you want to check for profanity.

```php
use Blaspsoft\Blasp\Facades\Blasp;

$sentence = 'This is a fucking shit sentence';
$check = Blasp::check($sentence);
```

The returned object will contain the following properties:

- **sourceString**: The original string you passed.
- **cleanString**: The string with profanities masked (e.g., replaced with `*`).
- **hasProfanity**: A boolean indicating whether the string contains profanity.
- **profanitiesCount**: The number of profanities found.
- **uniqueProfanitiesFound**: An array of unique profanities found in the string.

### Example

```php
$sentence = 'This is a fucking shit sentence';
$check = Blasp::check($sentence);

echo $check->sourceString;       // "This is a fucking shit sentence"
echo $check->cleanString;        // "This is a ******* **** sentence"
echo $check->hasProfanity;       // true
echo $check->profanitiesCount;   // 2
print_r($check->uniqueProfanitiesFound); // ['fucking', 'shit']
```

### Profanity Detection Types

Blasp can detect different types of profanities based on variations such as:

1. **Straight match**: Direct matches of profane words.
2. **Substitution**: Substituted characters (e.g., `pro0fán1ty`).
3. **Obscured**: Profanities with separators (e.g., `p-r-o-f-a-n-i-t-y`).
4. **Doubled**: Repeated letters (e.g., `pprrooffaanniittyy`).
5. **Combination**: Combinations of the above (e.g., `pp-rof@n|tty`).

### Laravel Validation Rule

Blasp also provides a custom Laravel validation rule called `blasp_check`, which you can use to validate form input for profanity.

#### Example

```php
$request->merge(['sentence' => 'This is f u c k 1 n g awesome!']);

$validated = $request->validate([
    'sentence' => ['blasp_check'],
]);

// If the sentence contains profanities, validation will fail.
```

### Configuration

Blasp uses a configuration file (`config/blasp.php`) to manage the list of profanities, separators, and substitutions. You can publish the configuration file using the following Artisan command:

```bash
php artisan vendor:publish --tag="blasp-config"
```

## License

Blasp is open-sourced software licensed under the [MIT license](LICENSE).
