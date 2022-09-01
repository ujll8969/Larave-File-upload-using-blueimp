# Laravel-File-upload-using-blueimp
Multiple file upload using blueimp in larave 8, Multiple form on same url.

<a href="https://blueimp.github.io/jQuery-File-Upload/" target="_blank">DEMO</a>


If you want to use same thing on your project follow below instructions.

**To install croppa.**

composer require bkwld/croppa

**To install fileupload**
Gargron / fileupload

paste the below script inside require in composer.json file.
```json
"require": {
    "gargron/fileupload": "~1.4.0"
  }
```
and run  **composer update --no-scripts** it will install the fileupload.
 


For using the multiple file upload section on same page you have change id of form only and access the files with different form id.



