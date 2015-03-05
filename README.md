google-drive-php-cl
===================

### Features
* Google Drive acces from command line

### Requirements
* [google-api-php-client](https://github.com/google/google-api-php-client)

### Functions
* Upload
* Download
* List ids
* Create folder

### Usage
php google-drive-php-cl/google_download.php <id>+
php google-drive-php-cl/google_upload.php <parent id> <file path>+
php google-drive-php-cl/google_folder.php <parent id> <name>+
php google-drive-php-cl/google_list.php <parent id>+

Arguments marked with a + can be passed multiple times.