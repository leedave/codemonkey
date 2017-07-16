## Template Files

## Example

You can find template examples in the `templates` folder of this Component

## Placeholders

Template files are plain text files that contain placeholders that allow you to
replace these with dynamic variables. 

Optionally can change the standard placeholders {{$variable}} in the File Class
```php
$file = new \Leedch\Codemonkey\File();
$file->setPlaceHolderStart('[[56:');
$file->setPlaceHolderEnd(']]');
//Will replace [[56:variableName]] instead of {{$variableName}} in template files
```

## Defining templates

You define your template files in your json config

```json
{
    "projectname": "TestCase",
    "files": [
        {
            "name": "tests/testcase.php",
            "templates": [
                "../vendor/leedch/codemonkey/templates/testcase.php.txt"
            ]
        }
}
```
You can stack more than one template in the array. All templates are simply
appended after each other