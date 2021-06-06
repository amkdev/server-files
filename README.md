![Plugin Icon: A folder with a list and character i.](./src/icon.svg) 

# Server Files for Craft CMS 3.x

Retrieve a list of files based on a specified folder path and extracts file information (optionally, currently onyl Exif image data)

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

```
cd /path/to/project
```

2. Then tell Composer to load the plugin:

```
composer require amkdev/server-files
```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Server Files.

## Introduction

This little plugin retrieves a list of files based on a specified folder path.

This might be useful to you if

- You're running Craft 3.1 or above
- You need to retrieve files plus file information, not managed by Craft CMS, from a folder on your web server and return the output in Twig

## Examples

### Output the jpg files of a directory and the Exif data title and caption

Inside /uploads/images there are 3 image files:
```
22 Apr 22:54 image01.jpg
22 Apr 22:54 image02.jpg
22 Apr 22:54 icon.gif
```

In our Twig templates we set variables and give the server files a folder path for the search (required) and define (optionally) the information we need and regex pattern:
```
{% set settings =
    {
        path: 'uploads/images',
        pattern: '*.jpg',
        info: 'exif[title,caption]'
    }
%}
{% set images = craft.serverfiles.config(settings) %}

{% for image in images %}
    <img src="{{ image.file }}" 
         alt="{{ image.name }}"  
         title="{{ image.info.exif.title }}" 
         data-caption="{{ image.info.exif.caption }}">
{% endfor %}
```
Additionally you can use "full" {{ image.full }} to get the absolute path of a file.

This example Twig code would output:
```
<img src="/uploads/images/image01.jpg" 
     alt="image01.jpg" 
     title="Flower" 
     data-caption="A beautiful flower below the tree.">
<img src="/uploads/images/image02.jpg" 
     alt="image02.jpg" title="Tree" 
     data-caption="A tree in a field of flowers.">

```
If none of the configured Exif information is present, an empty string is returned for the data. 

## Disclaimer

This plugin is distributed free of charge under the MIT License. The author is not responsible for any data loss or issues resulting from use of the plugin. 

## Special Thanks 

This plugin is based on [GetFiles](https://github.com/amkdev/craft-getfiles) by You & Me Digital. Thanks for the simple and easy starting base. 

Additionally this plugin uses [PHPExif](https://github.com/PHPExif) by Tom Van Herreweghe.