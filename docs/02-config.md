## Configuration Files

The Config File has 3 parts

## Projectname

This is just used for the name of the download zip

```json
{
    "projectname": "codemonkey"
}
```

## Files

This section defines the files you want generated

```json
{
"files": [
    {
        "name": "src/folder5/SimpleClass.php",
        "templates": [
            "randomclass.txt"
        ],
        "attributes": {
            "classname": "myClassName",
            "namespace": "\\Myvendor\\Myproject\\Myclass",
            "input": "{{$input1}}"
        }
    }
    ]
}
```

### files
Contains an array with objects

### files.name
Filename to save the file under. Folder will be created automatically

### files.templates
Array of filenames that are used as templates. Script will look for these in 
the folder you defined as template folder

### files.attributes
Object with values to be inserted into the template placeholders. Note that the
key is the placeholder name and the value is what will be put there

You can also insert placeholders into the value field, these will later be overwritten
by the variables you post

## Attributes
Here you define values you want to add dynamically. Codemonkey will make you a 
page with input fields where you can place your desired values before generating
your code

```json
{
    "attributes": [
        {
            "label": "Namespace for Class 1",
            "name": "namespace1",
            "default": "Leedch\\Codemonkey\\Demo2"
        }
    ]
}

```
